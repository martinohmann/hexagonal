<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Handler\Fixtures;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\HandlerInterface;

class FooHandler implements HandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public function handle(CommandInterface $command)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function canHandle(CommandInterface $command): bool
    {
        return true;
    }
}
