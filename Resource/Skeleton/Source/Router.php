<?php

use Hoa\Router;

$router = new Router\Http();
$router
    ->get(
        'home',
        '/'
    )
    ->get(
        'page',
        '/(?<pathname>[\w\d\-_]+)'
    )
    ->get(
        'post',
        '/p/(?<pathname>[\w\d\-_]+)'
    )
    ->_get(
        '_resource',
        '/(?<resource>)'
    );

return $router;
