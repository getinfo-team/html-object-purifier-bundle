<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle\Metadata;

interface MetadataInterface
{
    public function getClass(): string;

    /**
     * @return PropertyMetadataInterface[]
     */
    public function getProperties(): array;

    /**
     * @param string $property
     *
     * @return PropertyMetadataInterface|null
     */
    public function getProperty(string $property): ?PropertyMetadataInterface;
}