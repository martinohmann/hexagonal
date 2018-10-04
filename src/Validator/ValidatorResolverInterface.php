<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Validator;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\HexagonalException;
use mohmann\Hexagonal\ValidatorInterface;

interface ValidatorResolverInterface
{
    /**
     * @param CommandInterface $command
     * @throws HexagonalException
     * @return ValidatorInterface
     */
    public function resolveCommandValidator(CommandInterface $command): ValidatorInterface;
}
