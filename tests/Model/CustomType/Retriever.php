<?php

namespace Paraman\Tests\Model\CustomType;

use Paraman\Types\BaseValueRetriever;

class Retriever extends BaseValueRetriever
{
    public function getValue()
    {
        return (string) $this->value;
    }
}