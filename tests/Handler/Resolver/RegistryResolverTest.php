<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Handler\Resolver;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Handler\HandlerRegistry;
use mohmann\Hexagonal\Handler\Resolver\RegistryResolver;
use mohmann\Hexagonal\HandlerInterface;
use PHPUnit\Framework\TestCase;

class RegistryResolverTest extends TestCase
{
    /**
     * @var HandlerRegistry
     */
    private $handlerRegistry;

    /**
     * @var RegistryResolver
     */
    private $registryResolver;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->handlerRegistry = \Phake::mock(HandlerRegistry::class);
        $this->registryResolver = new RegistryResolver($this->handlerRegistry);
    }

    /**
     * @test
     */
    public function itResolvesCommandHandler()
    {
        $handler = \Phake::mock(HandlerInterface::class);
        $command = \Phake::mock(CommandInterface::class);

        \Phake::when($this->handlerRegistry)
            ->getCommandHandler($command)
            ->thenReturn($handler);

        $resolved = $this->registryResolver->resolveCommandHandler($command);

        $this->assertSame($handler, $resolved);
    }
}
