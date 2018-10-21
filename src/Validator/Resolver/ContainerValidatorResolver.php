<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Validator\Resolver;

use mohmann\Hexagonal\Command\Inflector\CommandInflector;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\InvalidValidatorClassException;
use mohmann\Hexagonal\Exception\MissingCommandValidatorException;
use mohmann\Hexagonal\Validator\ValidatorResolverInterface;
use mohmann\Hexagonal\ValidatorInterface;
use Psr\Container\ContainerInterface;

class ContainerValidatorResolver implements ValidatorResolverInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var CommandInflector
     */
    private $commandInflector;

    /**
     * @param ContainerInterface $container
     * @param CommandInflector $commandInflector
     */
    public function __construct(ContainerInterface $container, CommandInflector $commandInflector)
    {
        $this->container = $container;
        $this->commandInflector = $commandInflector;
    }

    /**
     * {@inheritDoc}
     */
    public function resolveCommandValidator(CommandInterface $command): ValidatorInterface
    {
        $commandClass = \get_class($command);
        $validatorClass = $this->commandInflector->getValidatorClass($commandClass);

        if (!$this->container->has($validatorClass)) {
            throw new MissingCommandValidatorException($command);
        }

        $validator = $this->container->get($validatorClass);

        if (!$validator instanceof ValidatorInterface) {
            throw new InvalidValidatorClassException(\get_class($validator));
        }

        return $validator;
    }
}
