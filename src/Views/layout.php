<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : '' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..700;1,100..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <img src="/images/logo.png" alt="Logo" class="dbLogo">
        <span>
            Mini Crm - PHP custom Framework
        </span>

        <div class="flex-center">
            <div class="lang-switcher">
                <button class="lang-button" title="Cambia lingua">
                    <i class="fas fa-globe"></i>
                </button>
                <div class="lang-dropdown">
                    <a href="?lang=it">Italiano</a>
                    <a href="?lang=en">English</a>
                </div>
            </div>

            <a href="https://github.com/davidebalice/php-doctrine-mvc-mini-crm" target="_blank">
                <img src="/images/github.png" alt="Logo" class="githubLogo">
            </a>
        </div>
    </header>

    <!-- Contenuto Dinamico -->
    <main>
        <?php
            if(isset($user)){
                include __DIR__ . '/admin/sidebar.php';
            }
        ?>
        <?php include $content; ?>
    </main>

    <!-- Footer -->
    <footer>
        <a href="https://www.davidebalice.dev" target="_blank">www.davidebalice.dev</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   

</body>
</html>
