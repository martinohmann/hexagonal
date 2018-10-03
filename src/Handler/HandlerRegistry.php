<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Handler;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\InvalidHandlerClassException;
use mohmann\Hexagonal\Exception\MissingCommandHandlerException;
use mohmann\Hexagonal\HandlerInterface;

class HandlerRegistry
{
    /**
     * @var HandlerInterface[]
     */
    private $handlers;

    /**
     * @param HandlerInterface[] $handlers
     */
    public function __construct(array $handlers = [])
    {
        $this->registerCommandHandlers($handlers);
    }

    /**
     * @param string $commandClass
     * @param HandlerInterface $handler
     * @return void
     */
    public function registerCommandHandler(string $commandClass, HandlerInterface $handler)
    {
        $this->handlers[$commandClass] = $handler;
    }

    /**
     * @param CommandInterface $command
     * @throws MissingCommandHandlerException
     * @return HandlerInterface
     */
    public function getCommandHandler(CommandInterface $command): HandlerInterface
    {
        $commandClass = \get_class($command);

        if (!isset($this->handlers[$commandClass])) {
            throw new MissingCommandHandlerException($command);
        }

        return $this->handlers[$commandClass];
    }

    /**
     * @return HandlerInterface[]
     */
    public function getCommandHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @param HandlerInterface[] $handlers
     * @return void
     */
    public function registerCommandHandlers(array $handlers)
    {
        foreach ($handlers as $handler) {
            if (!$handler instanceof HandlerInterface) {
                throw new InvalidHandlerClassException(
                    \is_object($handler) ? \get_class($handler) : \gettype($handler)
                );
            }
        }

        $this->handlers = $handlers;
    }
}
