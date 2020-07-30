<?php

namespace App\Components;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * By this response we'll be able to sent array without format to Json
 *
 * Class MicroserviceResponse
 * @package App\Components
 */
class MicroserviceResponse extends Response
{
    /**
     * @var string|array
     */
    protected $content;

    /**
     * @inheritDoc
     */
    public function __construct($content = '', int $status = 200, array $headers = [])
    {
        $this->headers = new ResponseHeaderBag($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.0');
    }

    /**
     * @param array|string|null $content
     *
     * @return $this|MicroserviceResponse
     */
    public function setContent($content)
    {
        $this->content = $content ?? '';

        return $this;
    }

    /**
     * Gets the current response content.
     *
     * @return array|string|false
     */
    public function getContent()
    {
        return $this->content;
    }
}
