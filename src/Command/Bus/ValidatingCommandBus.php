<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Command\Bus;

use mohmann\Hexagonal\Command\CommandBusInterface;
use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\HexagonalException;
use mohmann\Hexagonal\Validator\ValidatorResolverInterface;

class ValidatingCommandBus implements CommandBusInterface
{
    /**
     * @var CommandBusInterface
     */
    private $commandBus;

    /**
     * @var ValidatorResolverInterface
     */
    private $validatorResolver;

    /**
     * @param CommandBusInterface $commandBus
     * @param ValidatorResolverInterface $validatorResolver
     */
    public function __construct(
        CommandBusInterface $commandBus,
        ValidatorResolverInterface $validatorResolver
    ) {
        $this->commandBus = $commandBus;
        $this->validatorResolver = $validatorResolver;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(CommandInterface $command)
    {
        $this->validate($command);

        return $this->commandBus->execute($command);
    }

    /**
     * @param CommandInterface $command
     * @throws HexagonalException
     * @return void
     */
    private function validate(CommandInterface $command)
    {
        $validator = $this->validatorResolver->resolveCommandValidator($command);
        
        $validator->validate($command);
    }
}
