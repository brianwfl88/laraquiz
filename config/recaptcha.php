<?php

return [
	'key' => env('GOOGLE_RECAPTCHA_KEY'),
	'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
	'post_url' => 'https://www.google.com/recaptcha/api/siteverify',
	'ssl_verify' => FALSE
];