<?php
//
//namespace App\Serializers\Normalizers\NameConverter;
//
//use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
//
///**
// * Class DemoConverter
// * @package App\Serializers\Normalizers\NameConverter
// */
//class DemoConverter implements NameConverterInterface
//{
//    /**
//     * @param string $propertyName
//     *
//     * @return string
//     */
//    public function normalize(string $propertyName): string
//    {
//        if ($propertyName === 'attribute') {
//            return 'newAttributeName';
//        }
//
//        return $propertyName;
//    }
//
//    /**
//     * @param string $propertyName
//     *
//     * @return string
//     */
//    public function denormalize(string $propertyName): string
//    {
//        return $propertyName;
//    }
//}
