<?php


namespace App\Http;


class Request
{
    private $url;

    public function __construct()
    {
        $this->setUrl();
    }

    private function setUrl() {
        $this->url = explode('?', $_SERVER['REQUEST_URI'])[0];
    }

    public function getUrl() {
        return $this->url;
    }

    public function getStringFromPost(string $key, $default = '') {
        return (string) $this->getRawFromPost($key, $default);
    }

    public function getIntFromPost(string $key, $default = 0) {
        return (int) $this->getRawFromPost($key, $default);
    }

    public function getFloatFromPost(string $key, $default = 0) {
        return (float) $this->getRawFromPost($key, $default);
    }

    public function getArrayFromPost(string $key, $default = []) {
        return (array) $this->getRawFromPost($key, $default);
    }

    public function getStringFromGet(string $key, $default = '') {
        return (string) $this->getRawFromGet($key, $default);
    }

    public function getIntFromGet(string $key, $default = 0) {
        return (int) $this->getRawFromGet($key, $default);
    }

    public function getFloatFromGet(string $key, $default = 0) {
        return (float) $this->getRawFromGet($key, $default);
    }

    public function getArrayFromGet(string $key, $default = []) {
        return (array) $this->getRawFromGet($key, $default);
    }
    
    private function getRawFromPost(string $key, $default = null) {
        return $_POST[$key] ?? $default;
    }

    private function getRawFromGet(string $key, $default = null) {
        return $_GET[$key] ?? $default;
    }
}