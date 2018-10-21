<?php
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use mohmann\Hexagonal\Event\EventDispatcher;
use mohmann\Hexagonal\EventInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

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

$symfonyEventDispatcher = new SymfonyEventDispatcher();
$symfonyEventDispatcher->addListener('foo', function (FooEvent $event) {
    var_dump($event);
});

$eventDispatcher = new EventDispatcher($symfonyEventDispatcher);
$eventDispatcher->dispatch(new FooEvent());
