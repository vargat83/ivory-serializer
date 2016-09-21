<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\Serializer\Mapping;

use Ivory\Serializer\Mapping\PropertyMetadata;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class PropertyMetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PropertyMetadata
     */
    private $propertyMetadata;

    /**
     * @var string
     */
    private $name;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->name = 'foo';
        $this->propertyMetadata = new PropertyMetadata($this->name);
    }

    public function testInheritance()
    {
        $this->assertInstanceOf(PropertyMetadataInterface::class, $this->propertyMetadata);
    }

    public function testDefaultState()
    {
        $this->assertSame($this->name, $this->propertyMetadata->getName());
        $this->assertFalse($this->propertyMetadata->hasType());
        $this->assertNull($this->propertyMetadata->getType());
        $this->assertFalse($this->propertyMetadata->hasSinceVersion());
        $this->assertNull($this->propertyMetadata->getSinceVersion());
        $this->assertFalse($this->propertyMetadata->hasUntilVersion());
        $this->assertNull($this->propertyMetadata->getUntilVersion());
        $this->assertFalse($this->propertyMetadata->hasMaxDepth());
        $this->assertNull($this->propertyMetadata->getMaxDepth());
        $this->assertFalse($this->propertyMetadata->hasGroups());
        $this->assertEmpty($this->propertyMetadata->getGroups());
    }

    public function testType()
    {
        $this->propertyMetadata->setType($type = $this->createTypeMetadataMock());

        $this->assertTrue($this->propertyMetadata->hasType());
        $this->assertSame($type, $this->propertyMetadata->getType());
    }

    public function testSinceVersion()
    {
        $this->propertyMetadata->setSinceVersion($since = '1.0');

        $this->assertTrue($this->propertyMetadata->hasSinceVersion());
        $this->assertSame($since, $this->propertyMetadata->getSinceVersion());
    }

    public function testUntilVersion()
    {
        $this->propertyMetadata->setUntilVersion($until = '1.0');

        $this->assertTrue($this->propertyMetadata->hasUntilVersion());
        $this->assertSame($until, $this->propertyMetadata->getUntilVersion());
    }

    public function testMaxDepth()
    {
        $this->propertyMetadata->setMaxDepth($maxDepth = 512);

        $this->assertTrue($this->propertyMetadata->hasMaxDepth());
        $this->assertSame($maxDepth, $this->propertyMetadata->getMaxDepth());
    }

    public function testSetGroups()
    {
        $this->propertyMetadata->setGroups($groups = [$group = 'group']);
        $this->propertyMetadata->setGroups($groups);

        $this->assertTrue($this->propertyMetadata->hasGroups());
        $this->assertTrue($this->propertyMetadata->hasGroup($group));
        $this->assertSame($groups, $this->propertyMetadata->getGroups());
    }

    public function testAddGroups()
    {
        $this->propertyMetadata->setGroups($firstGroups = ['group1']);
        $this->propertyMetadata->addGroups($secondGroups = ['group2']);

        $this->assertTrue($this->propertyMetadata->hasGroups());
        $this->assertSame(array_merge($firstGroups, $secondGroups), $this->propertyMetadata->getGroups());
    }

    public function testAddGroup()
    {
        $this->propertyMetadata->addGroup($group = 'group');

        $this->assertTrue($this->propertyMetadata->hasGroups());
        $this->assertTrue($this->propertyMetadata->hasGroup($group));
        $this->assertSame([$group], $this->propertyMetadata->getGroups());
    }

    public function testRemoveGroup()
    {
        $this->propertyMetadata->addGroup($group = 'group');
        $this->propertyMetadata->removeGroup($group);

        $this->assertFalse($this->propertyMetadata->hasGroups());
        $this->assertFalse($this->propertyMetadata->hasGroup($group));
        $this->assertEmpty($this->propertyMetadata->getGroups());
    }

    public function testMerge()
    {
        $propertyMetadata = new PropertyMetadata('foo');
        $propertyMetadata->setType($type = $this->createTypeMetadataMock());
        $propertyMetadata->setMaxDepth($maxDepth = 1);
        $propertyMetadata->setGroups($groups = ['group1', 'group2']);

        $this->propertyMetadata->merge($propertyMetadata);

        $this->assertSame($type, $this->propertyMetadata->getType());
        $this->assertSame($maxDepth, $this->propertyMetadata->getMaxDepth());
        $this->assertSame($groups, $this->propertyMetadata->getGroups());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|TypeMetadataInterface
     */
    private function createTypeMetadataMock()
    {
        return $this->createMock(TypeMetadataInterface::class);
    }
}