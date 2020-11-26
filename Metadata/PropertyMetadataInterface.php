<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle\Metadata;

interface PropertyMetadataInterface
{
    public function getName(): string;

    public function getProfile(): string;

    public function isExclude(): bool;
}