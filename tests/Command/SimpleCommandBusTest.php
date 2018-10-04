<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Command;

use mohmann\Hexagonal\Command\SimpleCommandBus;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Handler\HandlerResolverInterface;
use mohmann\Hexagonal\HandlerInterface;
use PHPUnit\Framework\TestCase;

class SimpleCommandBusTest extends TestCase
{
    /**
     * @var HandlerResolverInterface
     */
    private $handlerResolver;

    /**
     * @var SimpleCommandBus
     */
    private $commandBus;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->handlerResolver = \Phake::mock(HandlerResolverInterface::class);
        $this->commandBus = new SimpleCommandBus($this->handlerResolver);
    }

    /**
     * @test
     */
    public function itExecutesCommandUsingHandler()
    {
        $handler = \Phake::mock(HandlerInterface::class);
        $command = \Phake::mock(CommandInterface::class);

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
}
