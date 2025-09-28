<?php

namespace RohanAdhikari\FilamentNepaliDatetime\Commands;

use Illuminate\Console\Command;

class FilamentNepaliDatetimeCommand extends Command
{
    public $signature = 'filament-nepali-datetime';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
