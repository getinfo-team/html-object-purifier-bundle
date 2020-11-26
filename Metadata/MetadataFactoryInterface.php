<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle\Metadata;

interface MetadataFactoryInterface
{
    /**
     * @param object|string $object
     *
     * @return MetadataInterface
     */
    public function getMetadata($object): MetadataInterface;
}