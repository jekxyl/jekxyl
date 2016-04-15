<?php

use Hoa\Router;

$router = new Router\Http();
$router
    ->get(
        'home',
        '/'
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
