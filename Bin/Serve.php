<?php

namespace Jekxyl\Bin;

use Hoa\Console;
use Hoa\Event;

class Serve extends Console\Dispatcher\Kit
{
    protected $options = [
        ['root',    Console\GetOption::REQUIRED_ARGUMENT, 'r'],
        ['address', Console\GetOption::REQUIRED_ARGUMENT, 'a'],
        ['help',    Console\GetOption::NO_ARGUMENT,       'h'],
        ['help',    Console\GetOption::NO_ARGUMENT,       '?']
    ];



    public function main()
    {
        $root    = '.';
        $address = '127.0.0.1:8888';

        while (false !== $c = $this->getOption($v)) {
            switch ($c) {
                case 'r':
                    $root = $v;

                    break;

                case 'a':
                    $address = $v;

                    break;

                case 'h':
                case '?':
                    return $this->usage();

                    break;

                case '__ambiguous':
                    $this->resolveOptionAmbiguity($v);

                    break;
            }
        }

        if (false === is_dir($root)) {
            throw new Console\Exception(
                'Root %s is not a directory.',
                0,
                $root
            );
        }

        $processus = new Console\Processus(
            PHP_BINARY,
            [
                '-S' => $address,
                '-t' => $root
            ]
        );
        $processus->on(
            'input',
            function () {
                return false;
            }
        );
        $processus->on(
            'output',
            function (Event\Bucket $bucket) {
                echo $bucket->getData()['line'], "\n";
            }
        );

        echo 'Server is listening ', $root, ' on ', $address, '.', "\n\n";

        $processus->run();

        return;
    }

    public function usage()
    {
        echo
            'Usage   : serve <options>', "\n",
            'Options :', "\n",
            $this->makeUsageOptionsList([
                'r'    => 'Root of the Web application (default: `.`).',
                'a'    => 'Address to bind (default: `127.0.0.1:8888`).',
                'help' => 'This help.'
            ]);

        return;
    }
}
