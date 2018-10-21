<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Command\Bus;

use mohmann\Hexagonal\Command\Bus\LoggingCommandBus;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Handler\HandlerResolverInterface;
use mohmann\Hexagonal\HandlerInterface;
use mohmann\Hexagonal\Tests\Command\Fixtures\FooCommand;
use mohmann\Hexagonal\Tests\Handler\Fixtures\FooHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class LoggingCommandBusTest extends TestCase
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
     * @var LoggingCommandBus
     */
    private $commandBus;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->handlerResolver = \Phake::mock(HandlerResolverInterface::class);
        $this->logger = \Phake::mock(LoggerInterface::class);
        $this->commandBus = new LoggingCommandBus($this->handlerResolver, $this->logger);
    }

    /**
     * @test
     */
    public function itExecutesCommandUsingHandler()
    {
        $command = \Phake::mock(CommandInterface::class);
        $handler = \Phake::mock(HandlerInterface::class);

        \Phake::when($this->handlerResolver)
            ->resolveCommandHandler($command)
            ->thenReturn($handler);

        $this->commandBus->execute($command);

        \Phake::verify($handler)
            ->handle($command);
    }

    /**
     * @test
     */
    public function itReturnsHandlerResult()
    {
        $handler = \Phake::mock(HandlerInterface::class);
        $command = \Phake::mock(CommandInterface::class);

        \Phake::when($this->handlerResolver)
            ->resolveCommandHandler($command)
            ->thenReturn($handler);

        \Phake::when($handler)
            ->handle($command)
            ->thenReturn(['foo' => 'bar']);

        $result = $this->commandBus->execute($command);

        $this->assertSame(['foo' => 'bar'], $result);
    }

    /**
     * @test
     */
    public function itLogsResolvedHandlerAndCommandClass()
    {
        $command = new FooCommand();
        $handler = new FooHandler();

        $command->setContext(['some' => 'context']);

        \Phake::when($this->handlerResolver)
            ->resolveCommandHandler($command)
            ->thenReturn($handler);

        $this->commandBus->execute($command);

        \Phake::verify($this->logger)
            ->info(\Phake::capture($message), \Phake::capture($context));

        $this->assertSame(
            'Handling command "mohmann\Hexagonal\Tests\Command\Fixtures\FooCommand" ' .
            'with "mohmann\Hexagonal\Tests\Handler\Fixtures\FooHandler"',
            $message
        );

        $this->assertSame(['command_context' => ['some' => 'context']], $context);
    }
}
