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

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\InvalidValidatorClassException;
use mohmann\Hexagonal\Exception\MissingCommandValidatorException;
use mohmann\Hexagonal\Validator\ValidatorResolverInterface;
use mohmann\Hexagonal\ValidatorInterface;

class ValidatorResolver implements ValidatorResolverInterface
{
    /**
     * @var ValidatorInterface[]
     */
    private $validators;

    /**
     * @param ValidatorInterface[] $validators
     */
    public function __construct(array $validators = [])
    {
        $this->registerCommandValidators($validators);
    }

    /**
     * {@inheritDoc}
     */
    public function resolveCommandValidator(CommandInterface $command): ValidatorInterface
    {
        $commandClass = \get_class($command);

        if (!isset($this->validators[$commandClass])) {
            throw new MissingCommandValidatorException($command);
        }

        return $this->validators[$commandClass];
    }

    /**
     * @return ValidatorInterface[]
     */
    public function getCommandValidators(): array
    {
        return $this->validators;
    }

    /**
     * @param string $commandClass
     * @param ValidatorInterface $validator
     * @return void
     */
    public function registerCommandValidator(string $commandClass, ValidatorInterface $validator)
    {
        $this->validators[$commandClass] = $validator;
    }

    /**
     * @param ValidatorInterface[] $validators
     * @return void
     */
    public function registerCommandValidators(array $validators)
    {
        foreach ($validators as $validator) {
            if (!$validator instanceof ValidatorInterface) {
                throw new InvalidValidatorClassException(
                    \is_object($validator) ? \get_class($validator) : \gettype($validator)
                );
            }
        }

        $this->validators = $validators;
    }
}
