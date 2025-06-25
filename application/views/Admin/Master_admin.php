<div class="main-content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Master Admin</h4>
					<p class="card-title-desc">Kelola data admin di sini.</p>

					<button class="btn btn-primary mb-3" id="addAdminButton">Tambah Admin</button>

					<table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
						<thead>
							<tr>
								<th>No</th>
								<th>Username</th>
								<th>Role</th>
								<th>Created At</th>
								<th>Updated At</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Admin -->
	<div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form id="adminForm">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="adminModalLabel">Tambah Admin</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" id="admin_id" name="id">

						<div class="mb-3">
							<label for="username" class="form-label">Username</label>
							<input type="text" class="form-control" id="username" name="username" required>
						</div>

						<div class="mb-3">
							<label for="password" class="form-label">Password</label>
							<div class="input-group">
								<input type="password" class="form-control" id="password" name="password" required>
								<button type="button" class="btn btn-outline-secondary" id="togglePassword">
									<i class="fas fa-eye"></i>
								</button>
							</div>
						</div>


						<div class="mb-3">
							<label for="role" class="form-label">Role</label>
							<select class="form-select" id="role" name="id_role" required>
								<!-- Data role akan diisi melalui AJAX -->
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Simpan</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="<?= base_url('assets/assets/js/sw.js'); ?>"></script>

<script>
	$(document).ready(function() {
		// Inisialisasi DataTable
		var datatable = $('#datatable').DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": {
				url: "<?= base_url('Data/getAdmins'); ?>",
				type: "POST"
			},
			"columns": [{
					"data": "no"
				},
				{
					"data": "username"
				},
				{
					"data": "role_name"
				},
				{
					"data": "created_at"
				},
				{
					"data": "updated_at"
				},
				{
					"data": "actions"
				}
			]
		});


		// Tambah Admin
		$('#addAdminButton').on('click', function() {
			$('#adminModalLabel').text('Tambah Admin');
			$('#adminForm')[0].reset();
			$('#admin_id').val('');
			$('#adminModal').modal('show');
			loadRoles();
		});

		// Edit Admin
		$(document).on('click', '.btn-edit', function() {
			const id = $(this).data('id');

			$.ajax({
				url: '<?= base_url("Admin/getAdminById"); ?>',
				type: 'POST',
				data: {
					id
				},
				dataType: 'json',
				success: function(response) {
					if (response.status === 'success') {
						$('#adminModalLabel').text('Edit Admin');
						$('#admin_id').val(response.data.id_admin);
						$('#username').val(response.data.username);
						$('#password').val(response.data.password); // Password kosongkan (tidak di-edit jika tidak diisi)
						$('#role').val(response.data.id_role);
						$('#adminModal').modal('show');
						loadRoles();
					} else {
						swe("Gagal mendapatkan data admin");
					}
				},
			});
		});


		// Submit Form
		$('#adminForm').on('submit', function(e) {
			e.preventDefault();

			const url = $('#admin_id').val() ? '<?= base_url("Admin/updateAdmin"); ?>' : '<?= base_url("Admin/addAdmin"); ?>';

			$.ajax({
				url,
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function(response) {
					if (response.status === 'success') {
						$('#adminModal').modal('hide');
						datatable.ajax.reload();
						sws(response.message);
					} else {
						swe(response.message);
					}
				},
			});
		});

		// Hapus Admin
		$(document).on('click', '.btn-delete', function() {
			const id = $(this).data('id');
			const username = $(this).data('username');

			Swal.fire({
				title: `Hapus admin ${username}?`,
				text: 'Data yang dihapus tidak dapat dikembalikan!',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal',
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: '<?= base_url("Admin/deleteAdmin"); ?>',
						type: 'POST',
						data: {
							id
						},
						dataType: 'json',
						success: function(response) {
							if (response.status === 'success') {
								datatable.ajax.reload();
								Swal.fire('Berhasil!', response.message, 'success');
							} else {
								Swal.fire('Gagal!', response.message, 'error');
							}
						},
					});
				}
			});
		});

		// Load Roles
		function loadRoles() {
			$.ajax({
				url: '<?= base_url("Admin/getRoles"); ?>',
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					if (response.status === "success" && response.data) {
						const roleSelect = $('#role');
						roleSelect.empty(); // Kosongkan daftar role terlebih dahulu
						roleSelect.append(new Option("Pilih Role", "")); // Tambahkan opsi default
						response.data.forEach((role) => {
							roleSelect.append(new Option(role.role_name, role.id_role));
						});
					} else {
						alert("Gagal memuat data role.");
					}
				},
				error: function(xhr, status, error) {
					console.error("Error saat memuat roles:", error);
				}
			});
		}
	// Toggle Password Visibility
	$('#togglePassword').on('click', function() {
        const passwordField = $('#password');
        const passwordFieldType = passwordField.attr('type');
        const icon = $(this).find('i');

        // Toggle input type and icon class
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
	});
</script>
