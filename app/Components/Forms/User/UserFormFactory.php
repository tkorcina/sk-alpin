<?php

namespace App\Components\Forms\User;

interface UserFormFactory
{
	public function create(): UserForm;
}
