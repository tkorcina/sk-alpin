<?php

namespace App\Components\Forms\ForgotPassword;

interface ForgotPasswordFormFactory
{
	public function create(): ForgotPasswordForm;
}
