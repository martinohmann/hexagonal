<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Validator;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\InvalidValidatorClassException;
use mohmann\Hexagonal\Exception\MissingCommandValidatorException;
use mohmann\Hexagonal\Validator\ValidatorRegistry;
use mohmann\Hexagonal\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class ValidatorRegistryTest extends TestCase
{
    /**
     * @var ValidatorRegistry
     */
    private $registry;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->registry = new ValidatorRegistry();
    }

    /**
     * @test
     */
    public function itRegistersCommandValidator()
    {
        $validator = \Phake::mock(ValidatorInterface::class);
        $command = \Phake::mock(CommandInterface::class);

        $this->registry->registerCommandValidator(\get_class($command), $validator);

        $this->assertSame($validator, $this->registry->getCommandValidator($command));
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

        $registry = new ValidatorRegistry($input);

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

        $this->registry->registerCommandValidators($input);

        $validators = $this->registry->getCommandValidators();

        $this->assertSame($input, $validators);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfOnSuitableCommandValidatorIsAvailable()
    {
        $command = \Phake::mock(CommandInterface::class);

        $this->expectException(MissingCommandValidatorException::class);
        $this->registry->getCommandValidator($command);
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
        $this->registry->registerCommandValidators($validators);
    }
}
