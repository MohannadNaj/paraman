<?php

namespace Parameter;

use Parameter\ParametersManager;

trait ParametersModelTrait {

    public function getValue()
    {
        return $this->value;
    }

	public function buildValue()
	{
		$parameterBuilderClassName = ParametersManager::BuilderClassPath($this->type);
		$parameterBuilder = new $parameterBuilderClassName($this);
		$parameterBuilder->build();
	}

	public function buildMetaValue()
	{
		$parameterBuilderClassName = ParametersManager::BuilderClassPath($this->type);
		$parameterBuilder = new $parameterBuilderClassName($this);
		$parameterBuilder->buildMeta();
	}

	public function getValueAttribute($value)
	{
		$parameterRetrieverClassName = ParametersManager::RetrieverClassPath($this->type);
		$parameterRetriever = new $parameterRetrieverClassName($value);

		return $parameterRetriever->getValue();
	}

	public static function getColumns()
	{
		$instance = new self;
		return $instance->getConnection()->getSchemaBuilder()->getColumnListing($instance->getTable());
	}
}