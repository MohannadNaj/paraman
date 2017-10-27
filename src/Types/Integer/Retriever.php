<?php

namespace Parameter\Types\Integer;

use Parameter\Types\BaseValueRetriever;

class Retriever extends BaseValueRetriever
{
    public function getValue()
    {
        return (int) $this->value;
    }
}