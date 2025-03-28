<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-address-card" aria-hidden="true" style="font-size:22px"></i>
                <h1>Lead > Quotations</h1>
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
                $currentTab="quotations";
                include('menu.php');
            ?>

            <div class="tab-content" id="content-detail">
                <h2>Quotations</h2>
                <a href="/leads/quotations/<?= $lead->getId()?>/create">
                    <div class="button add-button">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <span>Add quotation</span>
                    </div>
                </a>

                <!-- Modal conferma eliminazione -->
                <div id="deleteModal" class="modal">
                    <div class="modal-content modal-delete">
                        <b>Confirm delete?</b>
                        <br /><br />
                        <div class="modal-actions flex-center">
                            <button onclick="closeDeleteModal()" class="button-mini button-mid">
                                <i class="fa-solid fa-xmark"></i>
                                Cancel
                            </button>
                            <a id="confirmDeleteBtn" href="#">
                                <button class="danger button-mini button-mid">
                                    <i class="fa-solid fa-trash-can"></i>
                                    Delete
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
           
                <?php if (isset($quotations) && count($quotations) > 0): ?>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:10%">Date</th>
                                    <th style="width:30%">Title</th>
                                    <th style="width:30%">Status</th>
                                    <th style="width:30%">Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quotations as $quotation): ?>
                                    <tr>
                                        <td>
                                            <?= $quotation->getCreatedAt()->format('d/m/Y') ?>
                                        </td>
                                        <td><?= $quotation->getTitle() ?></td>
                                        <td><?= $quotation->getStatus() ?></td>
                                        <td><?= $quotation->getTotal() ?>
                                    
                                    
                                    
                                <?php foreach ($quotation->getItems() as $item): ?>
                                    <tr>
                                        <td><?php echo $item->getServiceName(); ?></td>
                                        <td><?php echo $item->getDescription(); ?></td>
                                        <td><?php echo $item->getQuantity(); ?></td>
                                        <td>&euro;<?php echo number_format($item->getPrice(), 2, ',', '.'); ?></td>
                                        <td>&euro;<?php echo number_format($item->getSubtotal(), 2, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                    
                                    




                                    
                                    </td>
                                        <td>
                                            <div class="buttons-container">
                                                <div>
                                                    <a href="/leads/quotations/<?= $lead->getId()?>/detail/<?= $quotation->getId()?>">
                                                        <button class="viewBtn base-button view-button" data-id="<?= $quotation->getId(); ?>">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                        </a>
                                                </div>
                                                <div>
                                                    <a href="/leads/quotations/<?= $lead->getId()?>/edit/<?= $quotation->getId()?>">
                                                        <button class="editBtn base-button edit-button">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                    </a>
                                                </div>
                                                <div class="flex-center base-button delete-button"  onclick="confirmDelete(<?php echo $quotation->getId(); ?>,<?php echo $lead->getId(); ?>)">
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
                    <br />
                    <b>Quotations not found</b>
                <?php endif; ?>
            
            </div>
        </div>
    </div>
</div>

<script>
    // Variabile per indicare se il sistema è in modalità demo
    const demoMode = <?php echo DEMO_MODE ? 'true' : 'false'; ?>;

    function confirmDelete(id,lead_id) {
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
            confirmBtn.href = '/leads/quotations/'+lead_id+'/delete/' + id;
            modal.style.display = 'flex';
        }
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    // Aggiungi l'event listener per il submit del form
    document.getElementById("editQuotation").addEventListener("submit", function(event){
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

    // Apre il modulo di aggiunta quotation
    document.getElementById("addQuotationBtn").addEventListener("click", function() {
        document.getElementById("addQuotationForm").style.display = "flex";
    });

    // Chiude il modulo di aggiunta
    document.getElementById("closeAddForm").addEventListener("click", function() {
        document.getElementById("addQuotationForm").style.display = "none";
    });

    // Aggiungi validazione (come nel codice esistente) se necessario
    document.getElementById("addQuotation").addEventListener("submit", function(event){
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

    // Apre modal di modifica e recupera i dati
    document.querySelectorAll(".editBtn").forEach(button => {
            button.addEventListener("click", function() {
            const quotationId = this.getAttribute("data-id");

            // Effettua la richiesta per ottenere i dati della chiamata
            fetch(`/leads/quotations/edit/${quotationId}`)
                .then(response => response.json())
                .then(data => {
                    const due_date = data.due_date.date.replace(" ", "T").slice(0, 16);

                    // Popola il form con i dati ricevuti
                    document.getElementById("edit_id").value = data.id;
                    document.getElementById("edit_due_date").value = due_date;
                    document.getElementById("edit_description").value = data.description;
                    document.getElementById("edit_id").value = quotationId;
                    
                    // Seleziona lo stato corretto
                    document.getElementById("edit_status").value = data.status;

                    // Mostra la modale
                    document.getElementById("editQuotationModal").style.display = "flex";
                })
                .catch(error => {
                    console.error("Errore nel recupero della chiamata:", error);
                    alert("Errore nel recupero dei dati della chiamata.");
                });
        });
    });

    // Funzione per chiudere la modal di modifica
    document.getElementById("closeEditForm").addEventListener("click", function() {
        document.getElementById("editQuotationModal").style.display = "none";
    });

    // Apre la modal di visualizzazione e recupera i dati
    document.querySelectorAll(".viewBtn").forEach(button => {
        button.addEventListener("click", function() {
            const quotationId = this.getAttribute("data-id");

            // Effettua la richiesta per ottenere i dettagli della chiamata
            fetch(`/leads/quotations/detail/${quotationId}`)
                .then(response => response.json())
                .then(data => {
                    // Popola il contenuto della modal con i dettagli
                    const due_date = new Date(data.due_date.date.replace(" ", "T")).toLocaleString('it-IT', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                    });
                    const status = data.status;
                    const description = data.description;

                    const quotationDetailsHTML = `
                        <p><strong>Due date:</strong> ${due_date}</p>
                        <p><strong>Status:</strong> ${status}</p>
                        <p><strong>Description:</strong> <div class=\"notes\">${description}</div></p>
                    `;

                    // Inserisce i dettagli nella modal
                    document.getElementById("quotationDetailsContainer").innerHTML = quotationDetailsHTML;

                    // Mostra la modal
                    document.getElementById("viewQuotationModal").style.display = "flex";
                })
                .catch(error => {
                    console.error("Errore nel recupero dei dettagli della chiamata:", error);
                    alert("Errore nel recupero dei dettagli.");
                });
        });
    });

    // Funzione per chiudere la modal di visualizzazione
    document.getElementById("closeViewForm").addEventListener("click", function() {
        document.getElementById("viewQuotationModal").style.display = "none";
    });

</script>