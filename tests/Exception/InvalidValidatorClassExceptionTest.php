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

use mohmann\Hexagonal\Exception\InvalidValidatorClassException;
use PHPUnit\Framework\TestCase;

class InvalidValidatorClassExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function itHasMessage()
    {
        $exception = new InvalidValidatorClassException('Foo\Bar\Baz');

        $this->assertSame(
            'Class "Foo\Bar\Baz" does not implement "mohmann\Hexagonal\ValidatorInterface"',
            $exception->getMessage()
        );
    }
}
