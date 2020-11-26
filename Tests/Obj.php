<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle\Tests;

use GetInfoTeam\HTMLObjectPurifierBundle\Annotation\Purify;

class Obj
{
    /**
     * @Purify()
     *
     * @var string
     */
    public $foo = 'Foo';

    /**
     * @Purify()
     *
     * @var int
     */
    public $bar = 123;

    /**
     * @Purify(exclude=true)
     *
     * @var string
     */
    public $baz = 'Baz';

    /**
     * @Purify(profile="profile")
     *
     * @var string
     */
    public $qux = 'Qux';

    /**
     * @var string
     */
    public $qwe = 'Qwe';

    /**
     * @Purify()
     *
     * @var string|null
     */
    public $asd = null;

    /**
     * @Purify()
     *
     * @var string
     */
    private $bat = 'Bat';

    private $zxcProp = 'Zxc';

    public function getBat(): string
    {
        return $this->bat;
    }

    public function setBat(string $bat)
    {
        $this->bat = $bat;
    }

    public function getZxc(): string
    {
        return $this->zxcProp;
    }

    public function setZxc(string $zxc)
    {
        $this->zxcProp = $zxc;
    }
}