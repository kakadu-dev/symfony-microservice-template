<?php
namespace App\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Class EnumType
 * @package App\DBAL
 */
class EnumType extends Type
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var array
     */
    protected array $values = [];

    /**
     * @param array            $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $values = array_map(function($val) { return "'".$val."'"; }, array_keys($this->values));

        return "ENUM(".implode(", ", $values).")";
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!array_key_exists($value, $this->values)) {
            throw new \InvalidArgumentException("Invalid '".$this->name."' value.");
        }
        return $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param AbstractPlatform $platform
     *
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public static function getValues(): array
    {
        return (new static())->values;
    }

    /**
     * @return array
     */
    public static function getKeys(): array
    {
        return array_keys((new static())->values);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public static function getValue(string $key)
    {
        $instance = new static();

        if (!array_key_exists($key, $instance->values)) {
            throw new \InvalidArgumentException("Invalid '".$key."' key.");
        }

        return $instance->values[$key];
    }
}
