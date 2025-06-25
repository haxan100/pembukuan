<?php
defined('BASEPATH') or exit('No direct script access allowed');

$bu = base_url();
?>
<footer class="footer text-center">

	</div>
	<!-- END layout-wrapper -->
</footer>
</div>
<!-- End Page wrapper  -->
</div>
<!-- End Wrapper -->

</body>

<!-- Right Sidebar -->
<div class="right-bar">
	<div data-simplebar class="h-100">
		<div class="rightbar-title px-3 py-4">
			<a href="javascript:void(0);" class="right-bar-toggle float-end">
				<i class="mdi mdi-close noti-icon"></i>
			</a>
			<h5 class="m-0">Settings</h5>
		</div>

		<!-- Settings -->
		<hr class="mt-0" />
		<h6 class="text-center">Choose Layouts</h6>

		<div class="p-4">
			<div class="mb-2">
				<img src="<?= base_url("assets/") ?>assets/images/layouts/layout-1.jpg" class="img-fluid img-thumbnail" alt="">
			</div>
			<div class="form-check form-switch mb-3">
				<input type="checkbox" class="form-check-input theme-choice" id="light-mode-switch" checked />
				<label class="form-check-label" for="light-mode-switch">Light Mode</label>
			</div>

			<div class="mb-2">
				<img src="<?= base_url("assets/") ?>assets/images/layouts/layout-2.jpg" class="img-fluid img-thumbnail" alt="">
			</div>
			<div class="form-check form-switch mb-3">
				<input type="checkbox" class="form-check-input theme-choice" id="dark-mode-switch" data-bsStyle="assets/css/bootstrap-dark.min.css"
					data-appStyle="assets/css/app-dark.min.css" />
				<label class="form-check-label" for="dark-mode-switch">Dark Mode</label>
			</div>

		</div>

	</div> <!-- end slimscroll-menu-->
</div>
<!-- /Right-bar -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<script src="<?= base_url("assets/") ?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url("assets/") ?>assets/libs/metismenu/metisMenu.min.js"></script>
<script src="<?= base_url("assets/") ?>assets/libs/simplebar/simplebar.min.js"></script>
<script src="<?= base_url("assets/") ?>assets/libs/node-waves/waves.min.js"></script>


<!-- Peity chart-->
<script src="<?= base_url("assets/") ?>assets/libs/peity/jquery.peity.min.js"></script>

<!-- Plugin Js-->
<script src="<?= base_url("assets/") ?>assets/libs/chartist/chartist.min.js"></script>
<script src="<?= base_url("assets/") ?>assets/libs/chartist-plugin-tooltips/chartist-plugin-tooltip.min.js"></script>

<script src="<?= base_url("assets/") ?>assets/js/pages/dashboard.init.js"></script>

<script src="<?= base_url("assets/") ?>assets/js/app.js"></script>


<!-- Required datatable js -->
<script src="<?= base_url("assets/") ?>assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url("assets/") ?>assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="<?= base_url("assets/") ?>assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url("assets/") ?>assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/") ?>assets/libs/jszip/jszip.min.js"></script>
<script src="<?= base_url("assets/") ?>assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="<?= base_url("assets/") ?>assets/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="<?= base_url("assets/") ?>assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url("assets/") ?>assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url("assets/") ?>assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
<!-- Responsive examples -->
<script src="<?= base_url("assets/") ?>assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url("assets/") ?>assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

<!-- Datatable init js -->
<script src="<?= base_url("assets/") ?>assets/js/pages/datatables.init.js"></script>
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<!-- Froala Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/froala-editor@4.0.16/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css">

<!-- Froala Editor JS -->
<script src="https://cdn.jsdelivr.net/npm/froala-editor@4.0.16/js/froala_editor.pkgd.min.js"></script>

<script src="<?= base_url("assets/") ?>assets/js/sw.js"></script>
<!-- <button id="subscribeButton">Subscribe to Notifications</button> -->


</body>

</html>

<script>
	$(document).ready(function() {
		// loadNavbar()
		$('body').on('click', '#exit', function() {
			Swal.fire({
				title: 'Apakah Anda Yakin ?',
				text: "Anda Akan Keluar Dari Aplikasi Ini",
				icon: 'warning',
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
								console.log(e)
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


		$('body').on('click', '#reload', function() {
			loadSidebar();
		});

		function showSuccessAlert(message) {
			Swal.fire({
				icon: 'success',
				title: 'Berhasil',
				text: message,
				timer: 3000,
				timerProgressBar: true,
				showConfirmButton: false
			});
		}


	});
</script>

<button id="subscribeButton">Klik untuk Subscribe</button>

<script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-messaging.js"></script>

<script type="module">
	console.log('Service Worker mulai...');
	try {
		importScripts('https://www.gstatic.com/firebasejs/11.0.2/firebase-app.js');
		console.log('Firebase App berhasil dimuat.');

		importScripts('https://www.gstatic.com/firebasejs/11.0.2/firebase-messaging.js');
		console.log('Firebase Messaging berhasil dimuat.');
	} catch (err) {
		// console.error('Gagal memuat Firebase SDK:', err.message);
	}
	// console.log('Service Worker selesai.');

	// Import Firebase
	import {
		initializeApp
	} from "https://www.gstatic.com/firebasejs/11.0.2/firebase-app.js";
	import {
		getMessaging,
		getToken,
		onMessage
	} from "https://www.gstatic.com/firebasejs/11.0.2/firebase-messaging.js";

	navigator.serviceWorker.register('/kabar/firebase-messaging-sw.js')
		.then((registration) => {
			console.log('Service Worker berhasil didaftarkan:', registration.scope);
		})
		.catch((err) => {
			console.error('Service Worker gagal didaftarkan:', err.message);
		});


	// Konfigurasi Firebase
	const firebaseConfig = {
		apiKey: "AIzaSyBYX7Je9BK3QzDxmaFbh-jKzzIAw7h0SXs",
		authDomain: "plusminus-3d9e5.firebaseapp.com",
		projectId: "plusminus-3d9e5",
		storageBucket: "plusminus-3d9e5.firebasestorage.app",
		messagingSenderId: "773329432786",
		appId: "1:773329432786:web:3a272843f87fd78828fd0b",
		measurementId: "G-D2F4WHSCMT"
	};


	firebase.initializeApp(firebaseConfig);
	const messaging = firebase.messaging();
	// Daftarkan Service Worker
	if ('serviceWorker' in navigator) {
		navigator.serviceWorker.register('/kabar/firebase-messaging-sw.js')
			.then((registration) => {
				messaging.useServiceWorker(registration);
				// console.log('Service Worker berhasil didaftarkan:', registration.scope);

				// Meminta izin notifikasi dari pengguna
				return messaging.getToken({
					vapidKey: 'BLcJZH1gg4rJKvbJxTNMZoMJmkQkzWCA8mkjllEaEddI2cZube68dP8v3nMutkHzPv0pC7MfKUT_kjjClrMZqjI'
				});
			})
			.then((token) => {
				if (token) {
					console.log('FCM Token:', token);
					// Masukkan token ke input hidden atau kirim ke server
					insert_token(token); // Fungsi untuk menyimpan token di server
					convertFCMTokenToUUID(token).then((uuid) => {
						console.log("FCM Token diubah menjadi UUID:", uuid);

						// Kirim UUID ke server
						insert_token(uuid); // Fungsi untuk menyimpan token di server
						// document.getElementById("token").value = uuid;
					});

					document.getElementById('token').value = token;
				} else {
					console.warn('Token FCM tidak tersedia. Pastikan izin diberikan.');
				}
			})
			.catch((error) => {
				// console.error('Gagal mendapatkan token FCM:', error);
			});
	} else {
		console.error('Service Worker tidak didukung di browser ini.');
	}

	// Fungsi untuk menangani notifikasi saat aplikasi aktif
	messaging.onMessage((payload) => {
		console.log('Notifikasi diterima:', payload);
		alert(`${payload.notification.title}\n${payload.notification.body}`);
	});

	function convertFCMTokenToUUID(fcmToken) {
		// Buat hash dari FCM Token
		const hash = crypto.subtle.digest("SHA-256", new TextEncoder().encode(fcmToken));
		return hash.then((buffer) => {
			const hex = Array.from(new Uint8Array(buffer))
				.map((byte) => byte.toString(16).padStart(2, "0"))
				.join("");

			// Konversi hash menjadi UUID v4
			const uuid = `${hex.slice(0, 8)}-${hex.slice(8, 12)}-4${hex.slice(13, 16)}-${(
            parseInt(hex.slice(16, 18), 16) & 0x3f |
            0x80
        ).toString(16)}${hex.slice(18, 20)}-${hex.slice(20, 32)}`;
			return uuid;
		});
	}


	function insert_token(token) {
		var url = '<?= site_url('admin/set_token_mitra'); ?>';
		$.ajax({
			data: {
				'token': token
			},
			url: url,
			type: "POST",
			success: function(response) {

			}
		});
	}

	messaging.onTokenRefresh(function() {
		messaging.getToken()
			.then(function(newtoken) {
				// insert_token(newtoken);
				insert_token(token)
				x.play();


				console.log("New Token : " + newtoken);
			})
			.catch(function(reason) {
				console.log(reason);
			})
	})

	messaging.onMessage((payload) => {
		const notificationOption = {
			body: payload.notification.body,
			icon: payload.notification.icon
		};
		var notification = new Notification(payload.notification.title, notificationOption);

		console.log("notification=>", notification, "||", payload)
		// loadSidebar()
		x.play();


		notification.onclick = function(ev) {
			ev.preventDefault();
			localStorage.setItem("status", "4");
			window.open(payload.notification.click_action, 'www.google.comsss');
			notification.close();
			x.play();

		}
	})
</script>
<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
<script>
	$(document).ready(function() {

		window.OneSignalDeferred = window.OneSignalDeferred || [];
		OneSignalDeferred.push(async function(OneSignal) {
			console.log("Inisialisasi OneSignal...");


			// Inisialisasi OneSignal
			await OneSignal.init({
				appId: "29c768d7-822e-4003-bfa1-7e8f5b623b63", // Ganti dengan App ID Anda
				allowLocalhostAsSecureOrigin: true, // Untuk pengujian di localhost

				notifyButton: {
					enable: true, // Menampilkan tombol subscribe
				},
			});

			console.log("OneSignal berhasil diinisialisasi.");
			$('#subscribeButton').click(function(e) {
				e.preventDefault();
				alert("s")
				//Example combining with push subscription change event
				function pushSubscriptionChangeListener(event) {
					if (event.current.token) {
						console.log(event.current);

						console.log(`The push subscription has received a token!`);
						//this is a good place to call OneSignal.login and pass in your user ID
						OneSignal.login("external_id");
					}
				}
				OneSignalDeferred.push(function() {
					OneSignal.User.addEventListener('change', function(event) {
						console.log('change', {
							event
						});
					});
				});

				OneSignalDeferred.push(function(OneSignal) {
					console.log(OneSignal.User._currentUser);

					OneSignal.User.PushSubscription.addEventListener("change", pushSubscriptionChangeListener);
				});
			});

		});
	});
</script>

<!-- Tombol Subscribe -->
