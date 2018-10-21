<?php
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use mohmann\Hexagonal\Command\AbstractCommand;
use mohmann\Hexagonal\Command\SimpleCommandBus;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\HexagonalException;
use mohmann\Hexagonal\Handler\Resolver\HandlerResolver;
use mohmann\Hexagonal\HandlerInterface;

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

class FooCommand extends AbstractCommand
{
    public $payload;
    public function __construct(string $payload)
    {
        $this->payload = $payload;
    }
}

class FooHandler implements HandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public function handle(CommandInterface $command)
    {
        return \sprintf('%s baz', $command->payload);
    }
}

$handlerResolver = new HandlerResolver([
    FooCommand::class => new FooHandler(),
]);

$commandBus = new SimpleCommandBus($handlerResolver);

try {
    $command = new FooCommand('bar');

    $result = $commandBus->execute($command);

    var_dump($result);
} catch (HexagonalException $e) {
    var_dump($e);
}
