<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Handler;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Exception\HexagonalException;
use mohmann\Hexagonal\HandlerInterface;

interface HandlerResolverInterface
{
    /**
     * @param CommandInterface $command
     * @throws HexagonalException
     * @return HandlerInterface
     */
    public function resolveCommandHandler(CommandInterface $command): HandlerInterface;
}
