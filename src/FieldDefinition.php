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

namespace Ergebnis\FactoryBot;

final class FieldDefinition implements FieldDefinition\Resolvable
{
    /**
     * @var \Closure
     */
    private $closure;

    private function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    public function resolve(FixtureFactory $fixtureFactory)
    {
        $closure = $this->closure;

        return $closure($fixtureFactory);
    }

    public static function closure(\Closure $closure): FieldDefinition\Closure
    {
        return new FieldDefinition\Closure($closure);
    }

    /**
     * Defines a field to be a string based on an incrementing integer.
     *
     * This is typically used to generate unique names such as usernames.
     *
     * The parameter may be a function that receives a counter value
     * each time the entity is created or it may be a string.
     *
     * If the parameter is a string string containing "%d" then it will be
     * replaced by the counter value. If the string does not contain "%d"
     * then the number is simply appended to the parameter.
     *
     * @param callable|string $funcOrString the function or pattern to generate a value from
     * @param int             $firstNum     the first number to use
     *
     * @return FieldDefinition\Sequence|self
     */
    public static function sequence($funcOrString, int $firstNum = 1)
    {
        $n = $firstNum - 1;

        if (\is_callable($funcOrString)) {
            return new self(static function () use (&$n, $funcOrString) {
                ++$n;

                return \call_user_func($funcOrString, $n);
            });
        }

        if (false === \strpos($funcOrString, '%d')) {
            $funcOrString .= '%d';
        }

        return new FieldDefinition\Sequence(
            $funcOrString,
            $firstNum
        );
    }

    /**
     * @phpstan-param class-string<T> $className
     * @phpstan-return FieldDefinition\Reference<T>
     * @phpstan-template T
     *
     * @psalm-param class-string<T> $className
     * @psalm-return FieldDefinition\Reference<T>
     * @psalm-template T
     *
     * @param string $className
     *
     * @return FieldDefinition\Reference
     */
    public static function reference(string $className): FieldDefinition\Reference
    {
        return new FieldDefinition\Reference($className);
    }

    /**
     * @phpstan-param class-string<T> $className
     * @phpstan-return FieldDefinition\References<T>
     * @phpstan-template T
     *
     * @psalm-param class-string<T> $className
     * @psalm-return FieldDefinition\References<T>
     * @psalm-template T
     *
     * @param string $className
     * @param int    $count
     *
     * @throws Exception\InvalidCount
     *
     * @return FieldDefinition\References
     */
    public static function references(string $className, int $count = 1): FieldDefinition\References
    {
        return new FieldDefinition\References(
            $className,
            $count
        );
    }

    /**
     * @phpstan-param T $value
     * @phpstan-return FieldDefinition\Value<T>
     * @phpstan-template T
     *
     * @psalm-param T $value
     * @psalm-return FieldDefinition\Value<T>
     * @psalm-template T
     *
     * @param mixed $value
     *
     * @return FieldDefinition\Value
     */
    public static function value($value): FieldDefinition\Value
    {
        return new FieldDefinition\Value($value);
    }
}
