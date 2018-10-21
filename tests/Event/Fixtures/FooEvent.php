<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Event\Fixtures;

use mohmann\Hexagonal\Event\AbstractEvent;
use mohmann\Hexagonal\EventInterface;
use Symfony\Component\EventDispatcher\Event;

class FooEvent extends Event implements EventInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'foo';
    }
}
