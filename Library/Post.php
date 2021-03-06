<?php

namespace Jekxyl;

class Post extends Document
{
    public function getUrl()
    {
        return $this->_router->unroute(
            'post',
            [
                'pathname' => preg_replace('/\.xyl$/', '.html', $this->_file->getRelativePathname())
            ]
        );
    }
}
