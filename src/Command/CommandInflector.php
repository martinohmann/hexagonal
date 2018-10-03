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

class CommandInflector
{
    /**
     * @param string $commandClass
     * @return string
     */
    public function getHandlerClass(string $commandClass): string
    {
        $namespaceParts = \explode('\\', $commandClass);

        /** @var string $className */
        $className = \array_pop($namespaceParts);

        $namespaceParts[] = $this->buildHandlerClassName($className);

        return \implode('\\', $namespaceParts);
    }

    /**
     * @param string $className
     * @return string
     */
    private function buildHandlerClassName(string $className): string
    {
        $className = \preg_replace('/Command$/', '', $className);

        return \sprintf('%sHandler', $className);
    }
}
