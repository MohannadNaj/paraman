<?php

namespace Paraman\Tests\Model\CustomType;

use Paraman\Types\BaseBuilder;

class Builder extends BaseBuilder
{
    public function buildValue()
    {
        return substr($this->parameter->value, 0, 2);
    }
}
