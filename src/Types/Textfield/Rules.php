<?php

namespace Paraman\Types\Textfield;

use Paraman\Types\BaseRules;

class Rules extends BaseRules
{
    /**
     * @return array
     */
    public function newRules()
    {
        return ['value'=>'max:255'];
    }

    public function updateRules()
    {
        return ['value'=>'max:255'];
    }
}
