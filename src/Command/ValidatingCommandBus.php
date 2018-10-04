<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Command;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\HexagonalException;
use mohmann\Hexagonal\Handler\HandlerResolverInterface;
use mohmann\Hexagonal\Validator\ValidatorResolverInterface;

class ValidatingCommandBus implements CommandBusInterface
{
    /**
     * @var HandlerResolverInterface
     */
    private $handlerResolver;

    /**
     * @var ValidatorResolverInterface
     */
    private $validatorResolver;

    /**
     * @param HandlerResolverInterface $handlerResolver
     * @param ValidatorResolverInterface $validatorResolver
     */
    public function __construct(
        HandlerResolverInterface $handlerResolver,
        ValidatorResolverInterface $validatorResolver
    ) {
        $this->handlerResolver = $handlerResolver;
        $this->validatorResolver = $validatorResolver;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(CommandInterface $command)
    {
        $this->validate($command);

        $handler = $this->handlerResolver->resolveCommandHandler($command);

        return $handler->handle($command);
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
