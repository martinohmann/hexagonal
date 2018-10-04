<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal;

use mohmann\Hexagonal\Exception\CommandValidationException;

interface ValidatorInterface
{
    /**
     * @param CommandInterface $command
     * @throws CommandValidationException
     * @return void
     */
    public function validate(CommandInterface $command);
}
