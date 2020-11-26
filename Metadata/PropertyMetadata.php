<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle\Metadata;

class PropertyMetadata implements PropertyMetadataInterface
{
    private $name;

    private $profile;

    protected $exclude;

    public function __construct(string $name, string $profile, bool $exclude)
    {
        $this->name = $name;
        $this->profile = $profile;
        $this->exclude = $exclude;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProfile(): string
    {
        return $this->profile;
    }

    public function isExclude(): bool
    {
        return $this->exclude;
    }
}