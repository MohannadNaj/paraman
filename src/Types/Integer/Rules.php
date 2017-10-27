<?php

namespace Parameter\Types\Integer;

use Parameter\Types\BaseRules;

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