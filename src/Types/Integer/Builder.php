<?php

namespace Paraman\Types\Integer;

use Paraman\Types\BaseBuilder;

class Builder extends BaseBuilder
{
    public function buildValue()
    {
        return (int) $this->parameter->value;
    }
}