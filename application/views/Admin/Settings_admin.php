-- View: Admin/Settings_admin.php
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengaturan</title>
    <style>
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .permissions-grid label {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Manajemen Pengaturan</h4>
                        <p class="card-title-desc">
                            Kelola pengaturan sistem seperti nomor WhatsApp Admin dan versi aplikasi.
                        </p>
                        <form id="settingsForm">
                            <?php $settings = isset($settings) ? $settings : null; ?>
                            <div class="mb-3">
                                <label for="wa_admin" class="form-label">WhatsApp Admin</label>
                                <input type="text" class="form-control" id="wa_admin" name="wa_admin" value="<?= $settings ? $settings->wa_admin : '' ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="version_android" class="form-label">Versi Android</label>
                                <input type="text" class="form-control" id="version_android" name="version_android" value="<?= $settings ? $settings->version_android : '' ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="version_ios" class="form-label">Versi iOS</label>
                                <input type="text" class="form-control" id="version_ios" name="version_ios" value="<?= $settings ? $settings->version_ios : '' ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('settingsForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('<?= base_url("Setting/updateSettings"); ?>', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data => {
                alert(data.message);
            }).catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
