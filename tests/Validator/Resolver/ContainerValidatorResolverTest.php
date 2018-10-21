<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Validator\Resolver;

use mohmann\Hexagonal\Command\CommandInflector;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\InvalidValidatorClassException;
use mohmann\Hexagonal\Exception\MissingCommandValidatorException;
use mohmann\Hexagonal\Validator\Resolver\ContainerValidatorResolver;
use mohmann\Hexagonal\ValidatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerValidatorResolverTest extends TestCase
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
     * @var ContainerValidatorResolver
     */
    private $resolver;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->container = \Phake::mock(ContainerInterface::class);
        $this->commandInflector = \Phake::mock(CommandInflector::class);
        $this->resolver = new ContainerValidatorResolver($this->container, $this->commandInflector);
    }

    /**
     * @test
     */
    public function itResolvesCommandValidator()
    {
        $command = \Phake::mock(CommandInterface::class);
        $validator = \Phake::mock(ValidatorInterface::class);
        $commandClass = \get_class($command);
        $validatorClass = \get_class($validator);

        \Phake::when($this->commandInflector)
            ->getValidatorClass($commandClass)
            ->thenReturn($validatorClass);

        \Phake::when($this->container)
            ->has($validatorClass)
            ->thenReturn(true);

        \Phake::when($this->container)
            ->get($validatorClass)
            ->thenReturn($validator);

        $result = $this->resolver->resolveCommandValidator($command);

        $this->assertSame($validator, $result);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfValidatorClassIsNotPresentInContainer()
    {
        $command = \Phake::mock(CommandInterface::class);
        $validator = \Phake::mock(ValidatorInterface::class);
        $commandClass = \get_class($command);
        $validatorClass = \get_class($validator);

        \Phake::when($this->commandInflector)
            ->getValidatorClass($commandClass)
            ->thenReturn($validatorClass);

        \Phake::when($this->container)
            ->has($validatorClass)
            ->thenReturn(false);

        $this->expectException(MissingCommandValidatorException::class);
        $this->resolver->resolveCommandValidator($command);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfResolvedValidatorIsOfWrongType()
    {
        $command = \Phake::mock(CommandInterface::class);
        $validator = \Phake::mock(ValidatorInterface::class);
        $commandClass = \get_class($command);
        $validatorClass = \get_class($validator);

        \Phake::when($this->commandInflector)
            ->getValidatorClass($commandClass)
            ->thenReturn($validatorClass);

        \Phake::when($this->container)
            ->has($validatorClass)
            ->thenReturn(true);

        \Phake::when($this->container)
            ->get($validatorClass)
            ->thenReturn(new \stdClass);

        $this->expectException(InvalidValidatorClassException::class);
        $this->resolver->resolveCommandValidator($command);
    }
}
