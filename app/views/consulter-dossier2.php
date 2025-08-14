<!DOCTYPE html>
<html lang="en" data-layout="topnav" data-menu-color="brand">

    
<!-- Mirrored from coderthemes.com/jidox/layouts/layouts-horizontal.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 07 Jul 2025 09:13:56 GMT -->
<head>
        <meta charset="utf-8" />
        <title>Consulter les dossiers | Bureau de Contrôle Routier</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/logo.jpg">

        <!-- Plugin css -->
        <link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css">
        <link href="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />

        <!-- Theme Config Js -->
        <script src="assets/js/config.js"></script>

        <!-- App css -->
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

        <!-- Icons css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <!-- Begin page -->
        <div class="wrapper">

            <!-- ========== Topbar Start ========== -->
            <?php require_once '_partials/_topmenu.php'; ?>
            <!-- ========== Topbar End ========== -->

            <!-- ========== Horizontal Menu Start ========== -->
           <?php require_once '_partials/_navbar.php'; ?>
            <!-- ========== Horizontal Menu End ========== -->
            
            <div class="content-page">
                <div class="content">
                    
                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box justify-content-between d-flex align-items-md-center flex-md-row flex-column">     
                                    <h4 class="page-title">Consultation des dossiers</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                           
                            <div class="col-xl-12 col-lg-12">

                                <div class="card">
                                    <div class="card-body">
                                        <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                                            <li class="nav-item">
                                                <a href="#conducteurs" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-start rounded-0 active">
                                                    Conducteurs et véhicules
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#vehicules" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0">
                                                    Véhicules et plaques d'immatriculations
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#particuliers" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-end rounded-0">
                                                    Particuliers
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#entreprises" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-end rounded-0">
                                                    Entreprises
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="conducteurs">
    
                                                <h5 class="text-uppercase mb-3"><i class="ri-briefcase-line me-1"></i>
                                                    Projects</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-centered table-hover table-borderless mb-0">
                                                        <thead class="border-top border-bottom bg-light-subtle border-light">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Clients</th>
                                                                <th>Project Name</th>
                                                                <th>Start Date</th>
                                                                <th>Due Date</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td><img src="assets/images/users/avatar-2.jpg" alt="table-user" class="me-2 rounded-circle" height="24"> Halette Boivin</td>
                                                                <td>App design and development</td>
                                                                <td>01/01/2022</td>
                                                                <td>10/12/2023</td>
                                                                <td><span class="badge bg-info-subtle text-info">Work in Progress</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td><img src="assets/images/users/avatar-3.jpg" alt="table-user" class="me-2 rounded-circle" height="24"> Durandana Jolicoeur</td>
                                                                <td>Coffee detail page - Main Page</td>
                                                                <td>21/07/2023</td>
                                                                <td>12/05/2024</td>
                                                                <td><span class="badge bg-danger-subtle text-danger">Pending</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>3</td>
                                                                <td><img src="assets/images/users/avatar-4.jpg" alt="table-user" class="me-2 rounded-circle" height="24"> Lucas Sabourin</td>
                                                                <td>Poster illustation design</td>
                                                                <td>18/03/2023</td>
                                                                <td>28/09/2023</td>
                                                                <td><span class="badge bg-success-subtle text-success">Done</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>4</td>
                                                                <td><img src="assets/images/users/avatar-6.jpg" alt="table-user" class="me-2 rounded-circle" height="24"> Donatien Brunelle</td>
                                                                <td>Drinking bottle graphics</td>
                                                                <td>02/10/2022</td>
                                                                <td>07/05/2023</td>
                                                                <td><span class="badge bg-info-subtle text-info">Work in Progress</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>5</td>
                                                                <td><img src="assets/images/users/avatar-5.jpg" alt="table-user" class="me-2 rounded-circle" height="24"> Karel Auberjo</td>
                                                                <td>Landing page design - Home</td>
                                                                <td>17/01/2022</td>
                                                                <td>25/05/2023</td>
                                                                <td><span class="badge bg-warning-subtle text-warning">Coming soon</span></td>
                                                            </tr>
    
                                                        </tbody>
                                                    </table>
                                                </div>

                                            
                                                <!-- end timeline -->  
    
                                            </div> <!-- end tab-pane -->
                                            <!-- end about me section content -->
    
                                            <div class="tab-pane" id="vehicules">
    
                                                <!-- comment box -->
                                                <div class="border rounded mt-2 mb-3">
                                                    <form action="#" class="comment-area-box">
                                                        <textarea rows="3" class="form-control border-0 resize-none" placeholder="Write something...."></textarea>
                                                        <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <a href="#" class="btn btn-sm px-2 fs-16 btn-light"><i class="ri-contacts-book-2-line"></i></a>
                                                                <a href="#" class="btn btn-sm px-2 fs-16 btn-light"><i class="ri-map-pin-line"></i></a>
                                                                <a href="#" class="btn btn-sm px-2 fs-16 btn-light"><i class="ri-camera-3-line"></i></a>
                                                                <a href="#" class="btn btn-sm px-2 fs-16 btn-light"><i class="ri-emoji-sticker-line"></i></a>
                                                            </div>
                                                            <button type="submit" class="btn btn-sm btn-dark">Post</button>
                                                        </div>
                                                    </form>
                                                </div> <!-- end .border-->
                                                <!-- end comment box -->
    
                                                <!-- Story Box-->
                                                <div class="border border-light rounded p-2 mb-3">
                                                    <div class="d-flex">
                                                        <img class="me-2 rounded-circle" src="assets/images/users/avatar-4.jpg"
                                                            alt="Generic placeholder image" height="32">
                                                        <div>
                                                            <h5 class="m-0">Thelma Fridley</h5>
                                                            <p class="text-muted"><small>about 1 hour ago</small></p>
                                                        </div>
                                                    </div>
                                                    <div class="fs-16 text-center fst-italic text-dark">
                                                        <i class="ri-double-quotes-l fs-20"></i> Cras sit amet nibh libero, in
                                                        gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras
                                                        purus odio, vestibulum in vulputate at, tempus viverra turpis. Duis
                                                        sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper
                                                        porta. Mauris massa.
                                                    </div>
    
                                                    <div class="mx-n2 p-2 mt-3 bg-light">
                                                        <div class="d-flex">
                                                            <img class="me-2 rounded-circle" src="assets/images/users/avatar-3.jpg"
                                                                alt="Generic placeholder image" height="32">
                                                            <div>
                                                                <h5 class="mt-0">Jeremy Tomlinson <small class="text-muted">about 2 minuts ago</small></h5>
                                                                Nice work, makes me think of The Money Pit.
    
                                                                <br/>
                                                                <a href="javascript: void(0);" class="text-muted fs-13 d-inline-block mt-2"><i
                                                                    class="ri-reply-line"></i> Reply</a>
    
                                                                <div class="d-flex mt-3">
                                                                    <a class="pe-2" href="#">
                                                                        <img src="assets/images/users/avatar-4.jpg" class="rounded-circle"
                                                                            alt="Generic placeholder image" height="32">
                                                                    </a>
                                                                    <div>
                                                                        <h5 class="mt-0">Thelma Fridley <small class="text-muted">5 hours ago</small></h5>
                                                                        i'm in the middle of a timelapse animation myself! (Very different though.) Awesome stuff.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
    
                                                        <div class="d-flex mt-2">
                                                            <a class="pe-2" href="#">
                                                                <img src="assets/images/users/avatar-1.jpg" class="rounded-circle"
                                                                    alt="Generic placeholder image" height="32">
                                                            </a>
                                                            <div class="w-100">
                                                                <input type="text" id="simpleinput" class="form-control border-0 form-control-sm" placeholder="Add comment">
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="mt-2">
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-danger"><i
                                                                class="ri-heart-line"></i> Like (28)</a>
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
                                                                class="ri-share-line"></i> Share</a>
                                                    </div>
                                                </div>
    
                                                <!-- Story Box-->
                                                <div class="border border-light rounded p-2 mb-3">
                                                    <div class="d-flex">
                                                        <img class="me-2 rounded-circle" src="assets/images/users/avatar-3.jpg"
                                                            alt="Generic placeholder image" height="32">
                                                        <div>
                                                            <h5 class="m-0">Jeremy Tomlinson</h5>
                                                            <p class="text-muted"><small>3 hours ago</small></p>
                                                        </div>
                                                    </div>
                                                    <p>Story based around the idea of time lapse, animation to post soon!</p>
    
                                                    <img src="assets/images/small/small-1.jpg" alt="post-img" class="rounded me-1"
                                                        height="60" />
                                                    <img src="assets/images/small/small-2.jpg" alt="post-img" class="rounded me-1"
                                                        height="60" />
                                                    <img src="assets/images/small/small-3.jpg" alt="post-img" class="rounded"
                                                        height="60" />
    
                                                    <div class="mt-2">
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
                                                                class="ri-reply-line"></i> Reply</a>
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
                                                                class="ri-heart-line"></i> Like</a>
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
                                                                class="ri-share-line"></i> Share</a>
                                                    </div>
                                                </div>
                                                
                                                <!-- Story Box-->
                                                <div class="border border-light p-2 mb-3">
                                                    <div class="d-flex">
                                                        <img class="me-2 rounded-circle" src="assets/images/users/avatar-6.jpg"
                                                            alt="Generic placeholder image" height="32">
                                                        <div>
                                                            <h5 class="m-0">Martin Williamson</h5>
                                                            <p class="text-muted"><small>15 hours ago</small></p>
                                                        </div>
                                                    </div>
                                                    <p>The parallax is a little odd but O.o that house build is awesome!!</p>
    
                                                    <iframe src='https://player.vimeo.com/video/87993762' height='300' class="img-fluid border-0"></iframe>
                                                </div>
    
                                                <div class="text-center">
                                                    <a href="javascript:void(0);" class="text-danger"><i class="ri-loader-fill me-1"></i> Load more </a>
                                                </div>
    
                                            </div>
                                            <!-- end timeline content-->
    
                                            <div class="tab-pane" id="particuliers">
                                                <form>
                                                    <h5 class="mb-4 text-uppercase"><i class="ri-contacts-book-2-line me-1"></i> Personal Info</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="firstname" class="form-label">First Name</label>
                                                                <input type="text" class="form-control" id="firstname" placeholder="Enter first name">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="lastname" class="form-label">Last Name</label>
                                                                <input type="text" class="form-control" id="lastname" placeholder="Enter last name">
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
    
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label for="userbio" class="form-label">Bio</label>
                                                                <textarea class="form-control" id="userbio" rows="4" placeholder="Write something..."></textarea>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="useremail" class="form-label">Email Address</label>
                                                                <input type="email" class="form-control" id="useremail" placeholder="Enter email">
                                                                <span class="form-text text-muted"><small>If you want to change email please <a href="javascript: void(0);">click</a> here.</small></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="userpassword" class="form-label">Password</label>
                                                                <input type="password" class="form-control" id="userpassword" placeholder="Enter password">
                                                                <span class="form-text text-muted"><small>If you want to change password please <a href="javascript: void(0);">click</a> here.</small></span>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
    
                                                    <h5 class="mb-3 text-uppercase bg-light p-2"><i class="ri-building-line me-1"></i> Company Info</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="companyname" class="form-label">Company Name</label>
                                                                <input type="text" class="form-control" id="companyname" placeholder="Enter company name">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="cwebsite" class="form-label">Website</label>
                                                                <input type="text" class="form-control" id="cwebsite" placeholder="Enter website url">
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
    
                                                    <h5 class="mb-3 text-uppercase bg-light p-2"><i class="ri-global-line me-1"></i> Social</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="social-fb" class="form-label">Facebook</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="ri-facebook-fill"></i></span>
                                                                    <input type="text" class="form-control" id="social-fb" placeholder="Url">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="social-tw" class="form-label">Twitter</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="ri-twitter-line"></i></span>
                                                                    <input type="text" class="form-control" id="social-tw" placeholder="Username">
                                                                </div>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="social-insta" class="form-label">Instagram</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="ri-instagram-line"></i></span>
                                                                    <input type="text" class="form-control" id="social-insta" placeholder="Url">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="social-lin" class="form-label">Linkedin</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="ri-linkedin-fill"></i></span>
                                                                    <input type="text" class="form-control" id="social-lin" placeholder="Url">
                                                                </div>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="social-sky" class="form-label">Skype</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="ri-skype-line"></i></span>
                                                                    <input type="text" class="form-control" id="social-sky" placeholder="@username">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="social-gh" class="form-label">Github</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="ri-github-line"></i></span>
                                                                    <input type="text" class="form-control" id="social-gh" placeholder="Username">
                                                                </div>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                    
                                                    <div class="text-end">
                                                        <button type="submit" class="btn btn-success mt-2"><i class="ri-save-line"></i> Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- end settings content-->
    

                                            <div class="tab-pane" id="entreprises">
    
                                                <!-- comment box -->
                                                <div class="border rounded mt-2 mb-3">
                                                    <form action="#" class="comment-area-box">
                                                        <textarea rows="3" class="form-control border-0 resize-none" placeholder="Write something...."></textarea>
                                                        <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <a href="#" class="btn btn-sm px-2 fs-16 btn-light"><i class="ri-contacts-book-2-line"></i></a>
                                                                <a href="#" class="btn btn-sm px-2 fs-16 btn-light"><i class="ri-map-pin-line"></i></a>
                                                                <a href="#" class="btn btn-sm px-2 fs-16 btn-light"><i class="ri-camera-3-line"></i></a>
                                                                <a href="#" class="btn btn-sm px-2 fs-16 btn-light"><i class="ri-emoji-sticker-line"></i></a>
                                                            </div>
                                                            <button type="submit" class="btn btn-sm btn-dark">Post</button>
                                                        </div>
                                                    </form>
                                                </div> <!-- end .border-->
                                                <!-- end comment box -->
    
                                                <!-- Story Box-->
                                                <div class="border border-light rounded p-2 mb-3">
                                                    <div class="d-flex">
                                                        <img class="me-2 rounded-circle" src="assets/images/users/avatar-4.jpg"
                                                            alt="Generic placeholder image" height="32">
                                                        <div>
                                                            <h5 class="m-0">Thelma Fridley</h5>
                                                            <p class="text-muted"><small>about 1 hour ago</small></p>
                                                        </div>
                                                    </div>
                                                    <div class="fs-16 text-center fst-italic text-dark">
                                                        <i class="ri-double-quotes-l fs-20"></i> Cras sit amet nibh libero, in
                                                        gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras
                                                        purus odio, vestibulum in vulputate at, tempus viverra turpis. Duis
                                                        sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper
                                                        porta. Mauris massa.
                                                    </div>
    
                                                    <div class="mx-n2 p-2 mt-3 bg-light">
                                                        <div class="d-flex">
                                                            <img class="me-2 rounded-circle" src="assets/images/users/avatar-3.jpg"
                                                                alt="Generic placeholder image" height="32">
                                                            <div>
                                                                <h5 class="mt-0">Jeremy Tomlinson <small class="text-muted">about 2 minuts ago</small></h5>
                                                                Nice work, makes me think of The Money Pit.
    
                                                                <br/>
                                                                <a href="javascript: void(0);" class="text-muted fs-13 d-inline-block mt-2"><i
                                                                    class="ri-reply-line"></i> Reply</a>
    
                                                                <div class="d-flex mt-3">
                                                                    <a class="pe-2" href="#">
                                                                        <img src="assets/images/users/avatar-4.jpg" class="rounded-circle"
                                                                            alt="Generic placeholder image" height="32">
                                                                    </a>
                                                                    <div>
                                                                        <h5 class="mt-0">Thelma Fridley <small class="text-muted">5 hours ago</small></h5>
                                                                        i'm in the middle of a timelapse animation myself! (Very different though.) Awesome stuff.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
    
                                                        <div class="d-flex mt-2">
                                                            <a class="pe-2" href="#">
                                                                <img src="assets/images/users/avatar-1.jpg" class="rounded-circle"
                                                                    alt="Generic placeholder image" height="32">
                                                            </a>
                                                            <div class="w-100">
                                                                <input type="text" id="simpleinput" class="form-control border-0 form-control-sm" placeholder="Add comment">
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="mt-2">
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-danger"><i
                                                                class="ri-heart-line"></i> Like (28)</a>
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
                                                                class="ri-share-line"></i> Share</a>
                                                    </div>
                                                </div>
    
                                                <!-- Story Box-->
                                                <div class="border border-light rounded p-2 mb-3">
                                                    <div class="d-flex">
                                                        <img class="me-2 rounded-circle" src="assets/images/users/avatar-3.jpg"
                                                            alt="Generic placeholder image" height="32">
                                                        <div>
                                                            <h5 class="m-0">Jeremy Tomlinson</h5>
                                                            <p class="text-muted"><small>3 hours ago</small></p>
                                                        </div>
                                                    </div>
                                                    <p>Story based around the idea of time lapse, animation to post soon!</p>
    
                                                    <img src="assets/images/small/small-1.jpg" alt="post-img" class="rounded me-1"
                                                        height="60" />
                                                    <img src="assets/images/small/small-2.jpg" alt="post-img" class="rounded me-1"
                                                        height="60" />
                                                    <img src="assets/images/small/small-3.jpg" alt="post-img" class="rounded"
                                                        height="60" />
    
                                                    <div class="mt-2">
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
                                                                class="ri-reply-line"></i> Reply</a>
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
                                                                class="ri-heart-line"></i> Like</a>
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
                                                                class="ri-share-line"></i> Share</a>
                                                    </div>
                                                </div>
                                                
                                                <!-- Story Box-->
                                                <div class="border border-light p-2 mb-3">
                                                    <div class="d-flex">
                                                        <img class="me-2 rounded-circle" src="assets/images/users/avatar-6.jpg"
                                                            alt="Generic placeholder image" height="32">
                                                        <div>
                                                            <h5 class="m-0">Martin Williamson</h5>
                                                            <p class="text-muted"><small>15 hours ago</small></p>
                                                        </div>
                                                    </div>
                                                    <p>The parallax is a little odd but O.o that house build is awesome!!</p>
    
                                                    <iframe src='https://player.vimeo.com/video/87993762' height='300' class="img-fluid border-0"></iframe>
                                                </div>
    
                                                <div class="text-center">
                                                    <a href="javascript:void(0);" class="text-danger"><i class="ri-loader-fill me-1"></i> Load more </a>
                                                </div>
    
                                            </div>
                                            <!-- end timeline content-->
    
                                        </div> <!-- end tab-content -->
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row-->

                    </div>
                    <!-- container -->

                </div>
                <!-- content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <script>document.write(new Date().getFullYear())</script> © Jidox - Coderthemes.com
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-end footer-links d-none d-md-block">
                                    <a href="javascript: void(0);">About</a>
                                    <a href="javascript: void(0);">Support</a>
                                    <a href="javascript: void(0);">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>
            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->

        <!-- Theme Settings -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas">
            <div class="d-flex align-items-center bg-primary p-3 offcanvas-header">
                <h5 class="text-white m-0">Theme Settings</h5>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body p-0">
                <div data-simplebar class="h-100">
                    <div class="card mb-0 p-3">
                        <div class="alert alert-warning" role="alert">
                            <strong>Customize </strong> the overall color scheme, sidebar menu, etc.
                        </div>

                        <h5 class="mt-0 fs-16 fw-bold mb-3">Choose Layout</h5>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check form-switch">
                                <input id="customizer-layout01" name="data-layout" type="checkbox" value="vertical" class="form-check-input">
                                <label class="form-check-label" for="customizer-layout01">Vertical</label>
                            </div>
                            <div class="form-check form-switch">
                                <input id="customizer-layout02" name="data-layout" type="checkbox" value="horizontal" class="form-check-input">
                                <label class="form-check-label" for="customizer-layout02">Accueil</label>
                            </div>
                        </div>

                        <h5 class="my-3 fs-16 fw-bold">Color Scheme</h5>

                        <div class="d-flex flex-column gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="data-bs-theme" id="layout-color-light" value="light">
                                <label class="form-check-label" for="layout-color-light">Light</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="data-bs-theme" id="layout-color-dark" value="dark">
                                <label class="form-check-label" for="layout-color-dark">Dark</label>
                            </div>
                        </div>

                        <div id="layout-width">
                            <h5 class="my-3 fs-16 fw-bold">Layout Mode</h5>

                            <div class="d-flex flex-column gap-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-layout-mode" id="layout-mode-fluid" value="fluid">
                                    <label class="form-check-label" for="layout-mode-fluid">Fluid</label>
                                </div>

                                <div id="layout-boxed">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="data-layout-mode" id="layout-mode-boxed" value="boxed">
                                        <label class="form-check-label" for="layout-mode-boxed">Boxed</label>
                                    </div>
                                </div>

                                <div id="layout-detached">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="data-layout-mode" id="data-layout-detached" value="detached">
                                        <label class="form-check-label" for="data-layout-detached">Detached</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="my-3 fs-16 fw-bold">Topbar Color</h5>

                        <div class="d-flex flex-column gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="data-topbar-color" id="topbar-color-light" value="light">
                                <label class="form-check-label" for="topbar-color-light">Light</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="data-topbar-color" id="topbar-color-dark" value="dark">
                                <label class="form-check-label" for="topbar-color-dark">Dark</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="data-topbar-color" id="topbar-color-brand" value="brand">
                                <label class="form-check-label" for="topbar-color-brand">Brand</label>
                            </div>
                        </div>

                        <div>
                            <h5 class="my-3 fs-16 fw-bold">Menu Color</h5>

                            <div class="d-flex flex-column gap-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-menu-color" id="leftbar-color-light" value="light">
                                    <label class="form-check-label" for="leftbar-color-light">Light</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-menu-color" id="leftbar-color-dark" value="dark">
                                    <label class="form-check-label" for="leftbar-color-dark">Dark</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-menu-color" id="leftbar-color-brand" value="brand">
                                    <label class="form-check-label" for="leftbar-color-brand">Brand</label>
                                </div>
                            </div>
                        </div>

                        <div id="sidebar-size">
                            <h5 class="my-3 fs-16 fw-bold">Sidebar Size</h5>

                            <div class="d-flex flex-column gap-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-default" value="default">
                                    <label class="form-check-label" for="leftbar-size-default">Default</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-compact" value="compact">
                                    <label class="form-check-label" for="leftbar-size-compact">Compact</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-small" value="condensed">
                                    <label class="form-check-label" for="leftbar-size-small">Condensed</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-small-hover" value="sm-hover">
                                    <label class="form-check-label" for="leftbar-size-small-hover">Hover View</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-full" value="full">
                                    <label class="form-check-label" for="leftbar-size-full">Full Layout</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-fullscreen" value="fullscreen">
                                    <label class="form-check-label" for="leftbar-size-fullscreen">Fullscreen Layout</label>
                                </div>
                            </div>
                        </div>

                        <div id="layout-position">
                            <h5 class="my-3 fs-16 fw-bold">Layout Position</h5>

                            <div class="btn-group checkbox" role="group">
                                <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-fixed" value="fixed">
                                <label class="btn btn-soft-primary w-sm" for="layout-position-fixed">Fixed</label>

                                <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-scrollable" value="scrollable">
                                <label class="btn btn-soft-primary w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                            </div>
                        </div>

                        <div id="sidebar-user">
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <label class="fs-16 fw-bold m-0" for="sidebaruser-check">Sidebar User Info</label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="sidebar-user" id="sidebaruser-check">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="offcanvas-footer border-top p-3 text-center">
                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-light w-100" id="reset-layout">Reset</button>
                    </div>
                    <div class="col-6">
                        <a href="#" role="button" class="btn btn-primary w-100">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>          
        
        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- Daterangepicker js -->
        <script src="assets/vendor/daterangepicker/moment.min.js"></script>
        <script src="assets/vendor/daterangepicker/daterangepicker.js"></script>
        
        <!-- Apex Charts js -->
        <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>

        <!-- Vector Map js -->
        <script src="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

        <!-- Dashboard App js -->
        <script src="assets/js/pages/demo.dashboard.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>

         <!-- Include Modal creation de compte agent -->
         <?php require_once '_partials/_modal_agent_account.php'; ?>
        
    </body>

<!-- Mirrored from coderthemes.com/jidox/layouts/layouts-horizontal.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 07 Jul 2025 09:13:58 GMT -->
</html>