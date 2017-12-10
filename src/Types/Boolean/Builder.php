<?php

namespace Paraman\Types\Boolean;

use Paraman\Types\BaseBuilder;

class Builder extends BaseBuilder
{
    public function buildValue()
    {
        return (bool) $this->parameter->value;
    }
}
