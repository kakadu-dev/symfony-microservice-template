<?php

namespace App\Serializers;

use App\Components\MicroserviceRequest;
use Symfony\Component\Serializer\Encoder\{
    JsonEncode,
    JsonEncoder
};
use Symfony\Component\Serializer\Normalizer\{
    AbstractObjectNormalizer,
    ObjectNormalizer
};
use Symfony\Component\Serializer\Serializer;

/**
 * Class MicroserviceSerializer
 * @package App\Serializers
 */
abstract class MicroserviceSerializer
{
    /**
     * it's specific field for Doctrine
     */
    protected const IGNORED_ATTRIBUTES = [
        '__initializer__',
        '__cloner__',
        '__isInitialized__',
    ];

    /**
     * @return array
     */
    protected function getNormalizers(): array
    {
        return [
            new ObjectNormalizer(
                null,
                $this->getNameConverter(),
                null,
                null,
                null,
                null,
                $this->getContext(),
            ),
        ];
    }

    /**
     * @return null
     */
    protected function getNameConverter()
    {
        return null;
    }

    /**
     * @return array
     */
    protected function getContext(): array
    {
        return [
            AbstractObjectNormalizer::IGNORED_ATTRIBUTES         => $this->getIgnoredAttributes(),
            AbstractObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ];
    }

    /**
     * @return array
     */
    protected function getEncoders(): array
    {
        $options = [
            'json_encode_options' => JSON_UNESCAPED_UNICODE,
        ];

        $encode = new JsonEncode($options);

        return [
            new JsonEncoder($encode),
        ];
    }

    /**
     * @return Serializer
     */
    protected function getSerializer(): Serializer
    {
        return new Serializer(
            $this->getNormalizers(),
            $this->getEncoders()
        );
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function serialize($data): string
    {
        return $this->getSerializer()->serialize(
            $data,
            'json',
        );
    }

    /**
     * @return array|string[]
     */
    protected function getIgnoredAttributes(): array
    {
        $attributes = [];

        $ignoredAttributes = array_merge(
            static::IGNORED_ATTRIBUTES,
            self::IGNORED_ATTRIBUTES
        );

        foreach ($ignoredAttributes as $attribute) {
            if (!in_array($attribute, MicroserviceRequest::getExpands())) {
                $attributes[] = $attribute;
            }
        }

        return $attributes;
    }
}
