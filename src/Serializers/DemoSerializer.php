<?php
//
//namespace App\Serializers;
//
//use App\Components\MicroserviceRequest;
//use App\Serializers\Normalizers\NameConverter\DemoConverter;
//use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
//use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
//
///**
// * Class CitySerializer
// * @package App\Serializers
// */
//class DemoSerializer extends MicroserviceSerializer
//{
//    protected const IGNORED_ATTRIBUTES = [
//        'cities',
//        'fullTitle'
//    ];
//
//    /**
//     * @return NameConverterInterface|null
//     */
//    protected function getNameConverter(): ?NameConverterInterface
//    {
//        if (!in_array('attribute', MicroserviceRequest::getExpands())) {
//            return new DemoConverter();
//        }
//
//        return null;
//    }
//
//    /**
//     * @return array
//     */
//    protected function getContext(): array
//    {
//        if (!in_array('attribute', MicroserviceRequest::getExpands())) {
//            return array_merge(
//                [
//                    AbstractObjectNormalizer::CALLBACKS                  => [
//                        'attribute' => function ($innerObject) {
//                            return $innerObject->getId();
//                        },
//                    ]
//                ],
//                parent::getContext()
//            );
//        }
//
//        return parent::getContext();
//    }
//}
