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

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\InvalidHandlerClassException;
use mohmann\Hexagonal\Exception\MissingCommandHandlerException;
use mohmann\Hexagonal\Handler\Resolver\HandlerResolver;
use mohmann\Hexagonal\HandlerInterface;
use PHPUnit\Framework\TestCase;

class HandlerResolverTest extends TestCase
{
    /**
     * @var HandlerResolver
     */
    private $resolver;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->resolver = new HandlerResolver();
    }

    /**
     * @test
     */
    public function itRegistersCommandHandler()
    {
        $handler = \Phake::mock(HandlerInterface::class);
        $command = \Phake::mock(CommandInterface::class);

        $this->resolver->registerCommandHandler(\get_class($command), $handler);

        $this->assertSame($handler, $this->resolver->resolveCommandHandler($command));
    }

    /**
     * @test
     */
    public function itRegistersHandlersOnConstruct()
    {
        $input = [
            'Some\Command' => \Phake::mock(HandlerInterface::class),
            'Some\Other\Command' => \Phake::mock(HandlerInterface::class),
        ];

        $registry = new HandlerResolver($input);

        $handlers = $registry->getCommandHandlers();

        $this->assertSame($input, $handlers);
    }

    /**
     * @test
     */
    public function itRegistersHandlers()
    {
        $input = [
            'Some\Command' => \Phake::mock(HandlerInterface::class),
            'Some\Other\Command' => \Phake::mock(HandlerInterface::class),
        ];

        $this->resolver->registerCommandHandlers($input);

        $handlers = $this->resolver->getCommandHandlers();

        $this->assertSame($input, $handlers);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfOnSuitableCommandHandlerIsAvailable()
    {
        $command = \Phake::mock(CommandInterface::class);

        $this->expectException(MissingCommandHandlerException::class);
        $this->resolver->resolveCommandHandler($command);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfHandlersHaveInvalidType()
    {
        $handlers = [
            'Some\Command' => \Phake::mock(HandlerInterface::class),
            'Some\Other\Command' => 'foo bar',
        ];

        $this->expectException(InvalidHandlerClassException::class);
        $this->resolver->registerCommandHandlers($handlers);
    }
}
