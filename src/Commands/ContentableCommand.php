<?php

namespace Plank\Contentable\Commands;

use Illuminate\Console\Command;

class ContentableCommand extends Command
{
    public $signature = 'contentable';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
