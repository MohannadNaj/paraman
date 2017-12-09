<?php

namespace Paraman\Types\_Default;

use Paraman\Types\BaseValueRetriever;

class Retriever extends BaseValueRetriever
{
    public function getValue()
    {
        return (string) $this->value;
    }
}