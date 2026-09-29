<?php
session_start();

$notif = '';
if (isset($_GET['pesan']) && $_GET['pesan'] === 'belum_login') {
    $notif = 'Silakan isi form dan login terlebih dahulu.';
}

// Cek apakah form dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tangkap data dari form input
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    // Simpan ke session
    $_SESSION['login'] = true;
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;

    // Arahkan / redirect ke dashboard.php
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Pendaftaran</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="background-glow"></div>

  <main class="container" style="max-width: 480px; margin-top: 60px;">
    <section class="profile-card">
      <h2 class="section-title">
        <i class="fa-solid fa-user-plus"></i> Form Pendaftaran
      </h2>

      <?php if (!empty($notif)): ?>
        <p style="color: #ff4d4d; text-align: center;"><?php echo htmlspecialchars($notif); ?></p>
      <?php endif; ?>
      
      <!-- Ubah action menjadi empty ("") agar mengarah ke file p.php ini sendiri untuk diproses -->
      <form action="" method="POST" class="custom-form">
        <div class="form-group">
          <label for="name">Nama Lengkap</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-user input-icon"></i>
            <input type="text" id="name" name="name" placeholder="Masukkan nama Anda" required>
          </div>
        </div>

        <div class="form-group">
          <label for="email">Alamat E-mail</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-envelope input-icon"></i>
            <input type="email" id="email" name="email" placeholder="nama@email.com" required>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-full" style="margin-top:20px;">
          Kirim Data <i class="fa-solid fa-paper-plane"></i>
        </button>
      </form>
    </section>
  </main>
</body>
</html>