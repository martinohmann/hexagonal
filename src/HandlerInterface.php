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

interface HandlerInterface
{
    /**
     * @param CommandInterface $command
     * @return mixed
     */
    public function handle(CommandInterface $command);

    /**
     * @deprecated
     * @param CommandInterface $command
     * @return bool
     */
    public function canHandle(CommandInterface $command): bool;
}
