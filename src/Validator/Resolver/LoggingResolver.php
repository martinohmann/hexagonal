<?php declare(strict_types=1);
/*
 * This file is part of the hexagonal package.
 *
 * (c) 2018 Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Validator\Resolver;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Validator\ValidatorResolverInterface;
use mohmann\Hexagonal\ValidatorInterface;
use Psr\Log\LoggerInterface;

class LoggingResolver implements ValidatorResolverInterface
{
    /**
     * @var ValidatorResolverInterface
     */
    private $validatorResolver;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ValidatorResolverInterface $validatorResolver
     * @param LoggerInterface $logger
     */
    public function __construct(ValidatorResolverInterface $validatorResolver, LoggerInterface $logger)
    {
        $this->validatorResolver = $validatorResolver;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function resolveCommandValidator(CommandInterface $command): ValidatorInterface
    {
        $validator = $this->validatorResolver->resolveCommandValidator($command);

        $this->logger->info(
            \sprintf(
                'Validating command "%s" with "%s"',
                \get_class($command),
                \get_class($validator)
            )
        );

        return $validator;
    }
}
