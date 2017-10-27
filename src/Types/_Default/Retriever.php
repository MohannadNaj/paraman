<?php

namespace Parameter\Types\_Default;

use Parameter\Types\BaseValueRetriever;

class Retriever extends BaseValueRetriever
{
    public function getValue()
    {
        return (string) $this->value;
    }
}