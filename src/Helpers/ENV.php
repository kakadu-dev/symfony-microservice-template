<?php

namespace App\Helpers;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * Class ENV
 * @package App\Helpers
 */
class ENV
{
    /**
     * @var string
     */
    private string $path;

    /**
     * ENV constructor.
     *
     * @param ContainerBagInterface $bag
     */
    public function __construct(ContainerBagInterface $bag)
    {
        $this->path = $bag->get('kernel.project_dir') . DIRECTORY_SEPARATOR . '.env';
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function put(string $key, $value): void
    {
        $data       = $this->getData();
        $data[$key] = $value;

        file_put_contents(
            $this->path,
            $this->getFormatted($data)
        );
    }

    /**
     * @param string $key
     * @param null   $default
     *
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        if (!array_key_exists($key, ($data = $this->getData()))) {
            return $default;
        }

        return $data[$key];
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function getFormatted(array $data): string
    {
        $result = '';
        foreach ($data as $key => $value) {
            if (is_int($key)) {
                $result .= $value . "\n";
                continue;
            }

            $result .= $key . '=' . $value . "\n";
        }

        return $result;
    }

    /**
     * @return array
     */
    private function getData(): array
    {
        $result = [];
        foreach (explode("\n", $this->getFile()) as $key => $value) {
            $item = trim($value);
            if ($item === '') {
                continue;
            }

            if (substr($value, 0, 1) === '#') {
                $result[] = $value;
                continue;
            }

            if (($pos = strpos($value, '=')) === false) {
                $result[] = $value;
                continue;
            }

            $key          = substr($value, 0, $pos);
            $value        = substr($value, $pos + 1);
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * @return false|string
     */
    private function getFile(): string
    {
        if (!file_exists($this->path)) {
            $this->makeFile();
        }

        return file_get_contents($this->path);
    }

    /**
     * create configuration file
     */
    public function makeFile(): void
    {
        @mkdir(dirname($this->path), 0777, true);
    }
}
