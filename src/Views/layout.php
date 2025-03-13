<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? $title : '' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..700;1,100..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <img src="/images/logo.png" alt="Logo" class="dbLogo">
        <nav>
            <a href="/dashboard">Dashboard</a>
            <a href="/logout">Logout</a>
        </nav>
        <img src="/images/github.png" alt="Logo" class="githubLogo">
    </header>

    <!-- Contenuto Dinamico -->
    <main>
        <?php
            if(isset($user)){
                include __DIR__ . '/admin/siderbar.php';
            }
        ?>
        <?php include $content; ?>  <!-- Qui viene inserito il contenuto dinamico -->
    </main>

    <!-- Footer -->
    <footer>
        <a href="https://www.davidebalice.dev" target="_blank">www.davidebalice.dev</a>
    </footer>
</body>
</html>
