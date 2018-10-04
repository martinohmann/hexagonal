<?php
/*
 * This file is part of the hexagonal package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\Hexagonal\Tests\Validator\Resolver;

use mohmann\Hexagonal\CommandInterface;
use mohmann\Hexagonal\Tests\Command\Fixtures\FooCommand;
use mohmann\Hexagonal\Tests\Validator\Fixtures\FooValidator;
use mohmann\Hexagonal\Validator\Resolver\LoggingResolver;
use mohmann\Hexagonal\Validator\ValidatorResolverInterface;
use mohmann\Hexagonal\ValidatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class LoggingResolverTest extends TestCase
{
    /**
     * @var ValidatorResolverInterface
     */
    private $decoratedResolver;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var LoggingResolver
     */
    private $loggingResolver;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->decoratedResolver = \Phake::mock(ValidatorResolverInterface::class);
        $this->logger = \Phake::mock(LoggerInterface::class);
        $this->loggingResolver = new LoggingResolver($this->decoratedResolver, $this->logger);
    }

    /**
     * @test
     */
    public function itCallsDecoratedResolver()
    {
        $command = \Phake::mock(CommandInterface::class);
        $validator = \Phake::mock(ValidatorInterface::class);

        \Phake::when($this->decoratedResolver)
            ->resolveCommandValidator($command)
            ->thenReturn($validator);

        $result = $this->loggingResolver->resolveCommandValidator($command);

        \Phake::verify($this->decoratedResolver)
            ->resolveCommandValidator($command);

        $this->assertSame($validator, $result);
    }

    /**
     * @test
     */
    public function itLogsResolvedValidatorAndCommandClass()
    {
        $command = new FooCommand();
        $validator = new FooValidator();

        \Phake::when($this->decoratedResolver)
            ->resolveCommandValidator($command)
            ->thenReturn($validator);

        $this->loggingResolver->resolveCommandValidator($command);

        \Phake::verify($this->logger)
            ->info(\Phake::capture($message));

        $this->assertSame(
            'Validating command "mohmann\Hexagonal\Tests\Command\Fixtures\FooCommand" ' .
            'with "mohmann\Hexagonal\Tests\Validator\Fixtures\FooValidator"',
            $message
        );
    }
}
