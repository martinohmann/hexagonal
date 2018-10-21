<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
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
     * @dataProvider provideHandlerClassTestData
     */
    public function itBuildsHandlerClassForCommand(string $commandClass, string $expected)
    {
        $className = $this->inflector->getHandlerClass($commandClass);

        $this->assertSame($expected, $className);
    }

    /**
     * @test
     * @dataProvider provideValidatorClassTestData
     */
    public function itBuildsValidatorClassForCommand(string $commandClass, string $expected)
    {
        $className = $this->inflector->getValidatorClass($commandClass);

        $this->assertSame($expected, $className);
    }

    /**
     * @return array
     */
    public function provideHandlerClassTestData(): array
    {
        return [
            ['Namespace\Foo', 'Namespace\FooHandler'],
            ['Foo\Bar\Baz\FooCommand', 'Foo\Bar\Baz\FooHandler'],
            ['BazCommand', 'BazHandler'],
            ['\BazCommand', '\BazHandler'],
            ['Some\Namespace\SomeLongerClassName', 'Some\Namespace\SomeLongerClassNameHandler'],
        ];
    }

    /**
     * @return array
     */
    public function provideValidatorClassTestData(): array
    {
        return [
            ['Namespace\Foo', 'Namespace\FooValidator'],
            ['Foo\Bar\Baz\FooCommand', 'Foo\Bar\Baz\FooValidator'],
            ['BazCommand', 'BazValidator'],
            ['\BazCommand', '\BazValidator'],
            ['Some\Namespace\SomeLongerClassName', 'Some\Namespace\SomeLongerClassNameValidator'],
        ];
    }
}
