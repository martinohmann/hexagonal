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

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\InvalidValidatorClassException;
use mohmann\Hexagonal\Exception\MissingCommandValidatorException;
use mohmann\Hexagonal\Validator\Resolver\ValidatorResolver;
use mohmann\Hexagonal\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class ValidatorResolverTest extends TestCase
{
    /**
     * @var ValidatorResolver
     */
    private $resolver;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->resolver = new ValidatorResolver();
    }

    /**
     * @test
     */
    public function itRegistersCommandValidator()
    {
        $validator = \Phake::mock(ValidatorInterface::class);
        $command = \Phake::mock(CommandInterface::class);

        $this->resolver->registerCommandValidator(\get_class($command), $validator);

        $this->assertSame($validator, $this->resolver->resolveCommandValidator($command));
    }

    /**
     * @test
     */
    public function itRegistersValidatorsOnConstruct()
    {
        $input = [
            'Some\Command' => \Phake::mock(ValidatorInterface::class),
            'Some\Other\Command' => \Phake::mock(ValidatorInterface::class),
        ];

        $registry = new ValidatorResolver($input);

        $validators = $registry->getCommandValidators();

        $this->assertSame($input, $validators);
    }

    /**
     * @test
     */
    public function itRegistersValidators()
    {
        $input = [
            'Some\Command' => \Phake::mock(ValidatorInterface::class),
            'Some\Other\Command' => \Phake::mock(ValidatorInterface::class),
        ];

        $this->resolver->registerCommandValidators($input);

        $validators = $this->resolver->getCommandValidators();

        $this->assertSame($input, $validators);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfOnSuitableCommandValidatorIsAvailable()
    {
        $command = \Phake::mock(CommandInterface::class);

        $this->expectException(MissingCommandValidatorException::class);
        $this->resolver->resolveCommandValidator($command);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfValidatorsHaveInvalidType()
    {
        $validators = [
            'Some\Command' => \Phake::mock(ValidatorInterface::class),
            'Some\Other\Command' => 'foo bar',
        ];

        $this->expectException(InvalidValidatorClassException::class);
        $this->resolver->registerCommandValidators($validators);
    }
}
