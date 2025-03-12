<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? $title : '' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <a href="/dashboard">Dashboard</a>
            <a href="/logout">Logout</a>
        </nav>
    </header>

    <!-- Contenuto Dinamico -->
    <main>
        <?php include $content; ?>  <!-- Qui viene inserito il contenuto dinamico -->
    </main>

    <!-- Footer -->
    <footer>
        <p>www.davidebalice.dev</p>
    </footer>
</body>
</html>
