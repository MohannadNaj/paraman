<?php

namespace Parameter\Types\Boolean;

use Parameter\Types\BaseBuilder;

class Builder extends BaseBuilder
{
    public function buildValue()
    {
        return (bool) $this->parameter->value;
    }
}