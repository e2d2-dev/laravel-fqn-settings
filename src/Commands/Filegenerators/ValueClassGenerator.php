<?php

namespace Betta\Settings\Commands\Filegenerators;

use Filament\Support\Commands\FileGenerators\ClassGenerator;
use Nette\PhpGenerator\ClassType;

class ValueClassGenerator extends ClassGenerator
{
    final public function __construct(
        protected string $fqn,
        protected mixed $value,
        protected string $type,
    ) {}

    public function getNamespace(): string
    {
        return $this->extractNamespace($this->getFqn());
    }

    public function getBasename(): string
    {
        return class_basename($this->getFqn());
    }

    public function getImports(): array
    {
        $extends = $this->getExtends();
        $extendsBasename = class_basename($extends);

        return [
            ...(($extendsBasename === class_basename($this->getFqn())) ? [$extends => "Base{$extendsBasename}"] : [$extends]),
        ];
    }

    public function getExtends(): ?string
    {
        return 'Betta\Settings\SettingAttribute';
    }

    protected function addPropertiesToClass(ClassType $class): void
    {
        $this->addValuePropertyToClass($class);
    }

    protected function addValuePropertyToClass(ClassType $class): void
    {
        $property = $class->addProperty('value')
            ->setProtected()
            ->setType($this->type)
            ->setValue($this->castValue())
            ->setNullable();
    }

    protected function castValue(): mixed
    {
        return match ($this->type) {
            'bool' => (bool) $this->value,
            'int' => (int) $this->value,
            'float' => (float) $this->value,
            default => $this->value,
        };
    }

    public function getFqn(): string
    {
        return $this->fqn;
    }
}
