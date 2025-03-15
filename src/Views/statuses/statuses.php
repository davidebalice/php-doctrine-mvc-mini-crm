<div class="page">
    <div class="page-header">
        <div class="page-wrapper">
            <h1>Statuses</h1>
        </div>
    </div>
    <div class="page-subheader">
        aaa
    </div>
    <div class="page-body">

        <?php if (!empty($statuses)): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Cognome</th>
                            <th>Età</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statuses as $status): ?>
                            <tr>
                                <td><?= htmlspecialchars($status->getName()) ?></td>
                                <td>Verdi</td>
                                <td>40</td>
                                <td>
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <i class="fa-solid fa-trash"></i>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
    
            <!-- Navigazione pagine -->
            <div class="pagination">
                <!-- Bottone "Prev" -->
                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?= $currentPage - 1; ?>">&#8592; Prev</a>
                <?php else: ?>
                    <a href="#" class="disabled">&#8592; Prev</a>
                <?php endif; ?>

                <!-- Numeri di pagina -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i; ?>" class="<?= ($i == $currentPage) ? 'active' : ''; ?>">
                        <?= $i; ?>
                    </a>
                <?php endfor; ?>

                <!-- Bottone "Next" -->
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?= $currentPage + 1; ?>">Next &#8594;</a>
                    <?php else: ?>
                    <a href="#" class="disabled">Next &#8594;</a>
                <?php endif; ?>
            </div>



        <?php else: ?>
            <p>Statuses not found</p>
        <?php endif; ?>
    </div>
</div>
