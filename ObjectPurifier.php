<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle;

use Exercise\HTMLPurifierBundle\HTMLPurifiersRegistryInterface;
use GetInfoTeam\HTMLObjectPurifierBundle\Metadata\MetadataFactoryInterface;
use GetInfoTeam\HTMLObjectPurifierBundle\Metadata\PropertyMetadataInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class ObjectPurifier implements ObjectPurifierInterface
{
    private $factory;

    private $registry;

    private $accessor;

    public function __construct(MetadataFactoryInterface $factory, HTMLPurifiersRegistryInterface $registry, PropertyAccessorInterface $accessor)
    {
        $this->factory = $factory;
        $this->registry = $registry;
        $this->accessor = $accessor;
    }

    public function purify($object): void
    {
        $metadata = $this->factory->getMetadata($object);

        foreach ($metadata->getProperties() as $property) {
            $this->purifyProperty($object, $property);
        }
    }

    public function purifyProperties($object, array $properties): void
    {
        $metadata = $this->factory->getMetadata($object);

        foreach ($properties as $property) {
            $propertyMetadata = $metadata->getProperty($property);

            if (is_null($propertyMetadata)) {
                continue;
            }

            $this->purifyProperty($object, $propertyMetadata);
        }
    }

    /**
     * @param object $object
     * @param PropertyMetadataInterface $metadata
     */
    private function purifyProperty($object, PropertyMetadataInterface $metadata): void
    {
        if ($metadata->isExclude()) {
            return;
        }

        $value = $this->accessor->getValue($object, $metadata->getName());

        if (!is_string($value)) {
            return;
        }

        $value = $this->registry->get($metadata->getProfile())->purify($value);

        $this->accessor->setValue($object, $metadata->getName(), $value);
    }
}