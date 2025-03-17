<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-bars" aria-hidden="true" style="font-size:22px"></i>
                <h1>Statuses</h1>
            </div>
        </div>
    </div>
    <div class="page-subheader">
        <a href="/statuses/create">
            <div class="button">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <span>Aggiungi status</span>
            </div>
        </a>
    </div>
    <div class="page-body">

        <?php if (!empty($statuses)): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width:90%">Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statuses as $status): ?>
                            <tr>
                                <td><?= htmlspecialchars($status->getName()) ?></td>
                                <td>
                                    <div class="buttons-container">
                                        <a href="">
                                            <div class="flex-center base-button edit-button ">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </div>
                                        </a>

                                        
                                        <div class="flex-center base-button delete-button"  onclick="confirmDelete(<?php echo $status->getId(); ?>)">
                                            <i class="fa-solid fa-trash"></i>
                                        </div>
                                        
                                    </div>
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



<div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>Sei sicuro di voler eliminare questo status?</p>
        <div class="modal-actions">
            <button onclick="closeModal()">Annulla</button>
            <a id="confirmDeleteBtn" href="#"><button class="danger">Elimina</button></a>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        let modal = document.getElementById('deleteModal');
        let confirmBtn = document.getElementById('confirmDeleteBtn');

        confirmBtn.href = '/statuses/delete/' + id;
        modal.style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
</script>
