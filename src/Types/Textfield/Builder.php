<?php

namespace Paraman\Types\Textfield;

use Paraman\Types\BaseBuilder;

class Builder extends BaseBuilder
{
    public function buildValue()
    {
        return (string) trim($this->parameter->value);
    }
}
