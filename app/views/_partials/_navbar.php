

<div class="topnav">
                <div class="container-fluid">
                    <nav class="navbar navbar-expand-lg">
                        <div class="collapse navbar-collapse" id="topnav-menu-content">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="/" role="button">
                                        <i class="ri-dashboard-2-fill"></i>Accueil
                                    </a>
                                </li>
                               
                                <?php if($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'superadmin') { ?>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layouts" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-user-line"></i>Agents <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-layouts">
                                            <?php if($_SESSION['user']['role'] == 'superadmin') { ?>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#agent-account-modal" class="dropdown-item" >Créer un compte pour un agent</a>
                                            <?php } ?>
                                            
                                            <?php if($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'superadmin') { ?>
                                            <a href="/agents" class="dropdown-item">Gestion des agents</a>
                                            <?php } ?>
                                        </div> 
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>

