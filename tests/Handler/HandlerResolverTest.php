<?php
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Handler;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\HandlerNotFoundException;
use mohmann\Hexagonal\Handler\HandlerRegistry;
use mohmann\Hexagonal\Handler\HandlerResolver;
use mohmann\Hexagonal\HandlerInterface;
use PHPUnit\Framework\TestCase;

class HandlerResolverTest extends TestCase
{
    /**
     * @var HandlerRegistry
     */
    private $handlerRegistry;

    /**
     * @var HandlerResolver
     */
    private $handlerResolver;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->handlerRegistry = \Phake::mock(HandlerRegistry::class);
        $this->handlerResolver = new HandlerResolver($this->handlerRegistry);
    }

    /**
     * @test
     */
    public function itResolvesCommandHandler()
    {
        $handler = \Phake::mock(HandlerInterface::class);
        $command = \Phake::mock(CommandInterface::class);

        \Phake::when($handler)
            ->canHandle($command)
            ->thenReturn(true);

        \Phake::when($this->handlerRegistry)
            ->getHandlers()
            ->thenReturn([$handler]);

        $resolved = $this->handlerResolver->resolveCommandHandler($command);

        $this->assertSame($handler, $resolved);
    }

    /**
     * @test
     */
    public function itThrowsExceptionWhenItCannotResolveCommandHandler()
    {
        $command = \Phake::mock(CommandInterface::class);

        \Phake::when($this->handlerRegistry)
            ->getHandlers()
            ->thenReturn([]);

        $this->expectException(HandlerNotFoundException::class);
        $this->handlerResolver->resolveCommandHandler($command);
    }
}
