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
use mohmann\Hexagonal\HandlerInterface;
use Psr\Log\LoggerInterface;

class LoggingHandlerResolver implements HandlerResolverInterface
{
    /**
     * @var HandlerResolverInterface
     */
    private $handlerResolver;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param HandlerResolverInterface $handlerResolver
     * @param LoggerInterface $logger
     */
    public function __construct(HandlerResolverInterface $handlerResolver, LoggerInterface $logger)
    {
        $this->handlerResolver = $handlerResolver;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function resolveCommandHandler(CommandInterface $command): HandlerInterface
    {
        $handler = $this->handlerResolver->resolveCommandHandler($command);

        $this->logger->debug(
            \sprintf(
                'Using handler "%s" for command "%s"',
                \get_class($handler),
                \get_class($command)
            )
        );

        return $handler;
    }
}
