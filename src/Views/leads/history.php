<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-address-card" aria-hidden="true" style="font-size:22px"></i>
                <h1>Lead > History</h1>
            </div>
        </div>
    </div>
    <div class="page-subheader">
        <a href="/leads">
            <div class="button">
                <i class="fa fa-angle-left" aria-hidden="true"></i>
                <span>Back</span>
            </div>
        </a>
        <?php
            include('selected_lead.php');
        ?>
    </div>

    <div class="page-body">
        <div class="tabs-container">
            <?php
                $currentTab="history";
                include('menu.php');
            ?>

            <div class="tab-content" id="content-detail">
                <h2>History</h2>
               
                <?php if (!empty($histories)): ?>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:10%">Date</th>
                                    <th style="width:90%">Event</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($histories as $history): ?>
                                    <tr>
                                        <td><?= $history->getCreatedAt()->format('d/m/Y H:i') ?></td>
                                        <td><?= $history->getEvent() ?></td>
                                        <td>
                                            <div class="buttons-container">
                                                <a href="/leads/history/edit/<?php echo $history->getId(); ?>">
                                                    <div class="flex-center base-button edit-button ">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </div>
                                                </a>
                                                <div class="flex-center base-button delete-button"  onclick="confirmDelete(<?php echo $history->getId(); ?>)">
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
                    <p>History not found</p>
                <?php endif; ?>
            
            </div>
        </div>
    </div>
</div>

<script>
    function showTab(tab) {
        // Hide all tab contents
        let contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => content.style.display = 'none');

        // Remove active class from all tabs
        let tabs = document.querySelectorAll('.tab');
        tabs.forEach(tabItem => tabItem.classList.remove('active'));

        // Show selected tab content
        document.getElementById('content-' + tab).style.display = 'block';

        // Add active class to selected tab
        document.getElementById('tab-' + tab).classList.add('active');
    }

// Variabile per indicare se il sistema è in modalità demo
const demoMode = <?php echo DEMO_MODE ? 'true' : 'false'; ?>;

// Aggiungi l'event listener per il submit del form
document.getElementById("histories-form").addEventListener("submit", function(event){
    event.preventDefault(); // Blocca l'invio del form

    if (demoMode) {
        // Mostra il messaggio se è in modalità demo
        Swal.fire({
            title: "Demo Mode",
            text: "Crud operations not allowed",
            icon: "error",
            confirmButtonText: "Ok"
        });
    } else {
        // Se non è in modalità demo, invia il modulo
        this.submit();
    }
});
</script>

<script>
document.getElementById("histories-form").addEventListener("submit", function(event) {
    let isValid = true;
    let errorMessage = "";
    
    document.querySelectorAll("[data-mandatory='true']").forEach(input => {
        if (input.value.trim() === "") {
            isValid = false;
            errorMessage += `Field "${input.name}" is mandatory.<br>`;
        }
    });

    if (!isValid) {
        event.preventDefault(); // Blocca l'invio del form
        let errorElement = document.getElementById("error-message");
        errorElement.innerHTML = errorMessage;
        errorElement.style.display = "block";
    }
});
</script>