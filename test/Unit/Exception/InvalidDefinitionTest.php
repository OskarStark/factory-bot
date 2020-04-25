<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/factory-bot
 */

namespace Ergebnis\FactoryBot\Test\Unit\Exception;

use Ergebnis\FactoryBot\Exception;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\FactoryBot\Exception\InvalidDefinition
 */
final class InvalidDefinitionTest extends Framework\TestCase
{
    use Helper;

    public function testFromClassNameCreatesException(): void
    {
        $className = self::faker()->word;
        $previousException = new \Exception();

        $exception = Exception\InvalidDefinition::fromClassNameAndException(
            $className,
            $previousException
        );

        $message = \sprintf(
            'An exception was thrown while trying to instantiate definition "%s".',
            $className
        );

        self::assertInstanceOf(Exception\InvalidDefinition::class, $exception);
        self::assertInstanceOf(\RuntimeException::class, $exception);
        self::assertInstanceOf(Exception\Exception::class, $exception);
        self::assertSame($message, $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($previousException, $exception->getPrevious());
    }
}
