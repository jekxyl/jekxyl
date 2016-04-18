<?php

namespace Jekxyl;

use Hoa\File;
use Hoa\Xyl;

class Compiler
{
    protected $_router = null;
    protected $_posts  = [];

    public function __construct()
    {
        $this->_router = require 'hoa://Jekxyl/Source/Router.php';

        return;
    }

    public function reset()
    {
        (new File\Directory('hoa://Jekxyl/Dist'))->delete();
        File\Directory::create('hoa://Jekxyl/Dist');
    }

    public function buildPosts()
    {
        $finder = new File\Finder();
        $finder
            ->in('hoa://Jekxyl/Source/Posts/')
            ->files()
            ->name('/\.xyl$/')
            ->sort(function ($a, $b) {
                return -1 * strcmp($a->getPathname(), $b->getPathname());
            });

        $this->_posts = [];

        foreach ($finder as $file) {
            $post = new Post($file, $this->_router);
            $post->render();

            $this->_posts[] = $post;
        }

        return;
    }

    public function buildIndex()
    {
        $xyl = new Xyl(
            new File\Read('hoa://Jekxyl/Source/Layouts/Default.xyl'),
            new File\Write('hoa://Jekxyl/Dist/index.html'),
            new Xyl\Interpreter\Html(),
            $this->_router
        );
        $posts = [];

        foreach ($this->_posts as $post) {
            $posts[] = [
                'title'     => $post->getTitle(),
                'url'       => $post->getUrl(),
                'timestamp' => $post->getTimestamp(),
                'date'      => $post->getDate(),
            ];
        }

        $data = $xyl->getData();
        $data->posts = $posts;

        $xyl->addOverlay('hoa://Jekxyl/Source/Index.xyl');
        $xyl->render();

        return;
    }

    public function buildAssets()
    {
        $source      = 'hoa://Jekxyl/Source/Public/';
        $destination = 'hoa://Jekxyl/Dist/';

        File\Directory::create($destination);

        $finder = new File\Finder();
        $finder
            ->in($source)
            ->files()
            ->directories()
            ->maxDepth(1);

        foreach ($finder as $file) {
            $file->open()->copy($destination);
            $file->close();
        }

        return;
    }
}
