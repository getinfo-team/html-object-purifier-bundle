<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Purify
{
    /** @var string */
    public $profile = 'default';

    /** @var bool */
    public $exclude = false;
}