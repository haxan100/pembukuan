<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['google_client_id'] = '773329432786-06h6n3t2ti3q8q2bl50o5irjpcdt9p83.apps.googleusercontent.com'; // Ganti dengan Google Client ID Anda
$config['google_client_secret'] = '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'; // Ganti dengan Google Client Secret Anda
$config['google_redirect_uri'] = base_url('api/google_callback'); // Endpoint callback
$config['google_scopes'] = 'openid email profile';
