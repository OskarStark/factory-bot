<?php
namespace FactoryGirl\Tests\Provider\Doctrine\Fixtures;

use Doctrine\Common\Collections\ArrayCollection;
use FactoryGirl\Provider\Doctrine\FieldDef;

class BasicUsageTest extends TestCase
{
    /**
     * @test
     */
    public function acceptsConstantValuesInEntityDefinitions()
    {
        $ss = $this->factory
            ->defineEntity('SpaceShip', [
                'name' => 'My BattleCruiser'
            ])
            ->get('SpaceShip');

        $this->assertSame('My BattleCruiser', $ss->getName());
    }

    /**
     * @test
     */
    public function acceptsGeneratorFunctionsInEntityDefinitions()
    {
        $name = "Star";
        $this->factory->defineEntity('SpaceShip', [
            'name' => function () use (&$name) {
                return "M/S $name";
            }
        ]);

        $this->assertSame('M/S Star', $this->factory->get('SpaceShip')->getName());
        $name = "Superstar";
        $this->assertSame('M/S Superstar', $this->factory->get('SpaceShip')->getName());
    }

    /**
     * @test
     */
    public function valuesCanBeOverriddenAtCreationTime()
    {
        $ss = $this->factory
            ->defineEntity('SpaceShip', [
                'name' => 'My BattleCruiser'
            ])
            ->get('SpaceShip', ['name' => 'My CattleBruiser']);
        $this->assertSame('My CattleBruiser', $ss->getName());
    }

    /**
     * @test
     */
    public function preservesDefaultValuesOfEntity()
    {
        $ss = $this->factory
            ->defineEntity('SpaceStation')
            ->get('SpaceStation');
        $this->assertSame('Babylon5', $ss->getName());
    }

    /**
     * @test
     */
    public function doesNotCallTheConstructorOfTheEntity()
    {
        $ss = $this->factory
            ->defineEntity('SpaceShip', [])
            ->get('SpaceShip');
        $this->assertFalse($ss->constructorWasCalled());
    }

    /**
     * @test
     */
    public function instantiatesCollectionAssociationsToBeEmptyCollectionsWhenUnspecified()
    {
        $ss = $this->factory
            ->defineEntity('SpaceShip', [
                'name' => 'Battlestar Galaxy'
            ])
            ->get('SpaceShip');

        $this->assertInstanceOf(ArrayCollection::class, $ss->getCrew());
        $this->assertEmpty($ss->getCrew());
    }

    /**
     * @test
     */
    public function arrayElementsAreMappedToCollectionAsscociationFields()
    {
        $this->factory->defineEntity('SpaceShip');
        $this->factory->defineEntity('Person', [
            'spaceShip' => FieldDef::reference('SpaceShip')
        ]);

        $p1 = $this->factory->get('Person');
        $p2 = $this->factory->get('Person');

        $ship = $this->factory->get('SpaceShip', [
            'name' => 'Battlestar Galaxy',
            'crew' => [$p1, $p2]
        ]);

        $this->assertInstanceOf(ArrayCollection::class, $ship->getCrew());
        $this->assertTrue($ship->getCrew()->contains($p1));
        $this->assertTrue($ship->getCrew()->contains($p2));
    }

    /**
     * @test
     */
    public function unspecifiedFieldsAreLeftNull()
    {
        $this->factory->defineEntity('SpaceShip');
        $this->assertNull($this->factory->get('SpaceShip')->getName());
    }

    /**
     * @test
     */
    public function entityIsDefinedToDefaultNamespace()
    {
        $this->factory->defineEntity('SpaceShip');
        $this->factory->defineEntity('Person\User');

        $this->assertInstanceOf(
            'FactoryGirl\Tests\Provider\Doctrine\Fixtures\TestEntity\SpaceShip',
            $this->factory->get('SpaceShip')
        );

        $this->assertInstanceOf(
            'FactoryGirl\Tests\Provider\Doctrine\Fixtures\TestEntity\Person\User',
            $this->factory->get('Person\User')
        );
    }

    /**
     * @test
     */
    public function entityCanBeDefinedToAnotherNamespace()
    {
        $this->factory->defineEntity(
            '\FactoryGirl\Tests\Provider\Doctrine\Fixtures\TestAnotherEntity\Artist'
        );

        $this->assertInstanceOf(
            'FactoryGirl\Tests\Provider\Doctrine\Fixtures\TestAnotherEntity\Artist',
            $this->factory->get(
                '\FactoryGirl\Tests\Provider\Doctrine\Fixtures\TestAnotherEntity\Artist'
            )
        );
    }

    /**
     * @test
     */
    public function returnsListOfEntities()
    {
        $this->factory->defineEntity('SpaceShip');

        $this->assertCount(1, $this->factory->getList('SpaceShip'));
    }

    /**
     * @test
     */
    public function canSpecifyNumberOfReturnedInstances()
    {
        $this->factory->defineEntity('SpaceShip');

        $this->assertCount(5, $this->factory->getList('SpaceShip', [], 5));
    }
}
