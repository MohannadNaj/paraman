<?php

namespace Parameter\Tests\Model\CustomType;

use Parameter\Types\BaseBuilder;

class Builder extends BaseBuilder
{
    public function buildValue()
    {
        return substr($this->parameter->value, 0,2);
    }
}