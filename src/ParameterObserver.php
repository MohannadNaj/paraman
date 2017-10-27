<?php

namespace Parameter;

class ParameterObserver {

	public function saving(Parameter $parameter)
	{
		$parameter->buildValue();
	}

	public function saved(Parameter $parameter)
	{
		new ParametersSingleton();
	}

	public function updating(Parameter $parameter)
	{
		$parameter->buildMetaValue();
	}

	public function updated(Parameter $parameter)
	{

	}

	public function deleting(Parameter $parameter)
	{

	}

	public function deleted(Parameter $parameter)
	{
		new ParametersSingleton();
	}

}