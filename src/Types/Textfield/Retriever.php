<?php

namespace Parameter\Types\Textfield;

use Parameter\Types\BaseValueRetriever;

class Retriever extends BaseValueRetriever
{
    public function getValue()
    {
        return (string) $this->value;
    }
}