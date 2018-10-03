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
    public function itBuildsHandlerClassForCommand(string $commandClass, string $expected)
    {
        $className = $this->inflector->getHandlerClass($commandClass);

        $this->assertSame($expected, $className);
    }

    public function provideTestData(): array
    {
        return [
            ['Namespace\Foo', 'Namespace\FooHandler'],
            ['Foo\Bar\Baz\FooCommand', 'Foo\Bar\Baz\FooHandler'],
            ['BazCommand', 'BazHandler'],
            ['\BazCommand', '\BazHandler'],
            ['Some\Namespace\SomeLongerClassName', 'Some\Namespace\SomeLongerClassNameHandler'],
        ];
    }
}
