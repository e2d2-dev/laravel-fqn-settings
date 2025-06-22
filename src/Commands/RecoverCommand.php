<?php

namespace Betta\Settings\Commands;

use Betta\Settings\Models\FqnSetting;
use Betta\Settings\Settings;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'settings:recover')]

class RecoverCommand extends Command
{
    protected $description = 'Recovers settings to database';

    protected $count;

    public function handle()
    {
        $this->before();

        Settings::recover();

        $this->info($this->getMessage());

        return $this->getMessage();
    }

    public function before(): void
    {
        $this->count = FqnSetting::count();
    }

    public function getMessage(): string
    {
        $recoveredCount = FqnSetting::count() - $this->count;

        return match ($recoveredCount) {
            0 => 'No new settings recovered',
            default => "Recovered {$recoveredCount} new settings...",
        };
    }
}
