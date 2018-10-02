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
        $this->registerHandlers($handlers);
    }

    /**
     * @param HandlerInterface $handler
     * @return void
     */
    public function registerHandler(HandlerInterface $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @param HandlerInterface[] $handlers
     * @return void
     */
    public function registerHandlers(array $handlers)
    {
        foreach ($handlers as $handler) {
            if (!$handler instanceof HandlerInterface) {
                throw new \InvalidArgumentException(
                    \sprintf(
                        'Expected class of type "%s", got "%s"',
                        HandlerInterface::class,
                        \is_object($handler) ? \get_class($handler) : \gettype($handler)
                    )
                );
            }
        }

        $this->handlers = $handlers;
    }
}
