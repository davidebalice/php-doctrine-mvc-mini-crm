<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-address-card" aria-hidden="true" style="font-size:22px"></i>
                <h1>Lead details  -> Tasks</h1>
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
                $currentTab="tasks";
                include('menu.php');
            ?>

            <div class="tab-content" id="content-detail">
                <h2>Tasks</h2>

                <div class="button add-button" id="addTaskBtn">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    <span>Add task</span>
                </div>
         
                <!-- Modal di aggiunta -->
                <div id="addTaskForm" class="modal">
                    <div class="modal-content">
                        <div class="flex-between">
                            <h3>Add Task</h3>
                            <span class="close flex-center" id="closeAddForm">&times;</span>
                        </div>
                        <form id="addTask" action="/leads/tasks/store" class="form-modal" method="POST">
                            <label for="task_time">Date / hour:</label>
                            <input type="datetime-local" id="task_time" name="task_time" required>

                            <label for="status">Status:</label>
                            <select id="status" name="status" required>
                                <option value="">- Select status -</option>
                                <option value="To Do">To Do</option>
                                <option value="In progress">In progress</option>
                                <option value="Blocked">Blocked</option>
                                <option value="Review">Review</option>
                                <option value="Done">Done</option>
                                <option value="Canceled">Canceled</option>
                                <option value="Failed">Failed</option>
                            </select>

                            <label for="notes">Note:</label>
                            <textarea id="notes" name="notes" required></textarea>
                            <input type="hidden" id="lead_id" name="lead_id" value="<? echo $lead->getId();?>" required>
                            <button type="submit" class="w100">Add</button>
                        </form>
                    </div>
                </div>

                <!-- Modal di modifica -->
                <div id="editTaskModal" class="modal">
                    <div class="modal-content">
                        <div class="flex-between">
                            <h3>Edit Task</h3>
                            <span class="close flex-center" id="closeEditForm">&times;</span>
                        </div>

                        <form id="editTask" action="/leads/tasks/update" class="form-modal" method="POST">
                            <input type="hidden" id="edit_lead_id" name="lead_id" value="<?= $lead->getId();?>" required>
                            <input type="hidden" id="edit_id" name="task_id" required>
                            <label for="edit_task_time">Date / time:</label>
                            <input type="datetime-local" id="edit_task_time" name="task_time" required>

                            <label for="task_time">Status:</label>
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
                <div id="viewTaskModal" class="modal">
                    <div class="modal-content modal-detail">
                        <div class="flex-between">
                            <h3>Task Details</h3>
                            <span class="close flex-center" id="closeViewForm">&times;</span>
                        </div>

                        <div id="taskDetailsContainer">
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

               
                <?php if (!empty($tasks)): ?>
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
                                <?php foreach ($tasks as $task): ?>
                                    <tr>
                                        <td><?= $task->getTaskTime()->format('d/m/Y H:i') ?></td>
                                        <td><?= $task->getShortNotes() ?></td>
                                        <td><?= $task->getStatus() ?></td>
                                        <td>
                                            <div class="buttons-container">
                                                <div>
                                                    <button class="viewBtn base-button view-button" data-id="<?= $task->getId(); ?>">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                </div>
                                                <div>
                                                    <button class="editBtn base-button edit-button" data-id="<?= $task->getId(); ?>">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                </div>
                                                <div class="flex-center base-button delete-button"  onclick="confirmDelete(<?php echo $task->getId(); ?>,<?php echo $lead->getId(); ?>)">
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
                    <p>Tasks not found</p>
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
            confirmBtn.href = '/leads/tasks/'+lead_id+'/delete/' + id;
            modal.style.display = 'flex';
        }
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    // Aggiungi l'event listener per il submit del form
    document.getElementById("editTask").addEventListener("submit", function(event){
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

    // Apre il modulo di aggiunta task
    document.getElementById("addTaskBtn").addEventListener("click", function() {
        document.getElementById("addTaskForm").style.display = "flex";
    });

    // Chiude il modulo di aggiunta
    document.getElementById("closeAddForm").addEventListener("click", function() {
        document.getElementById("addTaskForm").style.display = "none";
    });

    // Aggiungi validazione (come nel codice esistente) se necessario
    document.getElementById("addTask").addEventListener("submit", function(event){
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
            const taskId = this.getAttribute("data-id");

            // Effettua la richiesta per ottenere i dati della chiamata
            fetch(`/leads/tasks/edit/${taskId}`)
                .then(response => response.json())
                .then(data => {

                    const taskTime = data.task_time.date.replace(" ", "T").slice(0, 16);

                    // Popola il form con i dati ricevuti
                    document.getElementById("edit_id").value = data.id;
                    document.getElementById("edit_task_time").value = taskTime;
                    document.getElementById("edit_notes").value = data.notes;
                    document.getElementById("edit_id").value = taskId;
                    
                    // Seleziona lo stato corretto
                    document.getElementById("edit_status").value = data.status;

                    // Mostra la modale
                    document.getElementById("editTaskModal").style.display = "flex";
                })
                .catch(error => {
                    console.error("Errore nel recupero della chiamata:", error);
                    alert("Errore nel recupero dei dati della chiamata.");
                });
        });
    });

    // Funzione per chiudere la modal di modifica
    document.getElementById("closeEditForm").addEventListener("click", function() {
        document.getElementById("editTaskModal").style.display = "none";
    });

    // Apre la modal di visualizzazione e recupera i dati
    document.querySelectorAll(".viewBtn").forEach(button => {
        button.addEventListener("click", function() {
            const taskId = this.getAttribute("data-id");

            // Effettua la richiesta per ottenere i dettagli della chiamata
            fetch(`/leads/tasks/detail/${taskId}`)
                .then(response => response.json())
                .then(data => {
                    // Popola il contenuto della modal con i dettagli
                    const taskTime = new Date(data.task_time.date.replace(" ", "T")).toLocaleString('it-IT', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                    });
                    const status = data.status;
                    const notes = data.notes;

                    const taskDetailsHTML = `
                        <p><strong>Date/time:</strong> ${taskTime}</p>
                        <p><strong>Status:</strong> ${status}</p>
                        <p><strong>Notes:</strong> <div class=\"notes\">${notes}</div></p>
                    `;

                    // Inserisce i dettagli nella modal
                    document.getElementById("taskDetailsContainer").innerHTML = taskDetailsHTML;

                    // Mostra la modal
                    document.getElementById("viewTaskModal").style.display = "flex";
                })
                .catch(error => {
                    console.error("Errore nel recupero dei dettagli della chiamata:", error);
                    alert("Errore nel recupero dei dettagli.");
                });
        });
    });

    // Funzione per chiudere la modal di visualizzazione
    document.getElementById("closeViewForm").addEventListener("click", function() {
        document.getElementById("viewTaskModal").style.display = "none";
    });

</script>