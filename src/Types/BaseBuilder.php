<?php

namespace Parameter\Types;

use Parameter\Parameter;

abstract class BaseBuilder
{
    protected $loggableFields = ['value','label','category_id'];
    protected $parameter;

    public function __construct(Parameter & $parameter)
    {
        $this->parameter = & $parameter;
    }

    public abstract function buildValue();

    public function build()
    {
        $this->parameter->value = $this->buildValue();
    }

    public function buildMeta()
    {
        $parameter = & $this->parameter;
        $meta = $parameter->meta;

        if(! $meta)
            $meta = [];

        $original = collect($parameter->getOriginal())->only($this->loggableFields);

        $dirtyFields = $parameter->getDirty();

        foreach($dirtyFields as $key => $value) {
            if(is_array($value)) {
                $dirtyFields[$key] = json_encode($value);
            }
        }

        $diff = collect($dirtyFields)->only($this->loggableFields)->diffAssoc($original)->toArray();

        foreach($diff as $key => $value)
        {
            $meta['logs'][] = [
                'old'   => $original[$key],
                'new'   => $value,
                'auth_id' => auth()->id(),
                'field' => $key,
                'date'  => \Carbon\Carbon::now()->toDateTimeString()];
        }

        $parameter->meta = $meta;
    }
}