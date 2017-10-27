<?php

namespace Parameter\Types;

use Parameter\ParametersManager;

abstract class BaseRules
{
    public function __construct()
    {
    }

    abstract public function newRules();

    abstract public function updateRules();

    public function getNewRules()
    {
        return $this->newRules() + $this->commonNewRules();
    }

    public function getUpdateRules()
    {
        return $this->updateRules() + $this->commonUpdateRules();
    }

    private function commonNewRules()
    {
        return [
            'name'=>'unique:parameters|required',
            'label' => 'required|max:255',
            'category_id' => 'integer|nullable',
            'type' => 'required|in:' . implode(ParametersManager::getSupportedTypes(),','),
        ];
    }

    private function commonUpdateRules()
    {
        return [
            'label' => 'max:255',
        ];
    }
}