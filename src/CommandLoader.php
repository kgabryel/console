<?php

namespace Frankie\Console;

use danog\ClassFinder\ClassFinder;
use Ds\Map;
use Ds\Queue;
use Frankie\Config\NullConfigRepository;
use Frankie\DIContainer\DIContainer;
use RuntimeException;
use Symfony\Component\Console\Command\Command;

class CommandLoader
{
    private Queue $namespaces;
    private DIContainer $container;

    public function __construct(array $namespaces)
    {
        $this->namespaces = new Queue($namespaces);
        $this->container = new DIContainer(new Map(), new NullConfigRepository());
    }

    public function load(Application $application)
    {
        while (!$this->namespaces->isEmpty()) {
            $namespace = $this->namespaces->pop();
            $classes = ClassFinder::getClassesInNamespace(
                $namespace,
                ClassFinder::RECURSIVE_MODE
            );
            foreach ($classes as $class) {
                $this->container->setNewObject($class);
                $command = $this->container->get($class);
                if (!($command instanceof Command)) {
                    throw new RuntimeException();
                }
                $application->add($command, false);
            }
        }
    }
}