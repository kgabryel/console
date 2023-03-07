<?php

namespace Frankie\Console;

use Symfony\Bundle\FrameworkBundle\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\KernelInterface;

class Application extends SymfonyApplication
{
    private string $namespace;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->namespace = 'Frankie\Console\Command';
    }

    public function add(Command $command, bool $filter = true): ?Command
    {
        $name = $command->getName();
        if ($filter && !self::checkName($command, $name)) {
            return null;
        }

        return parent::add($command);
    }

    private static function checkName(Command $command, string $name): bool
    {
        if (str_starts_with($name, 'make:')) {
            $availableMake = [
                'make:migration',
                'make:command',
                'make:entity',
            ];
            if (!in_array($command->getName(), $availableMake, true)) {
                return false;
            }
        }
        $toRemove = [
            'secrets:',
            'router:',
            'debug:',
            'assets:',
            'config:',
            'cache:',
            'lint:'
        ];
        foreach ($toRemove as $value) {
            if (str_starts_with($name, $value)) {
                return false;
            }
        }

        return true;
    }

    public function loadCommands(array $namespaces = []): self
    {
        $namespaces = array_merge([$this->namespace], $namespaces);
        $commandLoader = new CommandLoader($namespaces);
        $commandLoader->load($this);

        return $this;
    }

}