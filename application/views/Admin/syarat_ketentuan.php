<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Syarat dan Ketentuan</h4>
                    <form id="termsForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Masukkan judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Konten</label>
                            <textarea id="content" name="content" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan Summernote CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi Summernote
        $('#content').summernote({
            height: 200,
            placeholder: 'Masukkan konten syarat dan ketentuan di sini...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // Submit Form dengan AJAX
        $('#termsForm').on('submit', function(e) {
            e.preventDefault();

            // Validasi input
            const title = $('#title').val().trim();
            const content = $('#content').val().trim();

            if (!title) {
                Swal.fire('Error', 'Judul tidak boleh kosong.', 'error');
                return;
            }

            if (!content) {
                Swal.fire('Error', 'Konten tidak boleh kosong.', 'error');
                return;
            }

            // Data form
            const formData = {
                title: title,
                content: content
            };

            // AJAX request
            $.ajax({
                url: '<?= base_url("Terms/save"); ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
		loadTerms();

                    if (response.status === 'success') {
                        Swal.fire('Berhasil', response.message, 'success');
                        $('#termsForm')[0].reset();
                        $('#content').summernote('reset'); // Reset Summernote
                    
					} else {
                        Swal.fire('Gagal', response.message || 'Terjadi kesalahan.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                }
            });
        });
		function loadTerms() {
			$.ajax({
				url: '<?= base_url("Terms/get"); ?>',
				type: 'GET',
				dataType: 'json',
				success: function (response) {
					if (response.status === 'success' && response.data) {
						$('#title').val(response.data.title);
						$('#content').summernote('code', response.data.content); // Set content ke Summernote
					} else {
						Swal.fire('Info', response.message || 'Data tidak ditemukan.', 'info');
					}
				},
				error: function () {
					Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
				}
			});
		}

		// Load data saat halaman selesai dimuat
		loadTerms();
		});
</script>
