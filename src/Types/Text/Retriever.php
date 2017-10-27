<?php

namespace Parameter\Types\Text;

use Parameter\Types\BaseValueRetriever;

class Retriever extends BaseValueRetriever
{
    public function getValue()
    {
        return $this->value;
    }
}