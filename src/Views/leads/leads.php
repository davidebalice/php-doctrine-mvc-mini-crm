<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-address-card" aria-hidden="true" style="font-size:22px"></i>
                <h1>Leads</h1>
            </div>
        </div>
    </div>
    <div class="page-subheader">
        <a href="/leads/create">
            <div class="button">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <span>Add lead</span>
            </div>
        </a>
        <div>
            <form class="search-form">
                <span>Search:</span>
                <input type="text" name="search" value="<?= (isset($search)&&($search!="")) ? $search : '' ?>" required>
                <button type="submit" class="search-button">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="page-body">
        
        <?php
            if(isset($search)&&($search!="")){
        ?>
            <div class="top-table">
                <div class="flex">
                    <p>Search term: <strong><?= $search ?></strong></p>
                    <a href="/leads" class="flex-center button-mini">View all</a>
                </div>
            </div>
            
        <?php
            }
        ?>

        <?php if (!empty($leads)): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                        <th style="width:30%">Surname name</th>
                        <th style="width:15%">Status</th>
                        <th style="width:15%">Source</th>
                        <th style="width:15%">Assigned user</th>
                        <th style="width:15%">Data</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leads as $lead): ?>
                            <tr>
                                <td><?= $lead->getLastName() ?> <?= $lead->getFirstName() ?></td>
                                <td><?= $lead->getStatus()->getName() ?></td>
                                <td><?= $lead->getSource()->getName() ?></td>
                                <td><?= $lead->getAssignedUser()->getName() ?></td>
                                <td><?= $lead->getCreatedAt()->format('d/m/Y H:i')  ?></td>
                                <td>
                                    <div class="buttons-container">
                                        
                                       
                                    
                                        <a href="/leads/detail/<?php echo $lead->getId(); ?>">
                                            <div class="flex-center base-button detail-button ">
                                                <i class="fa-regular fa-address-card"></i>
                                                Detail
                                            </div>
                                        </a>
                                    
                                        <a href="/leads/edit/<?php echo $lead->getId(); ?>">
                                            <div class="flex-center base-button edit-button ">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </div>
                                        </a>

                                        <div class="flex-center base-button delete-button"  onclick="confirmDelete(<?php echo $lead->getId(); ?>)">
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
                    <a href="?page=<?= $currentPage - 1; ?>"><i class="fa-solid fa-backward-step"></i> Prev</a>
                <?php else: ?>
                    <a href="#" class="disabled"><i class="fa-solid fa-backward-step"></i> Prev</a>
                <?php endif; ?>

                <!-- Numeri di pagina -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i; ?>" class="<?= ($i == $currentPage) ? 'active' : ''; ?>">
                        <?= $i; ?>
                    </a>
                <?php endfor; ?>

                <!-- Bottone "Next" -->
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?= $currentPage + 1; ?>">Next <i class="fa-solid fa-forward-step"></i></a>
                    <?php else: ?>
                    <a href="#" class="disabled">Next <i class="fa-solid fa-forward-step"></i></a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <p>Leads not found</p>
        <?php endif; ?>
    </div>
</div>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>Confirm delete?</p>
        <div class="modal-actions flex-center">
            <button onclick="closeModal()">Cancel</button>
            <a id="confirmDeleteBtn" href="#"><button class="danger">Delete</button></a>
        </div>
    </div>
</div>

<script>
    // Variabile per la modalità demo
    const demoMode = <?php echo DEMO_MODE ? 'true' : 'false'; ?>;

    function confirmDelete(id) {
        if (demoMode) {
            // Se è in modalità demo, mostriamo il messaggio di avviso
            Swal.fire({
                title: "Demo mode",
                text: "Crud operations not allowed",
                icon: "error",
                confirmButtonText: "Ok"
            });
        } else {
            // Se non è in modalità demo, mostra il modale di conferma per la cancellazione
            let modal = document.getElementById('deleteModal');
            let confirmBtn = document.getElementById('confirmDeleteBtn');
            
            // Imposta l'azione di cancellazione sul bottone di conferma
            confirmBtn.href = '/leads/delete/' + id;
            modal.style.display = 'flex';
        }
    }

    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
</script>

