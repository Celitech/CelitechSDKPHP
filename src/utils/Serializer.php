<?php

namespace Celitech\Utils;

use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;

class Serializer
{
    private static $serializer = null;

    private static function getSerializer(): SymfonySerializer
    {
        if (self::$serializer === null) {
            $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
            $nameConverter = new MetadataAwareNameConverter($classMetadataFactory);
            $normalizer = new ObjectNormalizer($classMetadataFactory, $nameConverter, null, new PhpDocExtractor());
            self::$serializer = new SymfonySerializer(
                [$normalizer, new ArrayDenormalizer()],
                [new JsonEncoder(), new JsonDecode()]
            );
        }

        return self::$serializer;
    }

    public static function deserialize(string $data, string $class)
    {
        return self::getSerializer()->deserialize($data, $class, 'json');
    }

    public static function serialize(mixed $object): array
    {
        $context = [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
        ];

        $json = self::getSerializer()->serialize($object, 'json', $context);
        return json_decode($json, true);
    }
}
