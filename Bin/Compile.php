<?php

namespace Jekxyl\Bin;

use Hoa\Console;
use Hoa\Protocol;
use Jekxyl\Compiler;

class Compile extends Console\Dispatcher\Kit
{
    protected $options = [
        ['source',      Console\GetOption::REQUIRED_ARGUMENT, 's'],
        ['destination', Console\GetOption::REQUIRED_ARGUMENT, 'd'],
        ['help',        Console\GetOption::NO_ARGUMENT,       'h'],
        ['help',        Console\GetOption::NO_ARGUMENT,       '?']
    ];



    public function main()
    {
        $source      = null;
        $destination = null;

        while (false !== $c = $this->getOption($v)) {
            switch ($c) {
                case 's':
                    $source = $v;

                    break;

                case 'd':
                    $destination = $v;

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

        if (false === is_dir($source)) {
            throw new Console\Exception(
                'Source %s is not a directory.',
                0,
                $source
            );
        }

        if (false === is_dir($destination)) {
            throw new Console\Exception(
                'Destination %s is not a directory.',
                0,
                $destination
            );
        }

        $protocol             = Protocol::getInstance();
        $protocol['Jekxyl'][] = new Protocol\Node('Source', "\r" . $source . DS);
        $protocol['Jekxyl'][] = new Protocol\Node('Dist', "\r" . $destination . DS);
        $protocol['Application']->setReach($source . DS);

        $compiler = new Compiler();

        echo 'Reset the destination… ';
        $compiler->reset();
        echo 'OK', "\n";

        echo 'Building posts… ';
        $compiler->buildPosts();
        echo 'OK', "\n";

        echo 'Building index… ';
        $compiler->buildIndex();
        echo 'OK', "\n";

        echo 'Building assets… ';
        $compiler->buildAssets();
        echo 'OK', "\n";

        return;
    }

    public function usage()
    {
        echo
            'Usage   : compile <options> <project_directory>', "\n",
            'Options :', "\n",
            $this->makeUsageOptionsList([
                's'    => 'Source of the project.',
                'd'    => 'Destination of the compilation.',
                'help' => 'This help.'
            ]);

        return;
    }
}
