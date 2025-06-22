<?php

namespace Betta\Settings\Commands;

use Betta\Settings\Commands\Concerns\CanAskForComponentLocation;
use Betta\Settings\Commands\Concerns\CanManipulateFiles;
use Betta\Settings\Commands\Filegenerators\ValueClassGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

#[AsCommand(name: 'make:setting')]
class CreateCommand extends Command
{
    use CanAskForComponentLocation;
    use CanManipulateFiles;

    protected string $attributeFqn;

    protected string $fqnEnd;

    protected string $attributePath;

    protected $description = 'Creates setting class file';

    protected string $value;

    protected string $type;

    public function handle()
    {
        $this->configureName();

        $this->configureLocation();

        $this->configureValue();

        $this->writeFile($this->attributePath, app(ValueClassGenerator::class, [
            'fqn' => "App\\Settings\\{$this->name}",
            'value' => $this->value,
            'type' => $this->type,
        ]));
    }

    protected function getOptions(): array
    {
        return [
            new InputOption(
                name: 'name',
                mode: InputOption::VALUE_OPTIONAL,
            ),
            new InputOption(
                name: 'value',
                mode: InputOption::VALUE_OPTIONAL,
            ),
            new InputOption(
                name: 'type',
                mode: InputOption::VALUE_OPTIONAL,
            ),
        ];
    }

    protected function configureValue(): void
    {
        $this->value = $this->option('value') ?? text(
            label: 'What is the value?',
            placeholder: 'filament',
            required: true,
        );

        $this->type = (string) str($this->option('type') ?? select(
            label: 'What is the return type?',
            options: ['string' => 'string', 'int' => 'int', 'float' => 'float', 'bool' => 'bool'],
            default: 'string'
        ))
            ->snake();
    }

    protected function configureName(): void
    {
        $this->name = (string) str($this->option('name') ?? text(
            label: 'What is the setting name?',
            placeholder: 'name',
            required: true,
        ))
            ->studly();
    }

    protected function configureLocation(): void
    {
        [
            $namespace,
            $path,
        ] = $this->askForComponentLocation(
            path: 'Schemas',
            question: 'Where would you like to create the schema?',
        );

        $this->attributeFqn = "{$namespace}\\Settings\\{$this->name}";
        $this->attributePath = (string) str(app_path()."\\Settings\\{$this->name}.php")
            ->replace('\\', '/')
            ->replace('//', '/');
    }
}
