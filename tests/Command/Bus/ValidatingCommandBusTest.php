<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Command\Bus;

use mohmann\Hexagonal\Command\CommandBusInterface;
use mohmann\Hexagonal\Command\Bus\ValidatingCommandBus;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\CommandValidationException;
use mohmann\Hexagonal\Validator\ValidatorResolverInterface;
use mohmann\Hexagonal\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class ValidatingCommandBusTest extends TestCase
{
    /**
     * @var CommandBusInterface
     */
    private $wrappedCommandBus;

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
        $this->wrappedCommandBus = \Phake::mock(CommandBusInterface::class);
        $this->validatorResolver = \Phake::mock(ValidatorResolverInterface::class);
        $this->commandBus = new ValidatingCommandBus($this->wrappedCommandBus, $this->validatorResolver);
    }

    /**
     * @test
     */
    public function itValidatesCommand()
    {
        $validator = \Phake::mock(ValidatorInterface::class);
        $command = \Phake::mock(CommandInterface::class);

        \Phake::when($this->validatorResolver)
            ->resolveCommandValidator($command)
            ->thenReturn($validator);

        $this->commandBus->execute($command);

        \Phake::verify($validator)
            ->validate($command);

        \Phake::verify($this->wrappedCommandBus)
            ->execute($command);
    }

    /**
     * @test
     */
    public function itDoesNotExecuteCommandBusWhenValidatorThrows()
    {
        $validator = \Phake::mock(ValidatorInterface::class);
        $command = \Phake::mock(CommandInterface::class);

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
            \Phake::verify($this->wrappedCommandBus, \Phake::never())
                ->execute($command);
        }
    }
}
