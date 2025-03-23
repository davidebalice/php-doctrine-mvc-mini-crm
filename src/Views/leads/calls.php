<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-address-card" aria-hidden="true" style="font-size:22px"></i>
                <h1>Lead details</h1>
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

                            <label for="call_time">Status:</label>
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
               
                <?php if (!empty($calls)): ?>
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
                                <?php foreach ($calls as $call): ?>
                                    <tr>
                                        <td><?= $call->getCallTime()->format('d/m/Y H:i') ?></td>
                                        <td><?= $call->getNotes() ?></td>
                                        <td>
                                            <div class="buttons-container">

                                            <button class="editBtn" data-id="<?= $call->getId(); ?>">Modifica</button>

                                                <a href="/leads/call/edit/<?php echo $call->getId(); ?>">
                                                    <div class="flex-center base-button edit-button ">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </div>
                                                </a>
                                                <div class="flex-center base-button delete-button"  onclick="confirmDelete(<?php echo $call->getId(); ?>)">
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
                    <p>Calls not found</p>
                <?php endif; ?>
            
            </div>
        </div>
    </div>
</div>

<!-- Modale di modifica -->
<div id="editCallModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEditForm">&times;</span>
        <h3>Modifica Call</h3>
        <form id="editCall" action="/leads/call/edit" method="POST">
            <input type="hidden" id="edit_call_id" name="call_id">
            <label for="edit_call_time">Data e Ora:</label>
            <input type="datetime-local" id="edit_call_time" name="call_time" required>

            <label for="edit_notes">Note:</label>
            <textarea id="edit_notes" name="notes" required></textarea>

            <button type="submit">Salva</button>
        </form>
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

/*
document.getElementById("calls-form").addEventListener("submit", function(event) {
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
*/

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

// Apre modal di modifica
document.querySelectorAll(".editBtn").forEach(button => {
    button.addEventListener("click", function() {
        // Recupera i dati dalla riga della tabella
        const callId = this.getAttribute("data-id");
        const callTime = this.getAttribute("data-time");
        const callNotes = this.getAttribute("data-notes");

        // Popola il form di modifica con i dati della call
        document.getElementById("edit_call_id").value = callId;
        document.getElementById("edit_call_time").value = callTime;
        document.getElementById("edit_notes").value = callNotes;

        // Mostra la modal
        document.getElementById("editCallModal").style.display = "block"; 
    });
});

// Funzione per chiudere la modal
document.getElementById("closeEditModal").addEventListener("click", function() {
    document.getElementById("editCallModal").style.display = "none";
});


// Chiude la modal di modifica
document.getElementById("closeEditForm").addEventListener("click", function() {
    document.getElementById("editCallForm").style.display = "none";
});

</script>