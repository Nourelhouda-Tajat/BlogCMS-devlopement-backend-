
<?php
session_start();
require_once('config.php');
require_once('fonctions.php');

// Gestion du logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Rediriger vers index si déjà connecté
if (isLoggedIn() && !isset($_GET['action'])) {
    header("Location: index.php");
    exit;
}

$error = '';

// Traitement du formulaire de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (!empty($email) && !empty($password)) {
        $user = getUserByEmail($pdo, $email);
        


        if ($user && password_verify($password, $user['userPassword'])) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['userName'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Email ou mot de passe incorrect";
        }
    } else {
        $error = "Veuillez remplir tous les champs";
    }
}
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <title>Login - blogCMS</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/vendor.css">
    <link rel="stylesheet" href="assets/css/main.css">
    
    <script src="assets/js/modernizr.js"></script>
    
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
</head>

<body class="ss-bg-white">

    <div id="preloader">
        <div id="loader" class="dots-fade">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <div id="top" class="s-wrap site-wrapper">

        <header class="s-header header">
            <div class="header__top">
                <div class="header__logo">
                    <a class="site-logo" href="index.php">
                        <img src="assets/images/logo.svg" alt="Homepage">
                    </a>
                </div>

                <div class="header__search">
                    <form role="search" method="get" class="header__search-form" action="#">
                        <label>
                            <span class="hide-content">Search for:</span>
                            <input type="search" class="header__search-field" placeholder="Type Keywords" value="" name="s" title="Search for:" autocomplete="off">
                        </label>
                        <input type="submit" class="header__search-submit" value="Search">
                    </form>
                    <a href="#0" title="Close Search" class="header__search-close">Close</a>
                </div>

                <a href="#0" class="header__search-trigger"></a>
                <a href="#0" class="header__menu-toggle"><span>Menu</span></a>
            </div>

            <nav class="header__nav-wrap">
                <ul class="header__nav">
                    <li><a href="index.php" title="">Home</a></li>
                    <li class="current"><a href="login.php" title="">Login</a></li>
                </ul>

                <ul class="header__social">
                    <li class="ss-facebook">
                        <a href="https://facebook.com/">
                            <span class="screen-reader-text">Facebook</span>
                        </a>
                    </li>
                    <li class="ss-twitter">
                        <a href="#0">
                            <span class="screen-reader-text">Twitter</span>
                        </a>
                    </li>
                    <li class="ss-dribbble">
                        <a href="#0">
                            <span class="screen-reader-text">Instagram</span>
                        </a>
                    </li>
                    <li class="ss-behance">
                        <a href="#0">
                            <span class="screen-reader-text">Behance</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </header>

        <div class="s-content content">
            <main class="row content__page">
                
                <section class="column large-full entry format-standard">

                    <div class="media-wrap">
                        <div>
                            <img src="assets/images/thumbs/contact/contact-1000.jpg" 
                                 srcset="assets/images/thumbs/contact/contact-2000.jpg 2000w, 
                                         assets/images/thumbs/contact/contact-1000.jpg 1000w, 
                                         assets/images/thumbs/contact/contact-500.jpg 500w" 
                                 sizes="(max-width: 2000px) 100vw, 2000px" alt="">
                        </div>
                    </div>

                    <div class="content__page-header">
                        <h1 class="display-1">Login</h1>
                    </div>

                    <p class="lead drop-cap">
                        Connectez-vous pour accéder à votre compte et gérer vos articles.
                    </p>

                    <?php if ($error): ?>
                        <div style="background: #ffebee; padding: 15px; margin-bottom: 20px; border-radius: 5px; color: #c62828;">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <fieldset>

                            <div class="form-field">
                                <input name="email" 
                                       id="email" 
                                       class="full-width" 
                                       placeholder="Your Email" 
                                       type="email" 
                                       required>
                            </div>

                            <div class="form-field">
                                <input name="password" 
                                       id="password" 
                                       class="full-width" 
                                       placeholder="Your Password" 
                                       type="password" 
                                       required>
                            </div>

                            <button type="submit" class="btn btn--primary btn-wide btn--large full-width">
                                Login
                            </button>

                        </fieldset>
                    </form>

                    <p style="margin-top: 20px; text-align: center; color: #888;">
                        <strong>Test accounts:</strong><br>
                        Admin: admin@blogcms.com / admin123<br>
                        User: user@blogcms.com / user123
                    </p>

                </section>

            </main>
        </div>

        <footer class="s-footer footer">
            <div class="row">
                <div class="column large-full footer__content">
                    <div class="footer__copyright">
                        <span>© Copyright Typerite 2019</span> 
                        <span>Design by <a href="https://www.styleshout.com/">StyleShout</a></span>
                    </div>
                </div>
            </div>

            <div class="go-top">
                <a class="smoothscroll" title="Back to Top" href="#top"></a>
            </div>
        </footer>

    </div>

    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>

</body>
</html>
