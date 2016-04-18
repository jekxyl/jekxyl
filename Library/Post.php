<?php

namespace Jekxyl;

use DOMXpath;
use DateTime;
use Hoa\File;
use Hoa\Http;
use Hoa\Router;
use Hoa\Stringbuffer;
use Hoa\Xml;
use Hoa\Xyl;

class Post
{
    const LIST_OF_METAS = ['title', 'layout', 'date'];

    protected $_file     = null;
    private $_streamName = null;
    protected $_xyl      = null;
    protected $_router   = null;
    protected $_metas    = [
        'date'   => '1970-01-01T00:00:00+00:00',
        'layout' => 'Default',
        'title'  => 'No title'
    ];

    public function __construct(File\SplFileInfo $file, Router $router)
    {
        $this->_file       = $file;
        $this->_streamName = $this->_file->getPathname();
        $this->_router     = $router;

        $this->computeMetas();
        $this->computeOutputDirectory();
        $this->computeXyl();

        return;
    }

    public function render()
    {
        return $this->getXyl()->render();
    }

    protected function computeMetas()
    {
        $xyl = new Xyl(
            new File\Read($this->_file->getPathname()),
            new Http\Response(),
            new Xyl\Interpreter\Html(),
            $this->_router
        );
        $ownerDocument = $xyl->readDOM()->ownerDocument;
        $xpath         = new DOMXpath($ownerDocument);
        $query         = $xpath->query('/processing-instruction(\'xyl-meta\')');

        for ($i = 0, $m = $query->length; $i < $m; ++$i) {
            $item  = $query->item($i);
            $meta  = new Xml\Attribute($item->data);
            $name  = $meta->readAttribute('name');
            $value = $meta->readAttribute('value');

            if (false === in_array($name, static::LIST_OF_METAS)) {
                continue;
            }

            $this->_metas[$name] = $value;
            $item->parentNode->removeChild($item);
        }

        $buffer = new Stringbuffer\Read();
        $buffer->initializeWith($xyl->readXML());
        $this->_streamName = $buffer->getStreamName();

        return;
    }

    public function getMetas()
    {
        return $this->_metas;
    }

    protected function computeXyl()
    {
        $layout  = 'hoa://Jekxyl/Source/Layouts/' . $this->getLayoutFilename();
        $overlay = $this->_streamName;
        $router  = $this->getRouter();

        $this->_xyl = new Xyl(
            new File\Read($layout),
            new File\Write($this->getOutputPathname()),
            new Xyl\Interpreter\Html(),
            $router
        );
        $this->_xyl->setTheme('');
        $this->_xyl->addOverlay($overlay);

        $this->computeData();

        $this->_xyl->interprete();

        return;
    }

    public function getXyl()
    {
        return $this->_xyl;
    }

    protected function computeData()
    {
        $data            = $this->_xyl->getData();
        $data->title     = $this->getTitle();
        $data->date      = $this->getDate();
        $data->timestamp = $this->getTimestamp();
    }

    protected function computeOutputDirectory()
    {
        $outputDirectory = dirname($this->getOutputPathname());

        if (false === is_dir($outputDirectory)) {
            File\Directory::create($outputDirectory);
        }

        return;
    }

    public function getOutputPathname()
    {
        return 'hoa://Jekxyl/Dist/' . ltrim($this->getUrl(), '/');
    }

    public function getRouter()
    {
        return $this->_router;
    }

    public function getTitle()
    {
        return $this->getMetas()['title'];
    }

    public function getDate()
    {
        return $this->getMetas()['date'];
    }

    public function getTimestamp()
    {
        $date   = $this->getDate();
        $format = DateTime::createFromFormat(
            DateTime::ISO8601,
            $date
        );

        return $format->format('U');
    }

    public function getLayoutFilename()
    {
        return $this->getMetas()['layout'] . '.xyl';
    }

    public function getUrl()
    {
        return $this->_router->unroute(
            'post',
            [
                'pathname' => $this->_file->getRelativePathname()
            ]
        );
    }
}
