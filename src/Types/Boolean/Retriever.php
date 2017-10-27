<?php

namespace Parameter\Types\Boolean;

use Parameter\Types\BaseValueRetriever;

class Retriever extends BaseValueRetriever
{
    public function getValue()
    {
        return (bool) $this->value;
    }
}