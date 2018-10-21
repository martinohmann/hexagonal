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
use mohmann\Hexagonal\Command\CommandInflector;
use mohmann\Hexagonal\Command\Bus\SimpleCommandBus;
use mohmann\Hexagonal\Command\Bus\ValidatingCommandBus;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\CommandValidationException;
use mohmann\Hexagonal\Exception\HexagonalException;
use mohmann\Hexagonal\Handler\Resolver\ContainerHandlerResolver;
use mohmann\Hexagonal\Handler\Resolver\HandlerResolver;
use mohmann\Hexagonal\Handler\Resolver\RegistryResolver;
use mohmann\Hexagonal\HandlerInterface;
use mohmann\Hexagonal\Validator\Resolver\ContainerValidatorResolver;
use mohmann\Hexagonal\ValidatorInterface;
use Symfony\Component\DependencyInjection\Container;

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

class FooValidator implements ValidatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function validate(CommandInterface $command)
    {
        if ('baz' !== $command->payload) {
            throw new CommandValidationException('expected command payload to be "baz"');
        }
    }
}

$container = new Container();
$container->set(FooHandler::class, new FooHandler());
$container->set(FooValidator::class, new FooValidator());

$commandInflector = new CommandInflector();

$handlerResolver = new ContainerHandlerResolver($container, $commandInflector);
$validatorResolver = new ContainerValidatorResolver($container, $commandInflector);

$simpleCommandBus = new SimpleCommandBus($handlerResolver);

$commandBus = new ValidatingCommandBus($simpleCommandBus, $validatorResolver);

try {
    $command = new FooCommand('bar');

    $result = $commandBus->execute($command);

    var_dump($result);
} catch (HexagonalException $e) {
    var_dump($e->getMessage());
}

try {
    $command = new FooCommand('baz');

    $result = $commandBus->execute($command);

    var_dump($result);
} catch (HexagonalException $e) {
    var_dump($e->getMessage());
}
