<?php

namespace Parameter\Types\Integer;

use Parameter\Types\BaseBuilder;

class Builder extends BaseBuilder
{
    public function buildValue()
    {
        return (int) $this->parameter->value;
    }
}