<?php


namespace App\Http\ResponseBody;


class TextBody extends AbstractBody
{
    public function __toString(): string
    {
        return $this->value;
    }
}