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

use mohmann\Hexagonal\HandlerInterface;

class InvalidHandlerClassException extends InvalidClassException
{
    /**
     * @param string $className
     */
    public function __construct(string $className)
    {
        parent::__construct($className, HandlerInterface::class);
    }
}
