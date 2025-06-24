<?php

namespace Betta\Settings\Commands;

use Betta\Settings\Models\FqnSetting;
use Betta\Settings\Settings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'settings:sync')]

class SyncCommand extends Command
{
    protected $description = 'Recovers settings to database';

    protected $count;

    public function handle()
    {
        if (! Schema::hasTable('fqn_settings')) {
            $this->fail('Settings Table does not exist');
        }
        $this->before();

        Settings::sync();

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
            0 => 'No new settings found',
            default => "Found {$recoveredCount} new settings...",
        };
    }
}
