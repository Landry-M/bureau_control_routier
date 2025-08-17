<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from coderthemes.com/jidox/layouts/auth-login-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 07 Jul 2025 09:13:48 GMT -->
<head>
    <meta charset="utf-8" />
    <title>Connexion | Bureau de contrôle routier</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/logo.jpg">

    <!-- Theme Config Js -->
    <script src="assets/js/config.js"></script>

    <!-- App css -->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg pb-0">

    <div class="auth-fluid">
        
        <!-- Auth fluid right content -->
        <div class="auth-fluid-right text-center">
            <div class="auth-user-testimonial">
                <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <h2 class="mb-3">Bureau de contrôle routier!</h2>
                            <p class="lead"><i class="ri-double-quotes-l"></i>  Bienvenue sur le portail de contrôle routier.
                            </p>
                        </div>
                        <div class="carousel-item">
                            <h2 class="mb-3">Suivi des contraventions !</h2>
                            <p class="lead"><i class="ri-double-quotes-l"></i>  Suivi des contraventions, des accidents et des dossier de contraventions.
                            </p>
                        </div>
                        <div class="carousel-item">
                            <h2 class="mb-3">Suivi des accidents !</h2>
                            <p class="lead"><i class="ri-double-quotes-l"></i> Gestion des accidents, des dossier d'accidents.
                            </p>
                        </div>
                    </div>
                </div>
            </div> <!-- end auth-user-testimonial-->
        </div>
        <!-- end Auth fluid right content -->

        <!--Auth fluid left content -->
        <div class="auth-fluid-form-box">
            
            <div class="card-body d-flex flex-column h-100 gap-3">

                <!-- Logo -->
                <div class="auth-brand text-center text-lg-start">
                    <a href="/" class="logo-dark">
                        <span><img src="assets/images/logo.jpg" alt="dark logo" height="100"></span>
                    </a>
                    <a href="/" class="logo-light">
                        <span><img src="assets/images/logo.jpg" alt="logo" height="100"></span>
                    </a>
                </div>

                <?php if(isset($result['state']) && $result['state'] ==false){ ?>
                    <div class="alert alert-danger text-center role="alert">
                        <p class="text-muted mb-4"> <?= $result['message']; ?></p>
                    </div>
                <?php unset($result); } ?> 

                <div class="my-auto">
                    <!-- title-->
                    <h4 class="mt-0">Connexion</h4>
                    <p class="text-muted mb-4"> Veuillez entrer votre nom d'utilisateur et votre mot de passe pour vous connecter.</p>

                    <!-- form -->
                    <form action="/login" method="post">
                        <div class="mb-3">
                            <label for="emailaddress" class="form-label">Nom d'utilisateur</label>
                            <input class="form-control" type="text" name="username" id="emailaddress" required="" placeholder="Entrer votre nom d'utilisateur">
                        </div>
                        <div class="mb-3">
                            <!-- <a href="auth-recoverpw-2.html" class="text-muted float-end"><small>Forgot your password?</small></a> -->
                            <label for="password" class="form-label">Mot de passe</label>
                            <input class="form-control" type="password" name="password" required="" id="password" placeholder="Entrer votre mot de passe">
                        </div>
                        <!-- <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                <label class="form-check-label" for="checkbox-signin">Remember me</label>
                            </div>
                        </div> -->
                        <div class="d-grid mb-0 text-center">
                            <button class="btn btn-primary" type="submit"><i class="ri-login-box-line"></i> Se connecter </button>
                        </div>
                        <!-- social-->
                        <!-- <div class="text-center mt-4">
                            <p class="text-muted fs-16">Sign in with</p>
                            <ul class="social-list list-inline mt-3">
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i class="ri-facebook-circle-fill"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i class="ri-google-fill"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-info text-info"><i class="ri-twitter-fill"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i class="ri-github-fill"></i></a>
                                </li>
                            </ul>
                        </div> -->
                    </form>
                    <!-- end form-->
                </div>

                <!-- Footer-->
                <!-- <footer class="footer footer-alt">
                    <p class="text-muted">Don't have an account? <a href="auth-register-2.html" class="text-muted ms-1"><b>Sign Up</b></a></p>
                </footer> -->

            </div> <!-- end .card-body -->
        </div>
        <!-- end auth-fluid-form-box-->
    </div>
    <!-- end auth-fluid-->
    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>


<!-- Mirrored from coderthemes.com/jidox/layouts/auth-login-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 07 Jul 2025 09:13:48 GMT -->
</html>