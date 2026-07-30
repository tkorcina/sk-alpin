<?php

namespace App\Components\Forms\SignIn;

interface SignInFormFactory
{
	public function create(): SignInForm;
}
