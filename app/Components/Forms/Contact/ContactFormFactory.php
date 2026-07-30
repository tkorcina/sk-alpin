<?php

namespace App\Components\Forms\Contact;

interface ContactFormFactory
{
	public function create(): ContactForm;
}
