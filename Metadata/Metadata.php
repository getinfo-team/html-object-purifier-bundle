<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle\Metadata;

use InvalidArgumentException;
use RuntimeException;

class Metadata implements MetadataInterface
{
    private $class;

    /** @var PropertyMetadataInterface[] */
    private $properties = [];

    /**
     * @param string $class
     * @param PropertyMetadataInterface[] $properties
     */
    public function __construct(string $class, iterable $properties)
    {
        if (!class_exists($class)) {
            throw new RuntimeException(sprintf('Class "%s" not exists.', $class));
        }

        $this->class = $class;

        foreach ($properties as $property) {
            if (!$property instanceof PropertyMetadataInterface) {
                throw new InvalidArgumentException(
                    sprintf('Invalid argument "$properties", expected %s[]', PropertyMetadataInterface::class)
                );
            }

            $this->properties[$property->getName()] = $property;
        }
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getProperties(): array
    {
        return array_values($this->properties);
    }

    public function getProperty(string $property): ?PropertyMetadataInterface
    {
        return $this->properties[$property] ?? null;
    }
}