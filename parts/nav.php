<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="home.php"><img src="/assets/images/logo.png" alt="" width="30" height="24" class="d-inline-block align-text-top"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/views/home.php" tabindex="-1" aria-disabled="false" title="Home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/views/instagram.php" tabindex="-1" aria-disabled="false" title="Instagram">Instagram</a>
                </li>
                <?php if ($userLogged) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/views/dev.php" tabindex="-1" aria-disabled="false" title="DEV" rel="nofollow">DEV</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="/views/policies.php" tabindex="-1" aria-disabled="false" title="Datenschutzrichtlinie">Datenschutzrichtlinie</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Tools
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li class="nav-item">
                            <a class="dropdown-item" href="/views/tools/textify.php" title="Textify">Textify</a>
                        </li>
                        <li class="nav-item">
                            <a class="dropdown-item" href="/views/tools/pw_hasher.php" title="PW Hasher">PW Hasher</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    </ul>
                </li>
            </ul>
            <?php if ($userLogged) : ?>
                <form method="post">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="logout">Logout</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</nav>