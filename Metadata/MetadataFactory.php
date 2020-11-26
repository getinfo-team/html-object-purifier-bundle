<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle\Metadata;

use GetInfoTeam\HTMLObjectPurifierBundle\Metadata\Loader\LoaderInterface;

class MetadataFactory implements MetadataFactoryInterface
{
    private $metadata = [];

    private $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function getMetadata($object): MetadataInterface
    {
        $class = is_object($object) ? get_class($object) : (string)$object;

        if (!isset($this->metadata[$class])) {
            $this->metadata[$class] = $this->loader->load($class);
        }

        return $this->metadata[$class];
    }
}