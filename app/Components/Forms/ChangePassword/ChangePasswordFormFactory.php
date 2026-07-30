<?php

namespace App\Components\Forms\ChangePassword;

interface ChangePasswordFormFactory
{
	public function create(): ChangePasswordForm;
}
