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

use mohmann\Hexagonal\Command\ValidatingCommandBus;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\CommandValidationException;
use mohmann\Hexagonal\Handler\HandlerResolverInterface;
use mohmann\Hexagonal\HandlerInterface;
use mohmann\Hexagonal\Validator\ValidatorResolverInterface;
use mohmann\Hexagonal\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class ValidatoringCommandBusTest extends TestCase
{
    /**
     * @var HandlerResolverInterface
     */
    private $handlerResolver;

    /**
     * @var ValidatorResolverInterface
     */
    private $validatorResolver;

    /**
     * @var ValidatingCommandBus
     */
    private $commandBus;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->handlerResolver = \Phake::mock(HandlerResolverInterface::class);
        $this->validatorResolver = \Phake::mock(ValidatorResolverInterface::class);
        $this->commandBus = new ValidatingCommandBus($this->handlerResolver, $this->validatorResolver);
    }

    /**
     * @test
     */
    public function itValidatesCommand()
    {
        $handler = \Phake::mock(HandlerInterface::class);
        $validator = \Phake::mock(ValidatorInterface::class);
        $command = \Phake::mock(CommandInterface::class);

        \Phake::when($this->handlerResolver)
            ->resolveCommandHandler($command)
            ->thenReturn($handler);

        \Phake::when($this->validatorResolver)
            ->resolveCommandValidator($command)
            ->thenReturn($validator);

        $this->commandBus->execute($command);

        \Phake::verify($validator)
            ->validate($command);

        \Phake::verify($handler)
            ->handle($command);
    }

    /**
     * @test
     */
    public function itReturnsHandlerResult()
    {
        $handler = \Phake::mock(HandlerInterface::class);
        $validator = \Phake::mock(ValidatorInterface::class);
        $command = \Phake::mock(CommandInterface::class);

        \Phake::when($this->handlerResolver)
            ->resolveCommandHandler($command)
            ->thenReturn($handler);

        \Phake::when($this->validatorResolver)
            ->resolveCommandValidator($command)
            ->thenReturn($validator);

        \Phake::when($handler)
            ->handle($command)
            ->thenReturn(['foo' => 'bar']);

        $result = $this->commandBus->execute($command);

        $this->assertSame(['foo' => 'bar'], $result);
    }

    /**
     * @test
     */
    public function itDoesNotExecuteHandlerWhenValidatorThrows()
    {
        $handler = \Phake::mock(HandlerInterface::class);
        $validator = \Phake::mock(ValidatorInterface::class);
        $command = \Phake::mock(CommandInterface::class);

        \Phake::when($this->handlerResolver)
            ->resolveCommandHandler($command)
            ->thenReturn($handler);

        \Phake::when($this->validatorResolver)
            ->resolveCommandValidator($command)
            ->thenReturn($validator);

        \Phake::when($validator)
            ->validate($command)
            ->thenThrow(new CommandValidationException);

        try {
            $this->commandBus->execute($command);
            $this->fail(
                \sprintf(
                    'Should have thrown "%s", but did not',
                    CommandValidationException::class
                )
            );
        } catch (CommandValidationException $e) {
            \Phake::verify($handler, \Phake::never())
                ->handle($command);
        }
    }
}
