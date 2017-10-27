<?php

namespace Parameter\Types\Textfield;

use Parameter\Types\BaseBuilder;

class Builder extends BaseBuilder
{
    public function buildValue()
    {
        return (string) trim($this->parameter->value);
    }
}