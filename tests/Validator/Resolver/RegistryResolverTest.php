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
use mohmann\Hexagonal\Validator\Resolver\RegistryResolver;
use mohmann\Hexagonal\Validator\ValidatorRegistry;
use mohmann\Hexagonal\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class RegistryResolverTest extends TestCase
{
    /**
     * @var ValidatorRegistry
     */
    private $validatorRegistry;

    /**
     * @var RegistryResolver
     */
    private $registryResolver;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->validatorRegistry = \Phake::mock(ValidatorRegistry::class);
        $this->registryResolver = new RegistryResolver($this->validatorRegistry);
    }

    /**
     * @test
     */
    public function itResolvesCommandValidator()
    {
        $validator = \Phake::mock(ValidatorInterface::class);
        $command = \Phake::mock(CommandInterface::class);

        \Phake::when($this->validatorRegistry)
            ->getCommandValidator($command)
            ->thenReturn($validator);

        $resolved = $this->registryResolver->resolveCommandValidator($command);

        $this->assertSame($validator, $resolved);
    }
}
