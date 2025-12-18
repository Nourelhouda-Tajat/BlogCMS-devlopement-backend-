<?php
session_start();
require_once('config.php');
require_once('fonctions.php');

$articles = getAllArticles($pdo);
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <title>Home - blogCMS</title>
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
                    
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAuthor()): ?>
                            <li><a href="dashboard.php" title="">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="#0" title="">Welcome, <?php echo $_SESSION['username']; ?></a></li>
                        <li><a href="login.php?action=logout" title="">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" title="">Login</a></li>
                    <?php endif; ?>
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

        <div class="s-content">
            <div class="masonry-wrap">
                <div class="masonry">
                    <div class="grid-sizer"></div>

                    <?php foreach($articles as $article): ?>
                        <?php 
                        $category = getCategoryById($pdo, $article['id_categoy']);
                        $author = getUserById($pdo, $article['id_user']);
                        ?>

                        <article class="masonry__brick entry format-standard">
                            <div class="entry__thumb">
                                <a href="single_article.php?id=<?php echo $article['ID_article']; ?>" class="entry__thumb-link">
                                    <img src="<?php echo $article['img_article']; ?>" alt="<?php echo $article['title']; ?>">
                                </a>
                            </div>

                            <div class="entry__text">
                                <div class="entry__header">
                                    <div class="entry__meta">
                                        <span class="cat-links">
                                            <a href="#"><?php echo $category['name_category']; ?></a>
                                        </span>
                                        <span class="byline">
                                            By: <a href="#"><?php echo $author['userName']; ?></a>
                                        </span>
                                    </div>
                                    <h1 class="entry__title">
                                        <a href="single_article.php?id=<?php echo $article['ID_article']; ?>">
                                            <?php echo $article['title']; ?>
                                        </a>
                                    </h1>
                                </div>
                                <div class="entry__excerpt">
                                    <p><?php echo substr($article['content'], 0, 150); ?>...</p>
                                </div>
                            </div>
                        </article>

                    <?php endforeach; ?>

                </div>
            </div>
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
