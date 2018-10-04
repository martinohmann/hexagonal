<?php
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Exception;

use mohmann\Hexagonal\Exception\InvalidClassException;
use PHPUnit\Framework\TestCase;

class InvalidClassExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function itHasMessage()
    {
        $exception = new InvalidClassException('Foo\Bar\Baz', 'Foo\BarInterface');

        $this->assertSame(
            'Class "Foo\Bar\Baz" does not implement "Foo\BarInterface"',
            $exception->getMessage()
        );
    }
}
