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

interface CommandBusInterface
{
    /**
     * @param CommandInterface $command
     * @throws HexagonalException
     * @return mixed
     */
    public function execute(CommandInterface $command);
}
