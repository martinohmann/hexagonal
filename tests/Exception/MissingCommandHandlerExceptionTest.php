<?php
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Exception;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\MissingCommandHandlerException;
use mohmann\Hexagonal\Tests\Command\Fixtures\FooCommand;
use PHPUnit\Framework\TestCase;

class MissingCommandHandlerExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function itHasMessage()
    {
        $command = new FooCommand();
        $exception = new MissingCommandHandlerException($command);

        $this->assertSame(
            'The command "mohmann\Hexagonal\Tests\Command\Fixtures\FooCommand" ' .
            'with context "[]" could not be handled because there is no suitable handler for it',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function itIncludesCommandContextInMessage()
    {
        $command = new FooCommand();
        $command->setContext(['foo' => ['bar' => 'baz']]);
        $exception = new MissingCommandHandlerException($command);

        $this->assertSame(
            'The command "mohmann\Hexagonal\Tests\Command\Fixtures\FooCommand" ' .
            'with context "{"foo":{"bar":"baz"}}" could not be handled because there is no suitable handler for it',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function itWrapsCommand()
    {
        $command = \Phake::mock(CommandInterface::class);

        $exception = new MissingCommandHandlerException($command);

        $this->assertSame($command, $exception->getCommand());
    }
}
