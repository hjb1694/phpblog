<header class="header">
    <div class="header__one">
        <div class="header__logo-box">
            <a href="index.php" class="header__logo"><i class="fas fa-asterisk" style="padding-right:1rem; margin-right:1rem; border-right:.8px solid #fff;"></i>HB Blog</a>
        </div>
        <i class="header__navtoggle  fa fa-bars"></i>
    </div>
    <div class="header__two">
        <nav class="header__nav">
            <a class="header__nav-item" href="#">About</a>
            <a class="header__nav-item" href="admin/main.php">Admin</a>
            <?php if(!isset($_SESSION['uid'])): ?>
            <a class="header__nav-item" href="login.php">Login</a>
            <a class="header__nav-item" href="register.php">Register</a>
            <?php else: ?>
            <a class="header__nav-item" href="logout.php">Logout</a>
            <?php endif; ?>
        </nav>
    </div>
</header>