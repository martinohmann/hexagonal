<?php
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Command;

use mohmann\Hexagonal\Command\CommandInflector;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Tests\Command\Fixtures\Bar\BazCommand;
use mohmann\Hexagonal\Tests\Command\Fixtures\Foo;
use mohmann\Hexagonal\Tests\Command\Fixtures\FooCommand;
use PHPUnit\Framework\TestCase;

class CommandInflectorTest extends TestCase
{
    /**
     * @var CommandInflector
     */
    private $inflector;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->inflector = new CommandInflector();
    }

    /**
     * @test
     * @dataProvider provideTestData
     */
    public function itBuildsHandlerClassForCommand(CommandInterface $command, string $expected)
    {
        $className = $this->inflector->getHandlerClass($command);

        $this->assertSame($expected, $className);
    }

    public function provideTestData(): array
    {
        return [
            [new Foo(), 'mohmann\Hexagonal\Tests\Command\Fixtures\FooHandler'],
            [new FooCommand(), 'mohmann\Hexagonal\Tests\Command\Fixtures\FooHandler'],
            [new BazCommand(), 'mohmann\Hexagonal\Tests\Command\Fixtures\Bar\BazHandler'],
        ];
    }
}
