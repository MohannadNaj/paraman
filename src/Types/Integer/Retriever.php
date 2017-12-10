<?php

namespace Paraman\Types\Integer;

use Paraman\Types\BaseValueRetriever;

class Retriever extends BaseValueRetriever
{
    public function getValue()
    {
        return (int) $this->value;
    }
}
