<!DOCTYPE html>
<html lang="en"><!--Unsere Webseite Sprache wird englsich sein***-->
<?php session_start();?>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>BOOKWARM</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap Icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <!-- SimpleLightbox plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
        
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/style.css" rel="stylesheet" />
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg" id="mainNav">
            <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php#page-top">
                    <img src="pictures/bookwarm-logo-new.png" alt="logo" class="logo"/>
                    ...your cozy library
                </a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto my-2 my-lg-0">
                        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="bookGallery.php">Book Gallery</a></li>
                        <?php
                            if(isset($_SESSION['username'])){
                                echo '<li class="nav-item"><a class="nav-link" href="addBook.php">Add Books</a></li>';
                                echo '<li class="nav-item"><a class="nav-link" href="userProfile.php">'.$_SESSION['username']."'s profile</a></li>";                             
                                echo '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
                                echo '<p>hello '.$_SESSION['username'].'!!!</p>';
                            }
                            else{
                                echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
