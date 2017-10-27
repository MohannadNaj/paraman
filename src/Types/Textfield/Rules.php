<?php
namespace Parameter\Types\Textfield;

use Parameter\Types\BaseRules;

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