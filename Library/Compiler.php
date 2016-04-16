<?php

namespace Jekxyl;

use Hoa\File;
use Hoa\Xyl;
use Hoa\Http;

class Compiler
{
    protected $_router = null;

    public function __construct()
    {
        $this->_router = require 'hoa://Application/Source/Router.php';

        return;
    }

    public function reset()
    {
        (new File\Directory('hoa://Application/Dist'))->delete();
        File\Directory::create('hoa://Application/Dist');
    }

    public function buildPosts()
    {
        $finder = new File\Finder();
        $finder
            ->in('hoa://Application/Source/Posts/')
            ->files()
            ->name('/\.xyl$/')
            ->sort(function ($a, $b) {
                return -1 * strcmp($a->getPathname(), $b->getPathname());
            });

        foreach ($finder as $file) {
            $xyl = new Xyl(
                new File\Read('hoa://Application/Source/Layouts/Default.xyl'),
                new Http\Response(),
                new Xyl\Interpreter\Html(),
                $this->_router
            );
            $xyl->addOverlay($file->getPathname());
            $xyl->render();
        }
    }
}