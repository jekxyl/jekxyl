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
        '/P/(?<url>[\w\d\-_]+)'
    )

    ->_get(
        '_resource',
        '/(?<resource>)'
    );

return $router;
