<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-address-card" aria-hidden="true" style="font-size:22px"></i>
                <h1>Lead details -> Calls</h1>
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
    </div>

    <div class="page-body">
        <div class="tabs-container">
            <?php
                $currentTab="calls";
                include('menu.php');
            ?>

            <div class="tab-content" id="content-detail">
                <h2>Calls</h2>

                <div class="button add-button" id="addCallBtn">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    <span>Add call</span>
                </div>
         
                <!-- Modal di aggiunta -->
                <div id="addCallForm" class="modal">
                    <div class="modal-content">
                        <div class="flex-between">
                            <h3>Add Call</h3>
                            <span class="close flex-center" id="closeAddForm">&times;</span>
                        </div>
                        <form id="addCall" action="/leads/calls/store" class="form-modal" method="POST">
                            <label for="call_time">Date / hour:</label>
                            <input type="datetime-local" id="call_time" name="call_time" required>

                            <label for="status">Status:</label>
                            <select id="status" name="status" required>
                                <option value="">- Select status -</option>
                                <option value="Canceled">Canceled</option>
                                <option value="Completed">Completed</option>
                                <option value="No answer">No answer</option>
                                <option value="Pending">Pending</option>
                                <option value="Rescheduled">Rescheduled</option>
                                <option value="Voicemail left">Voicemail left</option>
                            </select>

                            <label for="notes">Note:</label>
                            <textarea id="notes" name="notes" required></textarea>
                            <input type="hidden" id="lead_id" name="lead_id" value="<? echo $lead->getId();?>" required>
                            <button type="submit" class="w100">Add</button>
                        </form>
                    </div>
                </div>

                <!-- Modal di modifica -->
                <div id="editCallModal" class="modal">
                    <div class="modal-content">
                        <div class="flex-between">
                            <h3>Edit Call</h3>
                            <span class="close flex-center" id="closeEditForm">&times;</span>
                        </div>

                        <form id="editCall" action="/leads/calls/update" class="form-modal" method="POST">
                            <input type="hidden" id="edit_lead_id" name="lead_id" value="<?= $lead->getId();?>" required>
                            <input type="hidden" id="edit_id" name="call_id" required>
                            <label for="edit_call_time">Date / time:</label>
                            <input type="datetime-local" id="edit_call_time" name="call_time" required>

                            <label for="call_time">Status:</label>
                            <select name="status" id="edit_status" required>
                                <option value="">- Select status -</option>
                                <option value="Canceled">Canceled</option>
                                <option value="Completed">Completed</option>
                                <option value="No answer">No answer</option>
                                <option value="Pending">Pending</option>
                                <option value="Rescheduled">Rescheduled</option>
                                <option value="Voicemail left">Voicemail left</option>
                            </select>

                            <label for="edit_notes">Note:</label>
                            <textarea id="edit_notes" name="notes" required></textarea>

                            <button type="submit" class="w100">Save</button>
                        </form>
                    </div>
                </div>

                <!-- Modal visualizzazione dettagli -->
                <div id="viewCallModal" class="modal">
                    <div class="modal-content modal-detail">
                        <div class="flex-between">
                            <h3>Call Details</h3>
                            <span class="close flex-center" id="closeViewForm">&times;</span>
                        </div>

                        <div id="callDetailsContainer">
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

                <?php if (isset($calls) && count($calls) > 0): ?>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:10%">Date</th>
                                    <th style="width:40%">Note</th>
                                    <th style="width:40%">Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($calls as $call): ?>
                                    <tr>
                                        <td><?= $call->getCallTime()->format('d/m/Y H:i') ?></td>
                                        <td><?= $call->getShortNotes() ?></td>
                                        <td><?= $call->getStatus() ?></td>
                                        <td>
                                            <div class="buttons-container">
                                                <div>
                                                    <button class="viewBtn base-button view-button" data-id="<?= $call->getId(); ?>">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                </div>
                                                <div>
                                                    <button class="editBtn base-button edit-button" data-id="<?= $call->getId(); ?>">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                </div>
                                                <div class="flex-center base-button delete-button"  onclick="confirmDelete(<?php echo $call->getId(); ?>,<?php echo $lead->getId(); ?>)">
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
                    <b>Calls not found</b>
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
            confirmBtn.href = '/leads/calls/'+lead_id+'/delete/' + id;
            modal.style.display = 'flex';
        }
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    // Aggiungi l'event listener per il submit del form
    document.getElementById("editCall").addEventListener("submit", function(event){
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

    // Apre il modulo di aggiunta call
    document.getElementById("addCallBtn").addEventListener("click", function() {
        document.getElementById("addCallForm").style.display = "flex";
    });

    // Chiude il modulo di aggiunta
    document.getElementById("closeAddForm").addEventListener("click", function() {
        document.getElementById("addCallForm").style.display = "none";
    });

    // Aggiungi validazione (come nel codice esistente) se necessario
    document.getElementById("addCall").addEventListener("submit", function(event){
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
            const callId = this.getAttribute("data-id");

            // Effettua la richiesta per ottenere i dati della chiamata
            fetch(`/leads/calls/edit/${callId}`)
                .then(response => response.json())
                .then(data => {

                    const callTime = data.call_time.date.replace(" ", "T").slice(0, 16);

                    // Popola il form con i dati ricevuti
                    document.getElementById("edit_id").value = data.id;
                    document.getElementById("edit_call_time").value = callTime;
                    document.getElementById("edit_notes").value = data.notes;
                    document.getElementById("edit_id").value = callId;
                    
                    // Seleziona lo stato corretto
                    document.getElementById("edit_status").value = data.status;

                    // Mostra la modale
                    document.getElementById("editCallModal").style.display = "flex";
                })
                .catch(error => {
                    console.error("Errore nel recupero della chiamata:", error);
                    alert("Errore nel recupero dei dati della chiamata.");
                });
        });
    });

    // Funzione per chiudere la modal di modifica
    document.getElementById("closeEditForm").addEventListener("click", function() {
        document.getElementById("editCallModal").style.display = "none";
    });

    // Apre la modal di visualizzazione e recupera i dati
    document.querySelectorAll(".viewBtn").forEach(button => {
        button.addEventListener("click", function() {
            const callId = this.getAttribute("data-id");

            // Effettua la richiesta per ottenere i dettagli della chiamata
            fetch(`/leads/calls/detail/${callId}`)
                .then(response => response.json())
                .then(data => {
                    // Popola il contenuto della modal con i dettagli
                    const callTime = new Date(data.call_time.date.replace(" ", "T")).toLocaleString('it-IT', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                    });
                    const status = data.status;
                    const notes = data.notes;

                    const callDetailsHTML = `
                        <p><strong>Date/time:</strong> ${callTime}</p>
                        <p><strong>Status:</strong> ${status}</p>
                        <p><strong>Notes:</strong> <div class=\"notes\">${notes}</div></p>
                    `;

                    // Inserisce i dettagli nella modal
                    document.getElementById("callDetailsContainer").innerHTML = callDetailsHTML;

                    // Mostra la modal
                    document.getElementById("viewCallModal").style.display = "flex";
                })
                .catch(error => {
                    console.error("Errore nel recupero dei dettagli della chiamata:", error);
                    alert("Errore nel recupero dei dettagli.");
                });
        });
    });

    // Funzione per chiudere la modal di visualizzazione
    document.getElementById("closeViewForm").addEventListener("click", function() {
        document.getElementById("viewCallModal").style.display = "none";
    });

</script>