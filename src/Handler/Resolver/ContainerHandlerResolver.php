<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Handler\Resolver;

use mohmann\Hexagonal\Command\Inflector\CommandInflector;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\InvalidHandlerClassException;
use mohmann\Hexagonal\Exception\MissingCommandHandlerException;
use mohmann\Hexagonal\Handler\HandlerResolverInterface;
use mohmann\Hexagonal\HandlerInterface;
use Psr\Container\ContainerInterface;

class ContainerHandlerResolver implements HandlerResolverInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var CommandInflector
     */
    private $commandInflector;

    /**
     * @param ContainerInterface $container
     * @param CommandInflector $commandInflector
     */
    public function __construct(ContainerInterface $container, CommandInflector $commandInflector)
    {
        $this->container = $container;
        $this->commandInflector = $commandInflector;
    }

    /**
     * {@inheritDoc}
     */
    public function resolveCommandHandler(CommandInterface $command): HandlerInterface
    {
        $commandClass = \get_class($command);
        $handlerClass = $this->commandInflector->getHandlerClass($commandClass);

        if (!$this->container->has($handlerClass)) {
            throw new MissingCommandHandlerException($command);
        }

        $handler = $this->container->get($handlerClass);

        if (!$handler instanceof HandlerInterface) {
            throw new InvalidHandlerClassException(\get_class($handler));
        }

        return $handler;
    }
}
