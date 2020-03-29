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

namespace Ergebnis\FactoryBot\Test\Fixture\Definition\Definitions\ImplementsDefinition;

use Ergebnis\FactoryBot\Definition\Definition;
use Ergebnis\FactoryBot\FixtureFactory;
use Ergebnis\FactoryBot\Test\Fixture;
use Faker\Generator;

final class RepositoryDefinition implements Definition
{
    public function accept(FixtureFactory $fixtureFactory, Generator $faker): void
    {
        $fixtureFactory->defineEntity(Fixture\FixtureFactory\Entity\Repository::class);
    }
}
