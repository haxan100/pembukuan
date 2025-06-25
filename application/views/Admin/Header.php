<!doctype html>
<html lang="en">
<script>
	var base_url = "<?= base_url(); ?>";
	<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	$title = array_key_exists('title', $header) ? $header['title'] : $ci->namaAplikasi();
	$page = array_key_exists('page', $header) ? $header['page'] : 'master_admin';
	$js = array_key_exists('js', $header) ? $header['js'] : array();
	$bu = base_url();
	$this->load->config('custom_config'); // Load config custom (jika belum otomatis)
	$tampilGambar = $this->config->item('tampilGambar');

	if ($tampilGambar) {
		$gambar = base_url("assets/images/logo-light.png");
	} else {
		$gambar = base_url("assets/images/logo-placeholder.png"); // Bisa diganti jadi default atau dibiarkan kosong
	}
	?>
</script>

<head>

	<meta charset="utf-8">
	<title>Dashboard | Admin - <?= $title ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="Premium Multipurpose Admin & Dashboard Template" name="description">
	<meta content="Themesbrand" name="author">
	<!-- App favicon -->
	<link rel="shortcut icon" href="<?= base_url("assets/") ?>assets/images/favicon.ico">

	<link href="<?= base_url("assets/") ?>assets/libs/chartist/chartist.min.css" rel="stylesheet">

	<!-- Bootstrap Css -->
	<link href="<?= base_url("assets/") ?>assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css">
	<!-- Icons Css -->
	<link href="<?= base_url("assets/") ?>assets/css/icons.min.css" rel="stylesheet" type="text/css">
	<!-- App Css-->
	<link href="<?= base_url("assets/") ?>assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css">

</head>
<!-- JAVASCRIPT -->
<script src="<?= base_url("assets/") ?>assets/libs/jquery/jquery.min.js"></script>

<body data-sidebar="dark">
	<style>
		.btn-same-width {
			width: 150px;
			/* Menentukan lebar tombol, bisa sesuaikan */
			display: inline-flex;
			/* Menjaga tombol tetap dalam baris */
			justify-content: center;
			/* Menyejajarkan konten tombol */
			align-items: center;
			/* Menjaga ikon dan teks sejajar di tengah */
		}

		/* Styling umum untuk tabel */
		#datatable {
			width: 100%;
			border-collapse: collapse;
			font-family: 'Arial', sans-serif;
			font-size: 14px;
			text-align: left;
			background-color: #ffffff;
			box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
			border-radius: 8px;
			overflow: hidden;
		}

		/* Styling header tabel */
		#datatable thead {
			background-color: #007bff;
			color: white;
		}

		#datatable thead th {
			padding: 12px;
			text-transform: uppercase;
			font-weight: bold;
		}

		/* Styling body tabel dengan warna selang-seling */
		#datatable tbody tr:nth-child(odd) {
			background-color: #f9f9f9;
			/* Putih sedikit lebih gelap */
		}

		#datatable tbody tr:nth-child(even) {
			background-color: #ffffff;
			/* Putih biasa */
		}

		#datatable tbody tr:hover {
			background-color: #e0f3ff;
			/* Efek hover */
			transition: background 0.3s ease-in-out;
		}

		/* Styling sel data */
		#datatable tbody td {
			padding: 10px;
			color: #333;
		}

		/* Styling tombol aksi */
		.btn-edit {
			background-color: #ffc107;
			color: black;
			border: none;
			padding: 6px 12px;
			font-weight: bold;
			border-radius: 4px;
			cursor: pointer;
		}

		.btn-hapus {
			background-color: #dc3545;
			color: white;
			border: none;
			padding: 6px 12px;
			font-weight: bold;
			border-radius: 4px;
			cursor: pointer;
		}

		.btn-detail {
			background-color: #007bff;
			color: white;
			border: none;
			padding: 6px 12px;
			font-weight: bold;
			border-radius: 4px;
			cursor: pointer;
		}

		/* Hover efek untuk tombol */
		.btn-edit:hover {
			background-color: #e0a800;
		}

		.btn-hapus:hover {
			background-color: #c82333;
		}

		.btn-detail:hover {
			background-color: #0056b3;
		}

		/* Styling pagination DataTables */
		.dataTables_wrapper .dataTables_paginate .paginate_button {
			padding: 5px 10px;
			margin: 2px;
			border-radius: 4px;
			border: 1px solid #007bff;
			color: #007bff !important;
			background-color: white;
			transition: background 0.3s ease-in-out, color 0.3s ease-in-out;
		}

		.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
			background-color: #007bff;
			color: white !important;
		}

		/* Styling untuk memastikan tabel responsif */
		@media (max-width: 768px) {
			#datatable {
				display: block;
				overflow-x: auto;
			}
		}
	</style>



	<!-- Begin page -->
	<div id="layout-wrapper">
		<header id="page-topbar">
			<div class="navbar-header">
				<div class="d-flex">
					<!-- LOGO -->
					<div class="navbar-brand-box">
						<a href="index.html" class="logo logo-dark">
							<span class="logo-sm">
								<img src="<?= base_url("assets/") ?>assets/images/logo-sm.png" alt="" height="22">
							</span>
							<span class="logo-lg">
								<img src="<?= base_url("assets/") ?>assets/images/logo-dark.png" alt="" height="17">
							</span>
						</a>

						<a href="index.html" class="logo logo-light">
							<span class="logo-sm">
								<img src="<?= base_url("assets/") ?>assets/images/logo-sm.png" alt="" height="22">
							</span>
							<span class="logo-lg">
								<div style="text-align: center; margin-bottom: 30px;">
									<?php if ($tampilGambar): ?>
										<img src="<?= $gambar ?>" alt="Logo" style="max-width: 200px;">
									<?php else: ?>
										<img src="<?= $gambar ?>" alt="Logo Default" style="max-width: 100px; opacity: 0.4;">
										<!-- atau kosongkan saja: -->
										<!-- <p style="font-style: italic; color: #888;">Logo tidak ditampilkan</p> -->
									<?php endif; ?>
								</div>
							</span>
						</a>
					</div>

					<button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
						<i class="mdi mdi-menu"></i>
					</button>

				</div>

				<div class="d-flex">
					<div class="dropdown d-inline-block d-lg-none ms-2">
						<button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
							data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="mdi mdi-magnify"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
							aria-labelledby="page-header-search-dropdown">

							<form class="p-3">
								<div class="form-group m-0">
									<div class="input-group">
										<input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
										<div class="input-group-append">
											<button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="dropdown d-none d-lg-inline-block">
						<button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
							<i class="mdi mdi-fullscreen"></i>
						</button>
					</div>

					<div class="dropdown d-inline-block">
						<button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
							data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<img class="rounded-circle header-profile-user" src="<?= base_url("assets/") ?>assets/images/users/user-4.jpg"
								alt="Header Avatar">
						</button>
						<div class="dropdown-menu dropdown-menu-end">
							<!-- item-->
							<a class="dropdown-item" href="#"><i class="mdi mdi-account-circle font-size-17 align-middle me-1"></i> <?= $this->session->userdata('username'); ?></a>
							<a class="dropdown-item text-danger" id="exit"><i class="bx bx-power-off font-size-17 align-middle me-1 text-danger"></i> Logout</a>
						</div>
					</div>

					<div class="dropdown d-inline-block">
						<button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
							<i class="mdi mdi-cog-outline"></i>
						</button>
					</div>

				</div>
			</div>
		</header>

		<?php
		defined('BASEPATH') or exit('No direct script access allowed');
		$bu = base_url();
		$obj['ci'] = $ci;
		$obj['header'] = array(

			'title' => isset($pageTitle) ? $pageTitle : 'Plus Minus',
			'page' => isset($page) ? $page : 'index',
		);
		$ci->load->view('Admin/Sidebar', $obj);
		?>

		<script>
			$(document).ready(function() {

				$('body').on('click', '#exit', function() {
					Swal.fire({
						title: 'Apakah Anda Yakin ?',
						text: "Anda Akan Keluar Dari Aplikasi Ini",
						icon: 'question',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, Keluar!'
					}).then((result) => {
						if (result.isConfirmed) {
							$.ajax({
								type: "POST",
								url: "<?= $bu ?>Admin/logout",
								success: function(e) {
									if (!e.error) {
										// console.log(e)
										Swal.fire(
											'Berhasil !',
											'Anda Akan Dialihkan Ke Halaman Login',
											'success'
										)
										setTimeout(() => {
											window.location = '<?= $bu ?>Admin/Login';
										}, 2000);

									}
								}
							});
						}

					})

				});


			});
		</script>
