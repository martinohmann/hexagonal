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

use mohmann\Hexagonal\Handler\HandlerRegistry;
use mohmann\Hexagonal\HandlerInterface;
use PHPUnit\Framework\TestCase;

class HandlerRegistryTest extends TestCase
{
    /**
     * @var HandlerRegistry
     */
    private $registry;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->registry = new HandlerRegistry();
    }

    /**
     * @test
     */
    public function itRegistersHandler()
    {
        $handler = \Phake::mock(HandlerInterface::class);

        $this->registry->registerHandler($handler);

        $handlers = $this->registry->getHandlers();

        $this->assertCount(1, $handlers);
        $this->assertSame($handler, $handlers[0]);
    }

    /**
     * @test
     */
    public function itRegistersHandlers()
    {
        $input = [
            \Phake::mock(HandlerInterface::class),
            \Phake::mock(HandlerInterface::class),
        ];

        $this->registry->registerHandlers($input);

        $handlers = $this->registry->getHandlers();

        $this->assertSame($input, $handlers);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfHandlersHaveInvalidType()
    {
        $handlers = [
            \Phake::mock(HandlerInterface::class),
            'foo bar',
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->registry->registerHandlers($handlers);
    }
}
