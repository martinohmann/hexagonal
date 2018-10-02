<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Exception;

use mohmann\Hexagonal\CommandInterface;

class HandlerNotFoundException extends HexagonalException
{
    /**
     * @var CommandInterface
     */
    private $command;

    /**
     * @param CommandInterface $command
     */
    public function __construct(CommandInterface $command)
    {
        $this->command = $command;

        $message = \sprintf(
            'No handler found for command "%s" with context "%s"',
            \get_class($command),
            \json_encode($command->getContext())
        );

        parent::__construct($message);
    }

    /**
     * @return CommandInterface
     */
    public function getCommand(): CommandInterface
    {
        return $this->command;
    }
}
