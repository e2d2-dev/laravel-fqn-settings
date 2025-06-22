<?php

namespace Betta\Settings\Commands;

use Betta\Settings\Commands\Concerns\CanOpenUrlInBrowser;
use Betta\Settings\Models\FqnSetting;
use Betta\Settings\Settings;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use function Laravel\Prompts\confirm;

#[AsCommand(name: 'settings:install')]

class InstallCommand extends Command
{
    use CanOpenUrlInBrowser;

    protected $description = 'Install fqn settings';


    public function handle(): void
    {
        $this->installUpgradeCommand();
        $this->askToStar();
    }

    protected function installUpgradeCommand(): void
    {
        $path = base_path('composer.json');

        if (! file_exists($path)) {
            return;
        }

        $configuration = json_decode(file_get_contents($path), associative: true);

        $command = '@php artisan settings:sync';

        if (in_array($command, $configuration['scripts']['post-autoload-dump'] ?? [])) {
            return;
        }

        $configuration['scripts']['post-autoload-dump'] ??= [];
        $configuration['scripts']['post-autoload-dump'][] = $command;

        file_put_contents(
            $path,
            (string) str(json_encode($configuration, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
                ->append(PHP_EOL)
                ->replace(
                    search: "    \"keywords\": [\n        \"laravel\",\n        \"framework\"\n    ],",
                    replace: '    "keywords": ["laravel", "framework"],',
                )
                ->replace(
                    search: "    \"keywords\": [\n        \"framework\",\n        \"laravel\"\n    ],",
                    replace: '    "keywords": ["framework", "laravel"],',
                ),
        );
    }

    protected function askToStar(): void
    {
        if ($this->option('no-interaction')) {
            return;
        }

        if (! confirm(
            label: 'All done! Would you like to show some love by starring the repo on GitHub?',
            default: true,
        )) {
            return;
        }

        $this->openUrlInBrowser('https://github.com/e2d2-dev/laravel-fqn-settings/');
    }
}
