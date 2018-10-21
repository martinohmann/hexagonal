hexagonal
=========

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![PHP 7.1+](https://img.shields.io/badge/php-7.1%2B-blue.svg)](https://github.com/mohmann/hexagonal)

This package provides the building blocks to build PHP applications using [Hexagonal Architecture](https://fideloper.com/hexagonal-architecture) (a.k.a. Ports-and-Adapters).

Installation
------------

Via composer:

```
composer require mohmann/hexagonal
```

Usage example
-------------

```php
use mohmann\Hexagonal\Command\AbstractCommand;
use mohmann\Hexagonal\Command\Bus\SimpleCommandBus;
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
```

Check the [examples](examples/) subdirectory for more usage examples.

Development / Testing
---------------------

Refer to the `Makefile` for helpful commands, e.g.:

```sh
make stan
make test
make inf
```

License
-------

hexagonal is released under the MIT License. See the bundled LICENSE file for details.
