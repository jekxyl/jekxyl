<?php

use Hoa\Console;
use Hoa\Dispatcher;
use Hoa\Exception;
use Hoa\Router;

Exception\Error::enableErrorHandler();
Exception::enableUncaughtHandler();

try {
    $router = new Router\Cli();
    $router->get(
        'g',
        '(?<command>\w+)?(?<_tail>.*?)',
        'Main',
        'Main',
        ['command' => 'welcome']
    );

    $dispatcher = new Dispatcher\ClassMethod([
        'synchronous.call'
            => 'Jekxyl\Bin\(:%variables.command:ls/new/_New/U:)',
        'synchronous.able'
            => 'main'
    ]);
    $dispatcher->setKitName('Hoa\Console\Dispatcher\Kit');
    exit($dispatcher->dispatch($router));
} catch (Core\Exception $e) {
    $message = $e->raise(true);
    $code    = 1;
} catch (\Exception $e) {
    $message = $e->getMessage();
    $code    = 2;
}

Console\Cursor::colorize('foreground(white) background(red)');
echo $message, "\n";
Console\Cursor::colorize('normal');
exit($code);
