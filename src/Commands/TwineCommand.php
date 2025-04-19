<?php

namespace JeroenGerits\Twine\Commands;

use Illuminate\Console\Command;

class TwineCommand extends Command
{
    public $signature = 'twine';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
