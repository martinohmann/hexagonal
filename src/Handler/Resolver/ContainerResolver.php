<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Handler\Resolver;

use mohmann\Hexagonal\Command\CommandInflector;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\CommandHandlerMissingException;
use mohmann\Hexagonal\Exception\InvalidHandlerClassException;
use mohmann\Hexagonal\Handler\HandlerResolverInterface;
use mohmann\Hexagonal\HandlerInterface;
use Psr\Container\ContainerInterface;

class ContainerResolver implements HandlerResolverInterface
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
        $handlerClass = $this->commandInflector->getHandlerClass($command);

        if (!$this->container->has($handlerClass)) {
            throw new CommandHandlerMissingException($command);
        }

        $handler = $this->container->get($handlerClass);

        if (!$handler instanceof HandlerInterface) {
            throw new InvalidHandlerClassException(\get_class($handler));
        }

        return $handler;
    }
}
