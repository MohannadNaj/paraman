<?php

namespace Paraman\Types\Integer;

use Paraman\Types\BaseRules;

class Rules extends BaseRules
{
    /**
     * @return array
     */
    public function newRules()
    {
        return ['value'=>'integer'];
    }

    public function updateRules()
    {
        return ['value'=>'integer'];
    }
}