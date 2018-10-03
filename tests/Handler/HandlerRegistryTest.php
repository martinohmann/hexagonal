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
use mohmann\Hexagonal\Exception\CommandHandlerMissingException;
use mohmann\Hexagonal\Exception\InvalidHandlerClassException;
use mohmann\Hexagonal\Handler\HandlerRegistry;
use mohmann\Hexagonal\HandlerInterface;
use mohmann\Hexagonal\Tests\Command\Fixtures\Bar\BazCommand;
use mohmann\Hexagonal\Tests\Command\Fixtures\FooCommand;
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
    public function itRegistersCommandHandler()
    {
        $handler = \Phake::mock(HandlerInterface::class);
        $command = \Phake::mock(CommandInterface::class);

        $this->registry->registerCommandHandler(\get_class($command), $handler);

        $this->assertSame($handler, $this->registry->getCommandHandler($command));
    }

    /**
     * @test
     */
    public function itRegistersHandlersOnConstruct()
    {
        $input = [
            FooCommand::class => \Phake::mock(HandlerInterface::class),
            BazCommand::class => \Phake::mock(HandlerInterface::class),
        ];

        $registry = new HandlerRegistry($input);

        $handlers = $registry->getCommandHandlers();

        $this->assertSame($input, $handlers);
    }

    /**
     * @test
     */
    public function itRegistersHandlers()
    {
        $input = [
            FooCommand::class => \Phake::mock(HandlerInterface::class),
            BazCommand::class => \Phake::mock(HandlerInterface::class),
        ];

        $this->registry->registerCommandHandlers($input);

        $handlers = $this->registry->getCommandHandlers();

        $this->assertSame($input, $handlers);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfOnSuitableCommandHandlerIsAvailable()
    {
        $this->expectException(CommandHandlerMissingException::class);
        $this->registry->getCommandHandler(new FooCommand());
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfHandlersHaveInvalidType()
    {
        $handlers = [
            FooCommand::class => \Phake::mock(HandlerInterface::class),
            BazCommand::class => 'foo bar',
        ];

        $this->expectException(InvalidHandlerClassException::class);
        $this->registry->registerCommandHandlers($handlers);
    }
}
