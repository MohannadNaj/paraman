<?php

namespace Paraman\Types\Boolean;

use Paraman\Types\BaseRules;

class Rules extends BaseRules
{
    /**
     * @return array
     */
    public function newRules()
    {
        return ['value'=>'boolean'];
    }

    public function updateRules()
    {
        return ['value'=>'boolean'];
    }
}