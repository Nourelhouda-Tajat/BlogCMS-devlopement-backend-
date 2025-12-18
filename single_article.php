<?php
require_once('config.php');
require_once('fonctions.php');

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = $_GET['id'];

$article = getArticleById($pdo, $id);
$category = getCategoryById($pdo, $article['id_categoy']);
$author = getUserById($pdo, $article['id_user']);
$comments = getComments($pdo, $id);


?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title> <?php echo $article['title']?> -blogCMS </title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/vendor.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <!-- script
    ================================================== -->
    <script src="assets/js/modernizr.js"></script>

    <!-- favicons
    ================================================== -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">

</head>

<body class="ss-bg-white">

    <!-- preloader
    ================================================== -->
    <div id="preloader">
        <div id="loader" class="dots-fade">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <div id="top" class="s-wrap site-wrapper">

        <!-- site header
        ================================================== -->
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
        
                </div>  <!-- end header__search -->

                <!-- toggles -->
                <a href="#0" class="header__search-trigger"></a>
                <a href="#0" class="header__menu-toggle"><span>Menu</span></a>

            </div>

            <nav class="header__nav-wrap">

                <ul class="header__nav">
                    <li><a href="index.html" title="">Home</a></li>
                    <li class="has-children">
                        <a href="#0" title="">Categories</a>
                        <ul class="sub-menu">
                        <li><a href="category.html">Lifestyle</a></li>
                        <li><a href="category.html">Health</a></li>
                        <li><a href="category.html">Family</a></li>
                        <li><a href="category.html">Management</a></li>
                        <li><a href="category.html">Travel</a></li>
                        <li><a href="category.html">Work</a></li>
                        </ul>
                    </li>
                    <li class="has-children current">
                        <a href="#0" title="">Blog</a>
                        <ul class="sub-menu">
                        <li><a href="single-video.html">Video Post</a></li>
                        <li><a href="single-audio.html">Audio Post</a></li>
                        <li><a href="single-gallery.html">Gallery Post</a></li>
                        <li><a href="single-standard.html">Standard Post</a></li>
                        </ul>
                    </li>
                    <li><a href="styles.html" title="">Styles</a></li>
                    <li><a href="page-about.html" title="">About</a></li>
                    <li><a href="page-contact.html" title="">Contact</a></li>
                </ul> <!-- end header__nav -->

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

            </nav> <!-- end header__nav-wrap -->

        </header> <!-- end s-header -->


        <!-- site content
        ================================================== -->
        <div class="s-content content">
            <main class="row content__page">
                
                <article class="column large-full entry format-standard">

                    <div class="media-wrap entry__media">
                        <div class="entry__post-thumb">
                            <img src="<?php  $article['img_article']  ?>" sizes="(max-width: 2000px) 100vw, 2000px" alt="<?php $article['title'] //attention ?>">
                        </div>
                    </div>

                    <div class="content__page-header entry__header">
                        <h1 class="display-1 entry__title">
                        <?php $article['title'] ?>
                        </h1>
                        <ul class="entry__header-meta">
                            <li class="author"> <a href="#0"><?php $author['userName'] ?> </a></li>
                            <li class="date"> <?php date('F d, Y', strtotime($article['created_at'])) ?> </li>
                            <li class="cat-links">
                                <a href="#0"><?php $category['name_category'] ?></a>
                            </li>
                        </ul>
                    </div> <!-- end entry__header -->

                    <div class="entry__content">

                        <p class="lead drop-cap">
                        <?php $article['content'] ?>
                        </p>

                    </div> <!-- end entry content -->

                </article> 


                <div class="comments-wrap">

                    <div id="comments" class="column large-12">

                        <h3 class="h2"><?php count($comments) ?> Comments</h3>
        
                        <!-- START commentlist -->
                        <ol class="commentlist">
                            <?php foreach($comments as $comment): ?>
                                <?php $comment_user = getUserById($pdo, $comment['id_user']); ?>
        
                            <li class="depth-1 comment">
        
                                <div class="comment__avatar">
                                    <img class="avatar" src="assets/images/avatars/user-01.jpg" alt="" width="50" height="50">
                                </div>
        
                                <div class="comment__content">
        
                                    <div class="comment__info">
                                        <div class="comment__author"> <?php $comment_user['userName'] ?> </div>
        
                                        <div class="comment__meta">
                                            <div class="comment__time"><?php date('F d, Y', strtotime($comment['created_at'])) ?></div>
                                            <div class="comment__reply">
                                                <a class="comment-reply-link" href="#0">Reply</a>
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="comment__text">
                                    <p><?php $comment['content'] ?></p>
                                    </div>
        
                                </div>
        
                            </li> <!-- end comment level 1 -->
                            <?php endforeach; ?>
                        </ol>
                        <!-- END commentlist -->

                    </div> <!-- end comments -->

                    <div class="column large-12 comment-respond">

                        <!-- START respond -->
                        <div id="respond">
            
                            <h3 class="h2">Add Comment <span>Your email address will not be published</span></h3>
            
                            <form name="contactForm" id="contactForm" method="post" action="" autocomplete="off">
                                <fieldset>
            
                                    <div class="form-field">
                                        <input name="cName" id="cName" class="full-width" placeholder="Your Name" value="" type="text">
                                    </div>
            
                                    <div class="form-field">
                                        <input name="cEmail" id="cEmail" class="full-width" placeholder="Your Email" value="" type="text">
                                    </div>
            
                                    <div class="form-field">
                                        <input name="cWebsite" id="cWebsite" class="full-width" placeholder="Website" value="" type="text">
                                    </div>
            
                                    <div class="message form-field">
                                        <textarea name="cMessage" id="cMessage" class="full-width" placeholder="Your Message"></textarea>
                                    </div>
            
                                    <input name="submit" id="submit" class="btn btn--primary btn-wide btn--large full-width" value="Add Comment" type="submit">
            
                                </fieldset>
                            </form> <!-- end form -->
            
                        </div>
                        <!-- END respond-->
            
                    </div> <!-- end comment-respond -->
            
                </div> <!-- end comments-wrap -->
            </main>

        </div> <!-- end s-content -->


        <!-- footer
        ================================================== -->
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

    </div> <!-- end s-wrap -->


    <!-- Java Script
    ================================================== -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>

</body>