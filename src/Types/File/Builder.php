<?php

namespace Parameter\Types\File;

use Parameter\Types\BaseBuilder;

class Builder extends BaseBuilder
{
    public function buildValue()
    {
        return (string) $this->parameter->value;
    }
}