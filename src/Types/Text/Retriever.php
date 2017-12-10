<?php

namespace Paraman\Types\Text;

use Paraman\Types\BaseValueRetriever;

class Retriever extends BaseValueRetriever
{
    public function getValue()
    {
        return $this->value;
    }
}
