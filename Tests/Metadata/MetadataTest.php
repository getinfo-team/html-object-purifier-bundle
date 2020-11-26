<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle\Tests\Metadata;

use GetInfoTeam\HTMLObjectPurifierBundle\Metadata\Metadata;
use GetInfoTeam\HTMLObjectPurifierBundle\Metadata\PropertyMetadata;
use GetInfoTeam\HTMLObjectPurifierBundle\Metadata\PropertyMetadataInterface;
use GetInfoTeam\HTMLObjectPurifierBundle\Tests\Obj;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class MetadataTest extends TestCase
{
    public function testCreateClassNotExists()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Class "__Invalid__" not exists.');

        new Metadata('__Invalid__', []);
    }

    public function testCreateInvalidPropertiesType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('Invalid argument "$properties", expected %s[]', PropertyMetadataInterface::class)
        );

        new Metadata(Obj::class, ['invalid']);
    }

    public function test()
    {
        $propertyMetadata = new PropertyMetadata('foo', 'default', false);
        $metadata = new Metadata(Obj::class, [$propertyMetadata]);

        $this->assertSame(Obj::class, $metadata->getClass());
        $this->assertSame([$propertyMetadata], $metadata->getProperties());
        $this->assertSame($propertyMetadata, $metadata->getProperty('foo'));
        $this->assertNull($metadata->getProperty('bar'));
    }
}
