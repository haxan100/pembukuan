
<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Master Mitra</h4>
                    <p class="card-title-desc">Kelola data Harga di sini.</p>

                    <button class="btn btn-primary mb-3" id="addHargaButton">Tambah Mitra</button>

                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mitra</th>
                                <th>Username</th>
                                <th>Created At</th>
                                <th>Last Login</th>
                                <!-- <th>QTY Toko</th> -->
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

	<div class="modal fade" id="hargaModal" tabindex="-1" aria-labelledby="hargaModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<form id="hargaForm">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="hargaModalLabel">Master Mitra</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" id="detail_id" name="id">
						<div class="row">
							<!-- Bagian Kiri: Detail Produk -->
							<div class="col-md-12">
								<h6>Detail Produk</h6>
								<div class="mb-3">
									<label for="nama_mitra" class="form-label">Nama Mitra</label>
									<input type="text" class="form-control" id="nama_mitra" name="nama_mitra" required>
								</div>
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
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Simpan</button>
					</div>
				</div>
			</form>
		</div>
	</div>

<script>
    $(document).ready(function () {
		$('.importModal').click(function (e) { 
			e.preventDefault();
			$('#importModal').modal('show');

		});
        var datatable = $('#datatable').DataTable({

            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "<?= base_url('Mitra/getDTMasterMitra'); ?>",
                type: "POST",
				data: { 
					
				 },
            },

        });

        // Tambah Harga
        $('#addHargaButton').on('click', function () {
            $('#hargaModalLabel').text('Tambah Harga');
            $('#hargaForm')[0].reset();
            $('#harga_id').val('');
            $('#hargaModal').modal('show');
        });

        // Edit Harga
        $(document).on('click', '.btn-detail', function () {
			const id = $(this).data('id');
			window.location.href = '<?= base_url('Admin/master_mitra_detail/'); ?>' + id;

		});
        $(document).on('click', '.btn-edit', function () {
			const id = $(this).data('id');

			$.ajax({
				url: '<?= base_url("Mitra/getMitraById"); ?>',
				type: 'POST',
				data: { id },
				dataType: 'json',
				success: function (response) {
					if (response.status === 'success') {
						$('#hargaModalLabel').text('Edit Master Mitra');
						$('#detail_id').val(response.data.id_master_mitra);
						$('#nama_mitra').val(response.data.nama_mitra);
						$('#username').val(response.data.username);
						$('#password').val(response.data.password);
						$('#hargaModal').modal('show');
					} else {
						alert("Gagal mendapatkan data detail harga");
					}
				},
			});
		});
        $('#hargaForm').on('submit', function (e) {
			e.preventDefault();
			var  detail_id = $('#detail_id').val();
			console.log(detail_id);
			console.log(detail_id>=1);
			
			var url = '<?= base_url("Mitra/add"); ?>';
			if(detail_id>=1) var url = '<?= base_url("Mitra/updateHarga"); ?>';
			$.ajax({
				url: url,
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function (response) {
					if (response.status === 'success') {
						$('#hargaModal').modal('hide');
						datatable.ajax.reload();
						sws(response.message); // Tampilkan notifikasi sukses
					} else {
						alert(response.message); // Tampilkan pesan error jika ada
					}
				},
			});
		});

		$(document).on('click', '.btn-delete', function() {
			const id = $(this).data('id');
			const nama_mitra = $(this).data('nama_mitra');

			Swal.fire({
				title: `Hapus Mitra ${nama_mitra}?`,
				text: 'Data yang dihapus tidak dapat dikembalikan!',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal',
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: '<?= base_url("Mitra/delete"); ?>',
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
