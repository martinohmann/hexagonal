<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Command;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Handler\HandlerResolverInterface;

class SimpleCommandBus implements CommandBusInterface
{
    /**
     * @var HandlerResolverInterface
     */
    private $handlerResolver;

    /**
     * @param HandlerResolverInterface $handlerResolver
     */
    public function __construct(HandlerResolverInterface $handlerResolver)
    {
        $this->handlerResolver = $handlerResolver;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(CommandInterface $command)
    {
        $handler = $this->handlerResolver->resolveCommandHandler($command);

        return $handler->handle($command);
    }
}
