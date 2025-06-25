<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Syarat & Ketentuan</title>
  <style>
    /* Reset dasar */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f7f9fc;
      color: #333;
      padding: 30px;
    }

    .container {
      max-width: 900px;
      margin: 0 auto;
      background-color: #ffffff;
      padding: 50px 60px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    }

    h2 {
      text-align: center;
      margin-bottom: 40px;
      font-size: 28px;
      color: #222;
    }

    .content {
      font-size: 16px;
      line-height: 1.8;
    }

    .content h3,
    .content h4,
    .content strong {
      text-align: center;
      margin: 30px 0 15px;
      display: block;
      color: #000;
    }

    .content p {
      margin-bottom: 20px;
      text-align: justify;
    }

    .content ul,
    .content ol {
      margin-left: 25px;
      margin-bottom: 20px;
    }

    .content ol.lower-alpha {
      list-style-type: lower-alpha;
    }

    .content li {
      margin-bottom: 10px;
    }

    /* Tambahan styling untuk blockquote atau penekanan */
    .content blockquote {
      border-left: 4px solid #007BFF;
      padding-left: 15px;
      margin: 20px 0;
      font-style: italic;
      color: #555;
      background-color: #f0f8ff;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Syarat dan Ketentuan</h2>
    <div id="terms-content" class="content">
      Memuat konten...
    </div>
  </div>

  <script>
    fetch("<?= base_url('terms/get') ?>")
      .then(response => response.json())
      .then(data => {
        const container = document.getElementById('terms-content');
        if (data.status === 'success') {
          container.innerHTML = data.data.content;
        } else {
          container.innerHTML = '<p style="color:red;">' + data.message + '</p>';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        document.getElementById('terms-content').innerHTML =
          '<p style="color:red;">Terjadi kesalahan saat mengambil data.</p>';
      });
  </script>

</body>
</html>
