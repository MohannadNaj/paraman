<?php

namespace Parameter\Types\Text;

use Parameter\Types\BaseBuilder;

class Builder extends BaseBuilder
{
    public function buildValue()
    {
        return (string) trim($this->parameter->value);
    }
}