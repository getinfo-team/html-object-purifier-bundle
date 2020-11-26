<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle\Metadata\Loader;

use Doctrine\Common\Annotations\Reader;
use GetInfoTeam\HTMLObjectPurifierBundle\Annotation\Purify;
use GetInfoTeam\HTMLObjectPurifierBundle\Metadata\Metadata;
use GetInfoTeam\HTMLObjectPurifierBundle\Metadata\MetadataInterface;
use GetInfoTeam\HTMLObjectPurifierBundle\Metadata\PropertyMetadata;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

class AnnotationLoader implements LoaderInterface
{
    private $propertyInfo;

    private $reader;

    public function __construct(PropertyInfoExtractorInterface $propertyInfo, Reader $reader)
    {
        $this->propertyInfo = $propertyInfo;
        $this->reader = $reader;
    }

    /**
     * @param string $class
     * @return MetadataInterface
     */
    public function load(string $class): MetadataInterface
    {
        $properties = [];

        /** @var string $property */
        foreach ((array)$this->propertyInfo->getProperties($class) as $property) {
            if (!$this->isValidProperty($class, $property)) {
                continue;
            }

            try {
                $ref = new ReflectionProperty($class, $property);
            } catch (ReflectionException $e) {
                continue;
            }

            /** @var Purify|null $purify */
            $purify = $this->reader->getPropertyAnnotation($ref, Purify::class);

            if (!$purify) {
                continue;
            }

            $properties[] = new PropertyMetadata($property, $purify->profile, $purify->exclude);
        }

        return new Metadata($class, $properties);
    }

    private function isValidProperty(string $class, string $property): bool
    {
        $types = $this->propertyInfo->getTypes($class, $property);

        return $this->propertyInfo->isReadable($class, $property) &&
            $this->propertyInfo->isWritable($class, $property) &&
            is_array($types) &&
            1 === count($types) &&
            Type::BUILTIN_TYPE_STRING === $types[0]->getBuiltinType()
        ;
    }
}