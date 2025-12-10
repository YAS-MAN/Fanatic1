<?php
require_once __DIR__ . '/auth.php';
if (is_logged_in()) {
  header('Location: home.php');
  exit;
}
$pageTitle = 'Login';
$currentPage = 'index';
include 'header.php';
?>

    <?php if (isset($_GET['error'])): ?>
      <div class="flash-message flash-error">
         ⚠️ <?php echo htmlspecialchars($_GET['error']); ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['registered'])): ?>
      <div class="flash-message flash-success">
         ✅ Registrasi Berhasil! Silakan Login.
      </div>
    <?php endif; ?>

    <section class="auth-page">
      <video class="auth-video" autoplay muted loop playsinline preload="auto" poster="assets/hero_poster.jpg">
        <source src="assets/VID_HERO.mp4" type="video/mp4" />
      </video>
      <div id="auth-container" class="auth-container">
        <div class="form-container sign-in-container">
          <div class="auth-form-wrap">
            <h2>Sign In</h2>
            <form method="post" action="index.php" class="auth-form">
              <input type="hidden" name="action" value="login" />
              <input type="text" name="identifier" placeholder="Email atau Nama" required />
              <div class="input-with-toggle">
                <input type="password" name="password" placeholder="Password" id="login-password" required />
                <button type="button" class="toggle-pass" data-target="login-password">👁</button>
              </div>
              <button type="submit" class="btn red">Sign In</button>
            </form>
          </div>
        </div>

        <div class="form-container sign-up-container">
          <div class="auth-form-wrap">
            <h2>Create Account</h2>
            <form method="post" action="index.php" class="auth-form">
              <input type="hidden" name="action" value="register" />
              <input type="text" name="name" placeholder="Nama" required />
              <input type="email" name="email" placeholder="Email" style="margin-top:-1px" required />
              <div class="input-with-toggle">
                <input type="password" name="password" placeholder="Password" id="register-password" required />
                <button type="button" class="toggle-pass" data-target="register-password">👁</button>
              </div>
              <button type="submit" class="btn red">Sign Up</button>
            </form>
          </div>
        </div>

        <div class="overlay-container">
          <div class="overlay">
            <div class="overlay-panel overlay-left">
              <h3 style="font-style:italic; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">Welcome To <span class="red-f">F</span>anatic <span class="digit-1">1</span></h3>
              <p>Gabung untuk akses penuh!</p>
              <button class="btn grey" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
              <h3 style="font-style:italic; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">Welcome To <span class="red-f">F</span>anatic <span class="digit-1">1</span></h3>
              <p>Masuk dengan Email & Password</p>
              <button class="btn grey" id="signUp">Sign Up</button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script>
      setTimeout(() => {
        const flash = document.querySelector('.flash-message');
        if(flash) {
            flash.style.transition = "opacity 0.5s ease";
            flash.style.opacity = "0";
            setTimeout(() => flash.remove(), 500);
        }
      }, 4000);
    </script>

<?php include 'footer.php'; ?>