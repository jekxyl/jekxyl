<?php

namespace Jekxyl;

class Page extends Document
{
    public function getUrl()
    {
        return $this->_router->unroute(
            'page',
            [
                'pathname' => preg_replace('/\.xyl$/', '.html', $this->_file->getRelativePathname())
            ]
        );
    }
}
