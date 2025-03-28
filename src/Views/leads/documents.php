<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-address-card" aria-hidden="true" style="font-size:22px"></i>
                <h1>Lead > Documents</h1>
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
                $currentTab="documents";
                include('menu.php');
            ?>

            <div class="tab-content" id="content-detail">
                <h2>Documents</h2>

                <div class="button add-button" id="addDocumentBtn">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    <span>Add document</span>
                </div>
         
                <!-- Modal di aggiunta -->
                <div id="addDocumentForm" class="modal">
                    <div class="modal-content">
                        <div class="flex-between">
                            <h3>Add Document</h3>
                            <span class="close flex-center" id="closeAddForm">&times;</span>
                        </div>
                        <form id="addDocument" action="/leads/documents/store" class="form-modal" method="POST" enctype="multipart/form-data">
                            <label for="title">Title:</label>
                            <input type="text" id="title" name="title" required>

                            <label for="file">Filename:</label>
                            <input type="file" id="file" name="file" required>

                            <input type="hidden" id="lead_id" name="lead_id" value="<? echo $lead->getId();?>" required>
                            <button type="submit" class="w100">Add</button>
                        </form>
                    </div>
                </div>

                <!-- Modal di modifica -->
                <div id="editDocumentModal" class="modal">
                    <div class="modal-content">
                        <div class="flex-between">
                            <h3>Edit Document</h3>
                            <span class="close flex-center" id="closeEditForm">&times;</span>
                        </div>

                        <form id="editDocument" action="/leads/documents/update" class="form-modal" method="POST">
                            <input type="hidden" id="edit_lead_id" name="lead_id" value="<?= $lead->getId();?>" required>
                            <input type="hidden" id="edit_id" name="document_id" required>
                            <label for="edit_title">Title:</label>
                            <input type="text" id="edit_title" name="title" required>
                            <button type="submit" class="w100">Save</button>
                        </form>
                    </div>
                </div>

              
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
           
                <?php if (isset($documents) && count($documents) > 0): ?>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:40%">Title</th>
                                    <th style="width:40%">File</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documents as $document): ?>
                                    <tr>
                                        <td><?= $document->getTitle() ?></td>
                                        <td>
                                            <a href="/uploads/documents/<?= htmlspecialchars($document->getFilename(), ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                                                <u><?= htmlspecialchars($document->getFilename(), ENT_QUOTES, 'UTF-8') ?></u>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="buttons-container">
                                                <a href="/uploads/documents/<?= htmlspecialchars($document->getFilename(), ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                                                    <div>
                                                        <button class="viewBtn base-button view-button" data-id="<?= $document->getId(); ?>">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </a>
                                                <div>
                                                    <button class="editBtn base-button edit-button" data-id="<?= $document->getId(); ?>">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                </div>
                                                <div class="flex-center base-button delete-button"  onclick="confirmDelete(<?php echo $document->getId(); ?>,<?php echo $lead->getId(); ?>)">
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
                    <b>Documents not found</b>
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
            confirmBtn.href = '/leads/documents/'+lead_id+'/delete/' + id;
            modal.style.display = 'flex';
        }
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    // Aggiungi l'event listener per il submit del form
    document.getElementById("editDocument").addEventListener("submit", function(event){
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

    // Apre il modulo di aggiunta document
    document.getElementById("addDocumentBtn").addEventListener("click", function() {
        document.getElementById("addDocumentForm").style.display = "flex";
    });

    // Chiude il modulo di aggiunta
    document.getElementById("closeAddForm").addEventListener("click", function() {
        document.getElementById("addDocumentForm").style.display = "none";
    });

    // Aggiungi validazione (come nel codice esistente) se necessario
    document.getElementById("addDocument").addEventListener("submit", function(event){
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
            const documentId = this.getAttribute("data-id");

            // Effettua la richiesta per ottenere i dati della chiamata
            fetch(`/leads/documents/edit/${documentId}`)
                .then(response => response.json())
                .then(data => {
                    const title = data.title;

                    // Popola il form con i dati ricevuti
                    document.getElementById("edit_id").value = data.id;
                    document.getElementById("edit_title").value = title;
                    document.getElementById("edit_id").value = documentId;
                    
                    // Mostra la modale
                    document.getElementById("editDocumentModal").style.display = "flex";
                })
                .catch(error => {
                    console.error("Errore nel recupero della chiamata:", error);
                    alert("Errore nel recupero dei dati della chiamata.");
                });
        });
    });

    // Funzione per chiudere la modal di modifica
    document.getElementById("closeEditForm").addEventListener("click", function() {
        document.getElementById("editDocumentModal").style.display = "none";
    });

</script>