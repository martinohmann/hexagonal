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
use mohmann\Hexagonal\Validator\ValidatorRegistry;
use mohmann\Hexagonal\Validator\ValidatorResolverInterface;
use mohmann\Hexagonal\ValidatorInterface;

class RegistryResolver implements ValidatorResolverInterface
{
    /**
     * @var ValidatorRegistry
     */
    private $validatorRegistry;

    /**
     * @param ValidatorRegistry $validatorRegistry
     */
    public function __construct(ValidatorRegistry $validatorRegistry)
    {
        $this->validatorRegistry = $validatorRegistry;
    }

    /**
     * {@inheritDoc}
     */
    public function resolveCommandValidator(CommandInterface $command): ValidatorInterface
    {
        return $this->validatorRegistry->getCommandValidator($command);
    }
}
