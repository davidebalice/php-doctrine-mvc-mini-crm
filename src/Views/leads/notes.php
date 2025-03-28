<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-address-card" aria-hidden="true" style="font-size:22px"></i>
                <h1>Lead > Notes</h1>
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
                $currentTab="notes";
                include('menu.php');
            ?>

            <div class="tab-content" id="content-detail">
                <h2>Notes</h2>

                <div class="button add-button" id="addNoteBtn">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    <span>Add note</span>
                </div>
         
                <!-- Modal di aggiunta -->
                <div id="addNoteForm" class="modal">
                    <div class="modal-content">
                        <div class="flex-between">
                            <h3>Add Note</h3>
                            <span class="close flex-center" id="closeAddForm">&times;</span>
                        </div>
                        <form id="addNote" action="/leads/notes/store" class="form-modal" method="POST">
                            <label for="content">Note:</label>
                            <textarea id="content" name="content" required></textarea>
                            <input type="hidden" id="lead_id" name="lead_id" value="<? echo $lead->getId();?>" required>
                            <button type="submit" class="w100">Add</button>
                        </form>
                    </div>
                </div>

                <!-- Modal di modifica -->
                <div id="editNoteModal" class="modal">
                    <div class="modal-content">
                        <div class="flex-between">
                            <h3>Edit note</h3>
                            <span class="close flex-center" id="closeEditForm">&times;</span>
                        </div>

                        <form id="editNote" action="/leads/notes/update" class="form-modal" method="POST">
                            <input type="hidden" id="edit_lead_id" name="lead_id" value="<?= $lead->getId();?>" required>
                            <input type="hidden" id="edit_id" name="note_id" required>

                            <label for="edit_content">Note:</label>
                            <textarea id="edit_content" name="content" required></textarea>

                            <button type="submit" class="w100">Save</button>
                        </form>
                    </div>
                </div>

                <!-- Modal visualizzazione dettagli -->
                <div id="viewNoteModal" class="modal">
                    <div class="modal-content modal-detail">
                        <div class="flex-between">
                            <h3>Note details</h3>
                            <span class="close flex-center" id="closeViewForm">&times;</span>
                        </div>

                        <div id="noteDetailsContainer">
                            <!-- Dettagli verranno caricati qui -->
                        </div>
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
           
                <?php if (isset($notes) && count($notes) > 0): ?>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:10%">Date</th>
                                    <th style="width:60%">Note</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notes as $note): ?>
                                    <tr>
                                        <td>
                                            <?= $note->getCreatedAt()->format('d/m/Y') ?>
                                        </td>
                                        <td><?= $note->getShortContent() ?></td>
                                        <td>
                                            <div class="buttons-container">
                                                <div>
                                                    <button class="viewBtn base-button view-button" data-id="<?= $note->getId(); ?>">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                </div>
                                                <div>
                                                    <button class="editBtn base-button edit-button" data-id="<?= $note->getId(); ?>">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                </div>
                                                <div class="flex-center base-button delete-button"  onclick="confirmDelete(<?php echo $note->getId(); ?>,<?php echo $lead->getId(); ?>)">
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
                    <b>Notes not found</b>
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
            confirmBtn.href = '/leads/notes/'+lead_id+'/delete/' + id;
            modal.style.display = 'flex';
        }
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    // Aggiungi l'event listener per il submit del form
    document.getElementById("editNote").addEventListener("submit", function(event){
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

    // Apre il modulo di aggiunta note
    document.getElementById("addNoteBtn").addEventListener("click", function() {
        document.getElementById("addNoteForm").style.display = "flex";
    });

    // Chiude il modulo di aggiunta
    document.getElementById("closeAddForm").addEventListener("click", function() {
        document.getElementById("addNoteForm").style.display = "none";
    });

    // Aggiungi validazione (come nel codice esistente) se necessario
    document.getElementById("addNote").addEventListener("submit", function(event){
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
            const noteId = this.getAttribute("data-id");

            // Effettua la richiesta per ottenere i dati della chiamata
            fetch(`/leads/notes/edit/${noteId}`)
                .then(response => response.json())
                .then(data => {

                    // Popola il form con i dati ricevuti
                    document.getElementById("edit_id").value = data.id;
                    document.getElementById("edit_content").value = data.content;
                    document.getElementById("edit_id").value = noteId;
                    
                    // Mostra la modale
                    document.getElementById("editNoteModal").style.display = "flex";
                })
                .catch(error => {
                    console.error("Errore nel recupero della chiamata:", error);
                    alert("Errore nel recupero dei dati della chiamata.");
                });
        });
    });

    // Funzione per chiudere la modal di modifica
    document.getElementById("closeEditForm").addEventListener("click", function() {
        document.getElementById("editNoteModal").style.display = "none";
    });

    // Apre la modal di visualizzazione e recupera i dati
    document.querySelectorAll(".viewBtn").forEach(button => {
        button.addEventListener("click", function() {
            const noteId = this.getAttribute("data-id");

            // Effettua la richiesta per ottenere i dettagli della chiamata
            fetch(`/leads/notes/detail/${noteId}`)
                .then(response => response.json())
                .then(data => {
                    // Popola il contenuto della modal con i dettagli
                    const content = data.content;

                    const noteDetailsHTML = `
                        <p><strong>Note:</strong> <div class=\"notes\">${content}</div></p>
                    `;

                    // Inserisce i dettagli nella modal
                    document.getElementById("noteDetailsContainer").innerHTML = noteDetailsHTML;

                    // Mostra la modal
                    document.getElementById("viewNoteModal").style.display = "flex";
                })
                .catch(error => {
                    console.error("Errore nel recupero dei dettagli della chiamata:", error);
                    alert("Errore nel recupero dei dettagli.");
                });
        });
    });

    // Funzione per chiudere la modal di visualizzazione
    document.getElementById("closeViewForm").addEventListener("click", function() {
        document.getElementById("viewNoteModal").style.display = "none";
    });

</script>