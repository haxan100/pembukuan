
<style>
  .permissions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* Bagi menjadi 2 kolom */
    gap: 10px; /* Jarak antar elemen */
  }

  .permissions-grid label {
    display: flex;
    align-items: center;
  }
</style>

<div class="main-content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Manajemen Role</h4>
					<p class="card-title-desc">
						DataTables untuk mengelola Role Admin dengan fitur server-side processing. Anda dapat menambah, mengedit, dan menghapus role di sini.
					</p>
					<button class="btn btn-primary mb-3" data-bs-toggle="modal" id="addRoleButton" data-bs-target="#addRoleModal">
						<i class="fas fa-plus"></i> Tambah Role
					</button>
					<table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
						<thead>
							<tr>
								<th>No</th>
								<th>Role Name</th>
								<th>Created At</th>
								<th>Updated At</th>
								<th>Jumlah Pengguna</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="6" class="text-center">Loading data...</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div> <!-- end col -->
	</div>

	<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg"> <!-- Tambahkan modal-lg untuk memperbesar dialog -->
		<form id="roleForm">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="roleModalLabel">Edit Role</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="role_id" name="id">
					<div class="mb-3">
						<label for="role_name" class="form-label">Role Name</label>
						<input type="text" class="form-control" id="role_name" name="role_name" required>
					</div>
					<div class="mb-3">
						<label for="permissions-container" class="form-label">Permissions</label>
						<div id="permissions-container" class="permissions-grid">
							<!-- Checkbox akan dimuat secara dinamis -->
						</div>
						</div>

						<style>
						.permissions-grid {
							display: grid;
							grid-template-columns: repeat(2, 1fr); /* Membagi menjadi 2 kolom */
							gap: 10px; /* Jarak antar elemen checkbox */
						}

						.permissions-grid label {
							display: flex;
							align-items: center;
						}
						</style>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Simpan</button>
					</div>
				</div>
			</form>
		</div>
	</div>



</div>
<?php
?>

<script>
	$(document).ready(function() {
		$('#addRoleButton').on('click', function() {
			$('#roleModalLabel').text('Tambah Role');
			$('#roleForm')[0].reset();
			$('#role_id').val('');
			$('#roleModal').modal('show');
            loadPermissions();

		});
		$(document).on('click', '.btn-edit', function () {
			const id = $(this).data('id_role'); // Ambil ID role dari tombol

			// Ambil data role berdasarkan ID
			$.ajax({
				url: '<?= base_url("RoleAdmin/getRoleById"); ?>',
				type: 'POST',
				data: { id },
				dataType: 'json',
				success: function (response) {
					if (response.status === 'success') {
						// Isi nama role
						$('#roleModalLabel').text('Edit Role');
						$('#role_id').val(id); // Set ID role
						$('#role_name').val(response.data.role_name); // Set nama role

						// Reset dan kosongkan checkbox permissions
						const container = $('#permissions-container');
						container.empty();

						// Iterasi melalui permissions dari response
						response.data.permissions.forEach(function (permission) {
							const [key, value] = permission.split(':'); // Pisahkan nama kolom dan nilainya
							const isChecked = value === '1' ? 'checked' : ''; // Jika nilai 1, maka centang
							const label = key.replace(/_/g, ' '); // Ubah label untuk lebih rapi

							// Tambahkan checkbox ke container
							const checkboxHTML = `
								<div class="mb-2">
									<label>
										<input type="checkbox" id="${key}" name="${key}" value="1" ${isChecked}>
										${label}
									</label>
								</div>
							`;
							container.append(checkboxHTML);
						});

						// Tampilkan modal
						$('#roleModal').modal('show');
					} else {
						Swal.fire('Gagal', response.message, 'error');
					}
				},
				error: function () {
					Swal.fire('Error', 'Terjadi kesalahan saat memuat data', 'error');
				},
			});
		});

		var datatable = $('#datatable').DataTable({
			'lengthMenu': [
				[5, 10, 25, 50, -1],
				[5, 10, 25, 50, 'All']
			],
			'pageLength': 10,
			"processing": true,
			"serverSide": true,
			"ajax": {
				url: '<?= base_url('RoleAdmin/getRoles'); ?>',
				type: 'POST'
			},
		});

		// Event untuk submit form edit
		$('#editRoleForm').on('submit', function(e) {
			e.preventDefault();
			$.ajax({
				url: '<?= base_url('RoleAdmin/updateRole'); ?>',
				type: 'POST',
				data: $(this).serialize(),
				success: function(response) {
					datatable.ajax.reload();
					$('#editRoleModal').modal('hide');
					alert('Role berhasil diperbarui.');
				}
			});
		});
		// Event untuk tombol hapus
		$(document).on('click', '.btn-delete', function () {
			let id = $(this).data('id_role'); // Ambil ID role
			let roleName = $(this).data('role_name'); // Ambil nama role

			// Kirim AJAX untuk memeriksa apakah role sedang digunakan
			$.ajax({
				url: '<?= base_url("RoleAdmin/checkRoleUsage"); ?>', // Endpoint untuk cek penggunaan role
				type: 'POST',
				data: { id: id },
				dataType: 'json',
				success: function (response) {
					if (response.status === 'in_use') {
						// Jika role sedang digunakan
						Swal.fire(
							'Tidak Bisa Dihapus!',
							`Role "${roleName}" sedang digunakan oleh ${response.count} admin. Silakan hapus admin terlebih dahulu.`,
							'error'
						);
					} else if (response.status === 'available') {
						// Jika role tidak digunakan, tampilkan konfirmasi hapus
						Swal.fire({
							title: `Apakah Anda yakin ingin menghapus role "${roleName}"?`,
							text: "Data yang sudah dihapus tidak dapat dikembalikan!",
							icon: 'warning',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Ya, Hapus!',
							cancelButtonText: 'Batal'
						}).then((result) => {
							if (result.isConfirmed) {
								// Kirim permintaan hapus via AJAX
								$.ajax({
									url: '<?= base_url("RoleAdmin/deleteRole"); ?>',
									type: 'POST',
									data: { id: id },
									dataType: 'json',
									success: function (response) {
										if (response.status) {
											Swal.fire('Berhasil!', response.message, 'success');
											datatable.ajax.reload(); // Refresh datatable
										} else {
											Swal.fire('Gagal!', response.message, 'error');
										}
									},
									error: function () {
										Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
									}
								});
							}
						});
					} else {
						Swal.fire('Error!', 'Gagal memeriksa penggunaan role.', 'error');
					}
				},
				error: function () {
					Swal.fire('Error!', 'Terjadi kesalahan saat memeriksa penggunaan role.', 'error');
				}
			});
		});

		function loadPermissions() {
			$.ajax({
				url: '<?= base_url("RoleAdmin/getPermissions"); ?>', // Endpoint untuk mendapatkan semua kolom permissions
				type: 'GET',
				dataType: 'json',
				success: function (response) {
				if (response.status === "success" && Array.isArray(response.data)) {
					const container = $('#permissions-container');
					container.empty(); // Kosongkan container

					response.data.forEach((permission) => {
					const checkbox = `
						<label>
						<input type="checkbox" id="${permission}" name="${permission}" value="1">
						${permission.replace('_', ' ')}
						</label>
					`;
					container.append(checkbox);
					});
				} else {
					alert("Gagal memuat permissions. Data tidak valid.");
				}
				},
				error: function (xhr, status, error) {
				console.error("Error saat memuat permissions:", error);
				}
			});
		}




        // Submit form
        $('#roleForm').on('submit', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();
            const url = $('#role_id').val()
                ? '<?= base_url("RoleAdmin/updateRole"); ?>'
                : '<?= base_url("RoleAdmin/addRole"); ?>';

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Berhasil', response.message, 'success');
                        $('#roleModal').modal('hide');
                        $('#datatable').DataTable().ajax.reload();
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                }
            });
        });

	});
</script>
