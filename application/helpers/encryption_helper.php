<?php
defined('BASEPATH') or exit('No direct script access allowed');

function encrypt_password($password)
{
    $encryption_key = 'haxan_sb'; // Ganti dengan kunci rahasia Anda
    $cipher = 'AES-256-CBC';
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($password, $cipher, $encryption_key, OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $encryption_key, true);
    return base64_encode($iv . $hmac . $ciphertext_raw);
}

function decrypt_password($encrypted_password)
{
    $encryption_key = 'haxan_sb'; // Kunci rahasia harus sama dengan yang digunakan di `encrypt_password`
    $cipher = 'AES-256-CBC';
    $c = base64_decode($encrypted_password);
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    $original_password = openssl_decrypt($ciphertext_raw, $cipher, $encryption_key, OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $encryption_key, true);
    if (hash_equals($hmac, $calcmac)) {
        return $original_password;
    }
    return false;
}
if (!function_exists('check_login')) {
    function check_login()
    {
        $CI = &get_instance();
        if (!$CI->session->userdata('user_id') || !$CI->session->userdata('id_role')) {
            redirect('Admin/login');
        }
    }
}

if (!function_exists('check_access')) {
    function check_access($required_role)
    {
        $CI = &get_instance();

        // Ambil role user dari session
        $user_role = $CI->session->userdata('id_role');

        // Jika tidak ada role, redirect ke halaman login
        if (!$user_role) {
            redirect('Admin/login');
        }

        // Ambil detail role dari database (misal RoleModel)
        $CI->load->model('RoleModel');
        $role_data = $CI->RoleModel->findById("role",$user_role);

        // Jika role tidak ditemukan, redirect ke halaman index
        if (!$role_data) {
            redirect('admin');
        }

        // Cek apakah role user memiliki akses ke halaman yang diminta
        if (!isset($role_data->$required_role) || $role_data->$required_role != 1) {
            // Jika tidak memiliki akses, redirect ke halaman index
            redirect('admin');
        }
    }
}
if (!function_exists('check_access_sidebar')) {
    function check_access_sidebar($required_role)
    {
        $CI = &get_instance();

        // Ambil role user dari session
        $user_role = $CI->session->userdata('id_role');
        if (!$user_role) return false;

        // Ambil detail role dari database (misal RoleModel)
        $CI->load->model('RoleModel');
        $role_data = $CI->RoleModel->findById("role",$user_role);

        // Jika role tidak ditemukan, kembalikan false
        if (!$role_data) return false;

        // Cek apakah role memiliki akses ke menu
        return isset($role_data->$required_role) && $role_data->$required_role == 1;
    }
	function encrypt_password_mitra($password)
	{
		$encryption_key = 'haxan_sb_mitra'; // Ganti dengan kunci rahasia Anda
		$cipher = 'AES-256-CBC';
		$ivlen = openssl_cipher_iv_length($cipher);
		$iv = openssl_random_pseudo_bytes($ivlen);
		$ciphertext_raw = openssl_encrypt($password, $cipher, $encryption_key, OPENSSL_RAW_DATA, $iv);
		$hmac = hash_hmac('sha256', $ciphertext_raw, $encryption_key, true);
		return base64_encode($iv . $hmac . $ciphertext_raw);
	}

	function decrypt_password_mitra($encrypted_password)
	{
		$encryption_key = 'haxan_sb_mitra'; // Kunci rahasia harus sama dengan yang digunakan di `encrypt_password`
		$cipher = 'AES-256-CBC';
		$c = base64_decode($encrypted_password);
		$ivlen = openssl_cipher_iv_length($cipher);
		$iv = substr($c, 0, $ivlen);
		$hmac = substr($c, $ivlen, $sha2len = 32);
		$ciphertext_raw = substr($c, $ivlen + $sha2len);
		$original_password = openssl_decrypt($ciphertext_raw, $cipher, $encryption_key, OPENSSL_RAW_DATA, $iv);
		$calcmac = hash_hmac('sha256', $ciphertext_raw, $encryption_key, true);
		if (hash_equals($hmac, $calcmac)) {
			return $original_password;
		}
		return false;
	}
	function encrypt_password_toko($password)
	{
		$encryption_key = 'haxan_sb_toko'; // Ganti dengan kunci rahasia Anda
		$cipher = 'AES-256-CBC';
		$ivlen = openssl_cipher_iv_length($cipher);
		$iv = openssl_random_pseudo_bytes($ivlen);
		$ciphertext_raw = openssl_encrypt($password, $cipher, $encryption_key, OPENSSL_RAW_DATA, $iv);
		$hmac = hash_hmac('sha256', $ciphertext_raw, $encryption_key, true);
		return base64_encode($iv . $hmac . $ciphertext_raw);
	}

	function decrypt_password_toko($encrypted_password)
	{
		$encryption_key = 'haxan_sb_toko'; // Kunci rahasia harus sama dengan yang digunakan di `encrypt_password`
		$cipher = 'AES-256-CBC';
		$c = base64_decode($encrypted_password);
		$ivlen = openssl_cipher_iv_length($cipher);
		$iv = substr($c, 0, $ivlen);
		$hmac = substr($c, $ivlen, $sha2len = 32);
		$ciphertext_raw = substr($c, $ivlen + $sha2len);
		$original_password = openssl_decrypt($ciphertext_raw, $cipher, $encryption_key, OPENSSL_RAW_DATA, $iv);
		$calcmac = hash_hmac('sha256', $ciphertext_raw, $encryption_key, true);
		if (hash_equals($hmac, $calcmac)) {
			return $original_password;
		}
		return false;
	}
	if (!function_exists('check_login_mitra')) {
		function check_login_mitra()
		{
			$CI = &get_instance(); // Mendapatkan instance CI
			$CI->load->library('session'); // Memastikan library session sudah dimuat
	
			// Cek apakah user_id_mitra tersedia di sesi
			if (!$CI->session->userdata('user_id_mitra')) {
				// Jika tidak ada sesi user_id_mitra, arahkan ke halaman login mitra
				redirect('Mitra/login');
			}
		}
	}
	
}

