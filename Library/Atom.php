<?php

namespace Jekxyl;

use Hoa\File;
use Hoa\Xml;

class Atom
{
    protected $_posts = null;

    public function __construct(array $posts)
    {
        $this->_posts = $posts;
    }

    public function render()
    {
        $xml = new Xml\ReadWrite(
            new File\ReadWrite('hoa://Jekxyl/Dist/atom.xml')
        );
        $date                   = date('c');
        $channel                = $xml->channel;
        $channel->pubDate       = $date;
        $channel->lastBuildDate = $date;
        $url                    = rtrim($channel->link->readAll(), '/');

        foreach ($this->_posts as $post) {
            $item              = $channel->addChild('item');
            $item->title       = $post->getTitle();
            $item->pubDate     = $post->getDate();
            $item->link        = $url . $post->getUrl();
            $item->guid        = $url . $post->getUrl();
            $item->guid->writeAttribute('isPermaLink', 'true');
        }

        return;
    }
}
