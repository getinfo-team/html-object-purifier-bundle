<?php

declare(strict_types=1);

namespace GetInfoTeam\HTMLObjectPurifierBundle\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Exercise\HTMLPurifierBundle\HTMLPurifiersRegistryInterface;
use GetInfoTeam\HTMLObjectPurifierBundle\Metadata\Loader\AnnotationLoader;
use GetInfoTeam\HTMLObjectPurifierBundle\Metadata\MetadataFactory;
use GetInfoTeam\HTMLObjectPurifierBundle\ObjectPurifier;
use HTMLPurifier;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;

class ObjectPurifierTest extends TestCase
{
    private $purifier;

    public function testPurify()
    {
        $object = new Obj();

        $this->purifier->purify($object);

        $this->assertSame('Purified (default): Foo', $object->foo);
        $this->assertSame(123, $object->bar);
        $this->assertSame('Baz', $object->baz);
        $this->assertSame('Purified (profile): Qux', $object->qux);
        $this->assertSame('Qwe', $object->qwe);
        $this->assertNull($object->asd);
        $this->assertSame('Purified (default): Bat', $object->getBat());
    }

    public function testPurifyProperties()
    {
        $object = new Obj();

        $this->purifier->purifyProperties($object, ['foo', 'bar', 'baz', 'qux']);

        $this->assertSame('Purified (default): Foo', $object->foo);
        $this->assertSame(123, $object->bar);
        $this->assertSame('Baz', $object->baz);
        $this->assertSame('Purified (profile): Qux', $object->qux);
        $this->assertSame('Qwe', $object->qwe);
        $this->assertNull($object->asd);
        $this->assertSame('Bat', $object->getBat());
    }

    protected function setUp(): void
    {
        $reflectionExtractor = new ReflectionExtractor();
        $phpDocExtractor = new PhpDocExtractor();
        $propertyInfo = new PropertyInfoExtractor(
            [$reflectionExtractor],
            [$phpDocExtractor, $reflectionExtractor],
            [$phpDocExtractor],
            [$reflectionExtractor],
            [$reflectionExtractor]
        );
        $metadataFactory = new MetadataFactory(new AnnotationLoader($propertyInfo, new AnnotationReader()));

        $registry = $this->createPurifiersRegistry([
            'default' => $this->createPurifier('default'),
            'profile' => $this->createPurifier('profile')
        ]);

        $this->purifier = new ObjectPurifier($metadataFactory, $registry, PropertyAccess::createPropertyAccessor());
    }

    /**
     * @param HTMLPurifier[] $purifiers
     * @return HTMLPurifiersRegistryInterface
     */
    public function createPurifiersRegistry(array $purifiers): HTMLPurifiersRegistryInterface
    {
        return new class($purifiers) implements HTMLPurifiersRegistryInterface {

            private $purifiers;

            public function __construct(array $purifiers)
            {
                $this->purifiers = $purifiers;
            }

            public function has(string $profile): bool
            {
                return array_key_exists($profile, $this->purifiers);
            }

            public function get(string $profile): HTMLPurifier
            {
                if (!$this->has($profile)) {
                    throw new RuntimeException(sprintf('Profile "%s" not exists.', $profile));
                }
                return $this->purifiers[$profile];
            }
        };
    }

    private function createPurifier(string $profile): HTMLPurifier
    {
        return new class($profile) extends HTMLPurifier {
            private $profile;

            public function __construct(string $profile)
            {
                $this->profile = $profile;
                parent::__construct();
            }

            public function purify($html, $config = null)
            {
                return sprintf('Purified (%s): %s', $this->profile, $html);
            }
        };
    }
}
