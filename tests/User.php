<?php

namespace Parameter\Tests;

use Illuminate\Foundation\Auth\User as AuthUser;

class User extends AuthUser
{
	private $authorizedToEdit;

	public function __construct($authorizedToEdit = true)
	{
		$this->authorizedToEdit = $authorizedToEdit;
	}

	public function canEditParameters() {
		return $this->authorizedToEdit;
	}
}
