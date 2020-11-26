<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle;

interface ObjectPurifierInterface
{
    /**
     * @param object $object
     */
    public function purify($object): void;

    /**
     * @param object $object
     * @param string[] $properties
     */
    public function purifyProperties($object, array $properties): void;
}