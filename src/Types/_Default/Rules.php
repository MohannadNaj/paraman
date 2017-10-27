<?php
namespace Parameter\Types\_Default;

use Parameter\Types\BaseRules;

class Rules extends BaseRules
{
    /**
     * @return array
     */
    public function newRules()
    {
        return ['value'=>''];
    }

    public function updateRules()
    {
        return ['value'=>''];
    }
}