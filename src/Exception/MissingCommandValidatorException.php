<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Exception;

use mohmann\Hexagonal\CommandInterface;

class MissingCommandValidatorException extends HexagonalException
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
            'The command "%s" with context "%s" could not be validated because there is no suitable validator for it',
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
