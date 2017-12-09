<?php

namespace Paraman\Types\Textfield;

use Paraman\Types\BaseValueRetriever;

class Retriever extends BaseValueRetriever
{
    public function getValue()
    {
        return (string) $this->value;
    }
}