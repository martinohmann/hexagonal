<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Event;

use mohmann\Hexagonal\Event\EventDispatcher;
use mohmann\Hexagonal\Event\EventDispatcherInterface;
use mohmann\Hexagonal\Tests\Event\Fixtures\BarEvent;
use mohmann\Hexagonal\Tests\Event\Fixtures\FooEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class EventDispatcherTest extends TestCase
{
    /**
     * @var SymfonyEventDispatcherInterface
     */
    private $wrapped;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->wrapped = \Phake::mock(SymfonyEventDispatcherInterface::class);
        $this->dispatcher = new EventDispatcher($this->wrapped);
    }

    /**
     * @test
     */
    public function itForwardsEventToWrappedEventDispatcher()
    {
        $event = new FooEvent();

        $this->dispatcher->dispatch($event);

        \Phake::verify($this->wrapped)->dispatch($event->getName(), $event);
    }

    /**
     * @test
     */
    public function itWrapsEventsThatAreNotASubclassOfSymfonyEventBeforePassingThemOn()
    {
        $event = new BarEvent();

        $this->dispatcher->dispatch($event);

        /** @var GenericEvent $wrapperEvent */
        \Phake::verify($this->wrapped)->dispatch($event->getName(), \Phake::capture($wrapperEvent));

        $this->assertInstanceOf(GenericEvent::class, $wrapperEvent);
        $this->assertSame($event, $wrapperEvent->getSubject());
    }
}
