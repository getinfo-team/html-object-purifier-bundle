<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle\Metadata\Loader;

use GetInfoTeam\HTMLObjectPurifierBundle\Metadata\MetadataInterface;

interface LoaderInterface
{
    /**
     * @param string $class
     *
     * @return MetadataInterface
     */
    public function load(string $class): MetadataInterface;
}