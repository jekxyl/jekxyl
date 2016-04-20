<?php

namespace Jekxyl\Bin;

use Hoa\Console;
use Hoa\File;

class _New extends Console\Dispatcher\Kit
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

        $this->parser->listInputs($projectDirectory);

        if (empty($projectDirectory)) {
            throw new Console\Exception(
                'The project directory must not be empty or null.',
                0
            );
        }

        if (true === file_exists($projectDirectory)) {
            throw new Console\Exception(
                'The project directory %s already exists, cannot create it.',
                1,
                $projectDirectory
            );
        }

        $skeletonDirectory = 'hoa://Jekxyl/Resource/Skeleton';

        File\Directory::create($projectDirectory);
        (new File\Directory($skeletonDirectory))->copy($projectDirectory);

        return;
    }

    public function usage()
    {
        echo
            'Usage   : new <options> <project_directory>', "\n",
            'Options :', "\n",
            $this->makeUsageOptionsList([
                'help' => 'This help.'
            ]);

        return;
    }
}
