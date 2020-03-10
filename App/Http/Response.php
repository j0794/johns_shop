<?php


namespace App\Http;


use App\Http\ResponseBody\AbstractBody;

class Response
{
    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var AbstractBody
     */
    private $body;

    /**
     * @param string $name
     * @param string $value
     */
    public function setHeader(string $name, string $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * @param AbstractBody $body
     */
    public function setBody(AbstractBody $body)
    {
        $this->body = $body;
    }

    public function send()
    {
        foreach ($this->headers as $header => $value) {
            header("$header: $value");
        }
        if (!is_null($this->body)) {
            echo (string) $this->body;
        }
    }

    /**
     * @param string $url
     */
    public function redirect(string $url)
    {
        $this->setHeader('Location', $url);
    }
}