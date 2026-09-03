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
      
      <form action="welcome_get.php" method="POST" class="custom-form">
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

        <button type="submit" class="btn btn-primary btn-full">
          Kirim Data <i class="fa-solid fa-paper-plane"></i>
        </button>
      </form>
    </section>
  </main>
</body>
</html>