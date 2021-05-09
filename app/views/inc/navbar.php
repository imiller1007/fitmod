<!-- This will go in Navbar soon: fixed-top -->
<!-- Along with this in the body(style.css): "padding-top: 65px !important;" -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
    <div class="container">
        <a class="navbar-brand text-white" href="<?php echo URLROOT; ?>/mods">
            <h2>
                <fit style="font-family: 'Montserrat', sans-serif;">FIT</fit><strong><em>MOD</em></strong><i class="fas fa-running"></i>
            </h2>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="#"  data-bs-toggle="popover" data-bs-placement="bottom" data-bs-trigger="focus" data-bs-content="Coming Soon!">Trending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo URLROOT; ?>/mods">Newest</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" data-bs-toggle="popover" data-bs-placement="bottom" data-bs-trigger="focus" data-bs-content="Coming Soon!">Search</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Hi, <?php echo $_SESSION['user_first'] .' '. substr($_SESSION['user_last'], 0, 1).'.'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">My Mods</a></li>
                            <li><a class="dropdown-item" href="#">My Schedule</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/users/logout">Sign out</a></li>
                        </ul>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo URLROOT; ?>/users/login">Log in</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo URLROOT; ?>/users/register">Sign up</a>
                    </li>
                <?php endif; ?>
            </ul>


            <!-- <li class="nav-item active">
              <a class="nav-link text-white" href="#">Active Page<span class="sr-only">(current)</span></a>
            </li> -->
        </div>
    </div>

</nav>