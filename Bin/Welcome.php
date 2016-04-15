<?php

namespace Jekxyl\Bin;

use Hoa\Console;

class Welcome extends Console\Dispatcher\Kit
{
    protected $options = [
        ['help', Console\GetOption::NO_ARGUMENT, 'h'],
        ['help', Console\GetOption::NO_ARGUMENT, '?']
    ];



    public function main()
    {
        while (false !== $c = $this->getOption($v)) {
            switch ($c) {
                case 'h':
                case '?':
                    return $this->usage();

                    break;

                case '__ambiguous':
                    $this->resolveOptionAmbiguity($v);

                    break;
            }
        }

        echo
            'Jekxyl', "\n\n",
            'List of available commands:', "\n",
            '  * new    Create a new jekxyl project.', "\n";

        return;
    }

    public function usage()
    {
        echo
            'Usage   : welcome <options>', "\n",
            'Options :', "\n",
            $this->makeUsageOptionsList([
                'help' => 'This help.'
            ]);

        return;
    }
}
