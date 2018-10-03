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

use mohmann\Hexagonal\Command\CommandInflector;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\CommandHandlerMissingException;
use mohmann\Hexagonal\Exception\InvalidHandlerClassException;
use mohmann\Hexagonal\Handler\Resolver\ContainerResolver;
use mohmann\Hexagonal\HandlerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerResolverTest extends TestCase
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
     * @var ContainerResolver
     */
    private $containerResolver;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->container = \Phake::mock(ContainerInterface::class);
        $this->commandInflector = \Phake::mock(CommandInflector::class);
        $this->containerResolver = new ContainerResolver($this->container, $this->commandInflector);
    }

    /**
     * @test
     */
    public function itResolvesCommandHandler()
    {
        $command = \Phake::mock(CommandInterface::class);
        $handler = \Phake::mock(HandlerInterface::class);
        $handlerClass = \get_class($handler);

        \Phake::when($this->commandInflector)
            ->getHandlerClass($command)
            ->thenReturn($handlerClass);

        \Phake::when($this->container)
            ->has($handlerClass)
            ->thenReturn(true);

        \Phake::when($this->container)
            ->get($handlerClass)
            ->thenReturn($handler);

        $result = $this->containerResolver->resolveCommandHandler($command);

        $this->assertSame($handler, $result);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfHandlerClassIsNotPresentInContainer()
    {
        $command = \Phake::mock(CommandInterface::class);
        $handler = \Phake::mock(HandlerInterface::class);
        $handlerClass = \get_class($handler);

        \Phake::when($this->commandInflector)
            ->getHandlerClass($command)
            ->thenReturn($handlerClass);

        \Phake::when($this->container)
            ->has($handlerClass)
            ->thenReturn(false);

        $this->expectException(CommandHandlerMissingException::class);
        $this->containerResolver->resolveCommandHandler($command);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfResolvedHandlerIsOfWrongType()
    {
        $command = \Phake::mock(CommandInterface::class);
        $handler = \Phake::mock(HandlerInterface::class);
        $handlerClass = \get_class($handler);

        \Phake::when($this->commandInflector)
            ->getHandlerClass($command)
            ->thenReturn($handlerClass);

        \Phake::when($this->container)
            ->has($handlerClass)
            ->thenReturn(true);

        \Phake::when($this->container)
            ->get($handlerClass)
            ->thenReturn(new \stdClass);

        $this->expectException(InvalidHandlerClassException::class);
        $this->containerResolver->resolveCommandHandler($command);
    }
}
