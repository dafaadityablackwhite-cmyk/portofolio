<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Selamat Datang</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="background-glow"></div>

  <main class="container" style="max-width: 480px; margin-top: 60px;">
    <section class="profile-card text-center">
      <div class="success-icon">
        <i class="fa-solid fa-circle-check"></i>
      </div>
      
      <h2 class="section-title" style="justify-content: center; margin-bottom: 8px;">
        Selamat Datang!
      </h2>
      
      <p style="color: var(--text-muted); margin-bottom: 20px;">Data Anda berhasil terkirim.</p>

      <div class="result-box">
        <div class="result-item">
          <span>Nama:</span>
          <strong><?php echo htmlspecialchars($_POST["name"] ?? 'Tamu'); ?></strong>
        </div>
        <div class="result-item">
          <span>E-mail:</span>
          <strong><?php echo htmlspecialchars($_POST["email"] ?? '-'); ?></strong>
        </div>
      </div>

      <a href="index.html" class="btn btn-primary btn-full" style="margin-top: 20px;">
        <i class="fa-solid fa-house"></i> Kembali ke Beranda
      </a>
    </section>
  </main>
</body>
</html>