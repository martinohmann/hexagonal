<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Command;

use mohmann\Hexagonal\CommandInterface;

class CommandInflector
{
    /**
     * @const string
     */
    const TYPE_HANDLER = 'Handler';

    /**
     * @const string
     */
    const TYPE_VALIDATOR = 'Validator';

    /**
     * @param string $commandClass
     * @return string
     */
    public function getHandlerClass(string $commandClass): string
    {
        return $this->getClass($commandClass, self::TYPE_HANDLER);
    }

    /**
     * @param string $commandClass
     * @return string
     */
    public function getValidatorClass(string $commandClass): string
    {
        return $this->getClass($commandClass, self::TYPE_VALIDATOR);
    }

    /**
     * @param string $commandClass
     * @param string $type
     * @return string
     */
    private function getClass(string $commandClass, string $type): string
    {
        $namespaceParts = \explode('\\', $commandClass);

        /** @var string $className */
        $className = \array_pop($namespaceParts);

        $namespaceParts[] = $this->buildClassName($className, $type);

        return \implode('\\', $namespaceParts);
    }

    /**
     * @param string $className
     * @param string $type
     * @return string
     */
    private function buildClassName(string $className, string $type): string
    {
        $className = \preg_replace('/Command$/', '', $className);

        return $className . $type;
    }
}
