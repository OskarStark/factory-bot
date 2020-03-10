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

namespace Ergebnis\FactoryBot\Test\Fixture\Definition\Acceptable;

use Ergebnis\FactoryBot\Definition\Definition;
use Ergebnis\FactoryBot\FixtureFactory;
use Ergebnis\FactoryBot\Test\Fixture;

/**
 * Is acceptable as it implements the interface.
 */
final class UserDefinition implements Definition
{
    public function accept(FixtureFactory $factory): void
    {
        $factory->defineEntity(Fixture\Entity\User::class);
    }
}
