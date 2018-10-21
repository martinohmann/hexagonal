<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Handler\Resolver;

use mohmann\Hexagonal\Command\CommandInflector;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\InvalidHandlerClassException;
use mohmann\Hexagonal\Exception\MissingCommandHandlerException;
use mohmann\Hexagonal\Handler\Resolver\ContainerHandlerResolver;
use mohmann\Hexagonal\HandlerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerHandlerResolverTest extends TestCase
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
     * @var ContainerHandlerResolver
     */
    private $resolver;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->container = \Phake::mock(ContainerInterface::class);
        $this->commandInflector = \Phake::mock(CommandInflector::class);
        $this->resolver = new ContainerHandlerResolver($this->container, $this->commandInflector);
    }

    /**
     * @test
     */
    public function itResolvesCommandHandler()
    {
        $command = \Phake::mock(CommandInterface::class);
        $handler = \Phake::mock(HandlerInterface::class);
        $commandClass = \get_class($command);
        $handlerClass = \get_class($handler);

        \Phake::when($this->commandInflector)
            ->getHandlerClass($commandClass)
            ->thenReturn($handlerClass);

        \Phake::when($this->container)
            ->has($handlerClass)
            ->thenReturn(true);

        \Phake::when($this->container)
            ->get($handlerClass)
            ->thenReturn($handler);

        $result = $this->resolver->resolveCommandHandler($command);

        $this->assertSame($handler, $result);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfHandlerClassIsNotPresentInContainer()
    {
        $command = \Phake::mock(CommandInterface::class);
        $handler = \Phake::mock(HandlerInterface::class);
        $commandClass = \get_class($command);
        $handlerClass = \get_class($handler);

        \Phake::when($this->commandInflector)
            ->getHandlerClass($commandClass)
            ->thenReturn($handlerClass);

        \Phake::when($this->container)
            ->has($handlerClass)
            ->thenReturn(false);

        $this->expectException(MissingCommandHandlerException::class);
        $this->resolver->resolveCommandHandler($command);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfResolvedHandlerIsOfWrongType()
    {
        $command = \Phake::mock(CommandInterface::class);
        $handler = \Phake::mock(HandlerInterface::class);
        $commandClass = \get_class($command);
        $handlerClass = \get_class($handler);

        \Phake::when($this->commandInflector)
            ->getHandlerClass($commandClass)
            ->thenReturn($handlerClass);

        \Phake::when($this->container)
            ->has($handlerClass)
            ->thenReturn(true);

        \Phake::when($this->container)
            ->get($handlerClass)
            ->thenReturn(new \stdClass);

        $this->expectException(InvalidHandlerClassException::class);
        $this->resolver->resolveCommandHandler($command);
    }
}
