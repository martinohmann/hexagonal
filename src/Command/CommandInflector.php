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
     * @param CommandInterface $command
     * @return string
     */
    public function getHandlerClass(CommandInterface $command): string
    {
        $commandClass = \get_class($command);
        $namespaceParts = \explode('\\', $commandClass);
        $className = (string) \array_pop($namespaceParts);
        $namespaceParts[] = $this->buildHandlerClassName($className);

        return implode('\\', $namespaceParts);
    }

    /**
     * @param string $className
     * @return string
     */
    private function buildHandlerClassName(string $className): string
    {
        if (\strlen($className) > 6 && false !== ($offset = \stripos($className, 'Command', -7))) {
            $className = \substr($className, 0, (int) $offset);
        }

        return \sprintf('%sHandler', $className);
    }
}
