<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['ENVIRONMENT'] = 'prod'; // dev | loc | prod
$config['google_redirect_uri'] = 'http://localhost/kabar/api/google_callback';

$config['google_client_id'] = '773329432786-06h6n3t2ti3q8q2bl50o5irjpcdt9p83.apps.googleusercontent.com'; // Ganti dengan Google Client ID Anda
$config['google_client_secret'] = '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'; // Ganti dengan Google Client Secret Anda
// $config['google_redirect_uri'] = base_url('api/google_callback'); // Endpoint callback
$config['google_scopes'] = 'openid email profile';
$config['wa_admin'] = '6289602350857';
$config['version_ios'] = '1';
$config['version_android'] ='1';
$config['socket_server_url'] = 'http://[2a00:f48:1000:55a::1]:3000/';

$config['countdown'] = 30;


$config['tampilGambar'] = false;
