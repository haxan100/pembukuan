<!-- View: slider_view.php -->
<div class="main-content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Menu Slider</h4>
					<p class="card-title-desc">Kelola slider gambar di sini.</p>

					<button class="btn btn-primary mb-3" id="addSliderButton">Tambah Slider</button>

					<table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
						<thead>
							<tr>
								<th>No</th>
								<th>Gambar</th>
								<th>Caption</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Slider -->
	<div class="modal fade" id="sliderModal" tabindex="-1" aria-labelledby="sliderModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form id="sliderForm" enctype="multipart/form-data">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="sliderModalLabel">Tambah Slider</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" id="slider_id" name="id">

						<div class="mb-3">
							<label for="image" class="form-label">Gambar</label>
							<input type="file" class="form-control" id="image" name="image" required>
						</div>

						<div class="mb-3">
							<label for="caption" class="form-label">Caption</label>
							<input type="text" class="form-control" id="caption" name="caption" required>
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


<!-- Modal untuk menampilkan gambar besar -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body text-center">
				<img id="modalImage" src="" class="img-fluid" alt="Gambar Slider">
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="editSliderModal" tabindex="-1" aria-labelledby="editSliderModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form id="editSliderForm" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editSliderModalLabel">Edit Slider</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="edit_slider_id" name="id">

					<div class="mb-3 text-center">
						<img id="edit_image_preview" src="" class="img-thumbnail" style="max-width: 200px;">
					</div>

					<div class="mb-3">
						<label for="edit_caption" class="form-label">Caption</label>
						<input type="text" class="form-control" id="edit_caption" name="caption" required>
					</div>

					<div class="mb-3">
						<label for="edit_image" class="form-label">Upload Gambar Baru (Opsional)</label>
						<input type="file" class="form-control" id="edit_image" name="image">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function() {
		var datatable = $('#datatable').DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": {
				url: "<?= base_url('Slider/getSliders'); ?>",
				type: "POST"
			},
		});

		$('#addSliderButton').on('click', function() {
			$('#sliderModalLabel').text('Tambah Slider');
			$('#sliderForm')[0].reset();
			$('#sliderModal').modal('show');
		});

		$('#sliderForm').on('submit', function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			$.ajax({
				url: "<?= base_url('Slider/upload'); ?>",
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: 'json',
				success: function(response) {
					$('#sliderModal').modal('hide');
					datatable.ajax.reload();
					Swal.fire(response.message);
				}
			});
		});

		$(document).on('click', '.btn-delete', function() {
			const id = $(this).data('id');
			Swal.fire({
				title: `Hapus slider ini?`,
				text: 'Data yang dihapus tidak dapat dikembalikan!',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal',
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: "<?= base_url('Slider/delete'); ?>",
						type: 'POST',
						data: {
							id
						},
						dataType: 'json',
						success: function(response) {
							datatable.ajax.reload();
							Swal.fire(response.message);
						},
					});
				}
			});
		});

		$(document).on('click', '.slider-thumbnail', function() {
			const imageUrl = $(this).data('src');
			$('#modalImage').attr('src', imageUrl);
			$('#imageModal').modal('show');
		});
		$(document).on('click', '.btn-edit', function() {
			const id = $(this).data('id');
			const image = $(this).data('image');
			const caption = $(this).data('caption');
			console.log("okok");

			$('#edit_slider_id').val(id);
			$('#edit_caption').val(caption);
			$('#edit_image_preview').attr('src', image);
			$('#editSliderModal').modal('show');
		});
		$(document).on('submit', '#editSliderForm', function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			$.ajax({
				url: "<?= base_url('Slider/edit'); ?>",
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: 'json',
				success: function(response) {
					$('#editSliderModal').modal('hide');
					datatable.ajax.reload();
					Swal.fire(response.message);
				}
			});
		});
	});
</script>
