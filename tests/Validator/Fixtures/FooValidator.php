<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Validator\Fixtures;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\ValidatorInterface;

class FooValidator implements ValidatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function validate(CommandInterface $command)
    {
    }
}
