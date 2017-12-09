<?php

namespace Paraman\Types\File;

use Paraman\Types\BaseBuilder;

class Builder extends BaseBuilder
{
    public function buildValue()
    {
        return (string) $this->parameter->value;
    }
}