<?php

namespace Parameter\Types\_Default;

use Parameter\Types\BaseBuilder;

class Builder extends BaseBuilder
{
    public function buildValue()
    {
        return (string) trim($this->parameter->value);
    }
}