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

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Handler\HandlerRegistry;
use mohmann\Hexagonal\Handler\HandlerResolverInterface;
use mohmann\Hexagonal\HandlerInterface;

class RegistryResolver implements HandlerResolverInterface
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
     * {@inheritDoc}
     */
    public function resolveCommandHandler(CommandInterface $command): HandlerInterface
    {
        return $this->handlerRegistry->getCommandHandler($command);
    }
}
