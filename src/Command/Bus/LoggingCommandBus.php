<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Command\Bus;

use mohmann\Hexagonal\Command\CommandBusInterface;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Handler\HandlerResolverInterface;
use Psr\Log\LoggerInterface;

class LoggingCommandBus implements CommandBusInterface
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
    public function execute(CommandInterface $command)
    {
        $handler = $this->handlerResolver->resolveCommandHandler($command);

        $this->logger->info(
            \sprintf(
                'Handling command "%s" with "%s"',
                \get_class($command),
                \get_class($handler)
            ),
            ['command_context' => $command->getContext()]
        );

        return $handler->handle($command);
    }
}
