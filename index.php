<?php
    include "header.php";
?>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container px-4 px-lg-5 h-100">
                <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-8 align-self-end">
                        <h1 class="text-white font-weight-bold">Library</h1>
                        <hr class="divider" />
                    </div>
                    <div class="col-lg-8 align-self-baseline">
                    </div>
                </div>
            </div>
        </header>
        <!-- About-->
        <section class="page-section bg-primary" id="about">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="text-white mt-0">We got what you need!</h2>
                        <hr class="divider divider-light" />
                        <p class="text-white-75 mb-4">This website is an online Library! Here you can see books form other clients that have uploaded. You can upload after you have registered.</p>
                        <a class="btn btn-light btn-xl" href="registration.php">Get Started!</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Services-->
        <section class="page-section" id="services">
            <div class="container px-4 px-lg-5">
                <h2 class="text-center mt-0">Our Services</h2>
                <hr class="divider" />
                <div class="row gx-4 gx-lg-5">
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <a href="login.php">
                                <div class="mb-2"><i class="bi bi-person fs-1 text-primary"></i></div>
                                <h3 class="h4 mb-2">Login</h3>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <a href="registration.php">
                                <div class="mb-2"><i class="bi bi-person-plus fs-1 text-primary"></i></div>
                                <h3 class="h4 mb-2">Register</h3>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <a href="addBooks.php">
                                <div class="mb-2"><i class="bi-plus fs-1 text-primary"></i></div>
                                <h3 class="h4 mb-2">Add Books</h3>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <a href="bookGallery.php">
                                <div class="mb-2"><i class="bi bi-book fs-1 text-primary"></i></div>
                                <h3 class="h4 mb-2">View Books</h3>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<?php
    include "footer.php";
?>
