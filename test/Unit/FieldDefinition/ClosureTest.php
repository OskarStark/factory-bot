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

namespace Ergebnis\FactoryBot\Test\Unit\FieldDefinition;

use Ergebnis\FactoryBot\FieldDefinition\Closure;
use Ergebnis\FactoryBot\FixtureFactory;
use Ergebnis\FactoryBot\Test\Fixture;
use Ergebnis\FactoryBot\Test\Unit\AbstractTestCase;

/**
 * @internal
 *
 * @covers \Ergebnis\FactoryBot\FieldDefinition\Closure
 *
 * @uses \Ergebnis\FactoryBot\EntityDefinition
 * @uses \Ergebnis\FactoryBot\FieldDefinition
 * @uses \Ergebnis\FactoryBot\FieldDefinition\Value
 * @uses \Ergebnis\FactoryBot\FixtureFactory
 */
final class ClosureTest extends AbstractTestCase
{
    public function testResolveReturnsResultOfInvokingClosureWithFixtureFactory(): void
    {
        $fixtureFactory = new FixtureFactory(self::entityManager());

        $fixtureFactory->defineEntity(Fixture\FixtureFactory\Entity\User::class);

        $closure = static function (FixtureFactory $fixtureFactory): Fixture\FixtureFactory\Entity\User {
            return $fixtureFactory->create(Fixture\FixtureFactory\Entity\User::class);
        };

        $fieldDefinition = Closure::required($closure);

        $resolved = $fieldDefinition->resolve($fixtureFactory);

        self::assertInstanceOf(Fixture\FixtureFactory\Entity\User::class, $resolved);
    }
}
