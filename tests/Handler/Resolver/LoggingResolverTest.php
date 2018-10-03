<?php
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Handler\Resolver;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Handler\HandlerResolverInterface;
use mohmann\Hexagonal\Handler\Resolver\LoggingResolver;
use mohmann\Hexagonal\HandlerInterface;
use mohmann\Hexagonal\Tests\Command\Fixtures\FooCommand;
use mohmann\Hexagonal\Tests\Handler\Fixtures\FooHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class LoggingResolverTest extends TestCase
{
    /**
     * @var HandlerResolverInterface
     */
    private $decoratedResolver;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var LoggingResolver
     */
    private $loggingResolver;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->decoratedResolver = \Phake::mock(HandlerResolverInterface::class);
        $this->logger = \Phake::mock(LoggerInterface::class);
        $this->loggingResolver = new LoggingResolver($this->decoratedResolver, $this->logger);
    }

    /**
     * @test
     */
    public function itCallsDecoratedResolver()
    {
        $command = \Phake::mock(CommandInterface::class);
        $handler = \Phake::mock(HandlerInterface::class);

        \Phake::when($this->decoratedResolver)
            ->resolveCommandHandler($command)
            ->thenReturn($handler);

        $result = $this->loggingResolver->resolveCommandHandler($command);

        \Phake::verify($this->decoratedResolver)
            ->resolveCommandHandler($command);

        $this->assertSame($handler, $result);
    }

    /**
     * @test
     */
    public function itLogsResolvedHandlerAndCommandClass()
    {
        $command = new FooCommand();
        $handler = new FooHandler();

        \Phake::when($this->decoratedResolver)
            ->resolveCommandHandler($command)
            ->thenReturn($handler);

        $this->loggingResolver->resolveCommandHandler($command);

        \Phake::verify($this->logger)
            ->info(\Phake::capture($message));

        $this->assertSame(
            'Handling command "mohmann\Hexagonal\Tests\Command\Fixtures\FooCommand" ' .
            'with "mohmann\Hexagonal\Tests\Handler\Fixtures\FooHandler"',
            $message
        );
    }
}
