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
use mohmann\Hexagonal\Exception\HandlerNotFoundException;
use mohmann\Hexagonal\HandlerInterface;

class HandlerResolver implements HandlerResolverInterface
{
    /**
     * @var HandlerRegistry
     */
    private $handlerRegistry;

    /**
     * @param HandlerRegistry $handlerRegistry
     */
    public function __construct(HandlerRegistry $handlerRegistry)
    {
        $this->handlerRegistry = $handlerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveCommandHandler(CommandInterface $command): HandlerInterface
    {
        foreach ($this->handlerRegistry->getHandlers() as $handler) {
            if ($handler->canHandle($command)) {
                return $handler;
            }
        }

        throw new HandlerNotFoundException($command);
    }
}
