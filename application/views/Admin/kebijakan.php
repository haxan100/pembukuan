<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Kebijakan</h4>
                    <form id="policyForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Masukkan judul kebijakan" required>
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

<!-- Summernote CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script>
    $(document).ready(function () {
        // Inisialisasi Summernote
        $('#content').summernote({
            height: 200
        });

        // Submit Form dengan AJAX
        $('#policyForm').on('submit', function (e) {
            e.preventDefault();

            const formData = {
                title: $('#title').val(),
                content: $('#content').val()
            };

            $.ajax({
                url: '<?= base_url("Policy/savePolicy"); ?>', // Endpoint untuk menyimpan data kebijakan
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Berhasil', response.message, 'success');
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                }
            });
        });

        // Memuat data kebijakan yang sudah ada
        $.ajax({
            url: '<?= base_url("Policy/getPolicy"); ?>', // Endpoint untuk mendapatkan data kebijakan
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#title').val(response.data.title);
                    $('#content').summernote('code', response.data.content);
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Terjadi kesalahan saat memuat data', 'error');
            }
        });
    });
</script>
