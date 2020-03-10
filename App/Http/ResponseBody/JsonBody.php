<?php


namespace App\Http\ResponseBody;


class JsonBody extends AbstractBody
{
    public function __toString(): string
    {
        return json_encode($this->value);
    }
}