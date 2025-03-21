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
            <ul class="tabs">
                <li class="tab active" id="tab-detail" onclick="showTab('detail')">Detail</li>
                <li class="tab" id="tab-history" onclick="showTab('history')">History</li>
                <li class="tab" id="tab-note" onclick="showTab('note')">Notes</li>
                <li class="tab" id="tab-call" onclick="showTab('call')">Calls</li>
                <li class="tab" id="tab-task" onclick="showTab('task')">Tasks</li>
                <li class="tab" id="tab-quotation" onclick="showTab('quotation')">Quotations</li>
            </ul>

            <div class="tab-content" id="content-detail">
                <h2>Lead details</h2>
               
                    <div class="detail-row">
                        <div>Name:</div>
                        <div><?php echo $lead->getFirstName() ?></div>
                    </div>

                    <div class="detail-row">
                        <div>Surname:</div>
                        <div><?php echo $lead->getLastName(); ?></div>
                    </div>

                    <div class="detail-row">
                        <div>Status:</div>
                        <div><?php echo $lead->getStatus()->getName(); ?></div>
                    </div>

                    <div class="detail-row">
                        <div>Source:</div>
                        <div><?php echo $lead->getSource()->getName(); ?></div>
                    </div>

                    <div class="detail-row">
                        <div>City:</div>
                        <div><?php echo $lead->getCity(); ?></div>
                    </div>

                    <div class="detail-row">
                        <div>Address:</div>
                        <div><?php echo $lead->getAddress(); ?></div>
                    </div>

                    <div class="detail-row">
                        <div>ZIP:</div>
                        <div><?php echo $lead->getZip(); ?></div>
                    </div>

                    <div class="detail-row">
                        <div>Phone:</div>
                        <div><?php echo $lead->getPhone(); ?></div>
                    </div>

                    <div class="detail-row">
                        <div>Email:</div>
                        <div><?php echo $lead->getEmail(); ?></div>
                    </div>

                    <div class="detail-row">
                        <div>Created at:</div>
                        <div><?php echo $lead->getCreatedAt()->format('d/m/Y H:i'); ?></div>
                    </div>
                   
            





            </div>
            <div class="tab-content" id="content-history">
                <h2>History</h2>
            </div>
            <div class="tab-content" id="content-note">
                <h2>Notes</h2>
            </div>
            <div class="tab-content" id="content-call">
                <h2>Calls</h2>
            </div>
            <div class="tab-content" id="content-task">
                <h2>Tasks</h2>
            </div>
            <div class="tab-content" id="content-quotation">
                <h2>Quotations</h2>
                <?php foreach ($lead->getQuotations() as $quotation): ?>
                    <div class="quotation">
                        <h3><?php echo $quotation->getTitle(); ?></h3>
                        <p><strong>Data:</strong> <?php echo $quotation->getCreatedAt(); ?></p>
                        <table>
                            <thead>
                                <tr>
                                    <th>Servizio</th>
                                    <th>Descrizione</th>
                                    <th>Quantità</th>
                                    <th>Prezzo Unitario</th>
                                    <th>Subtotale</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quotation->getItems() as $item): ?>
                                    <tr>
                                        <td><?php echo $item->getServiceName(); ?></td>
                                        <td><?php echo $item->getDescription(); ?></td>
                                        <td><?php echo $item->getQuantity(); ?></td>
                                        <td>&euro;<?php echo number_format($item->getPrice(), 2, ',', '.'); ?></td>
                                        <td>&euro;<?php echo number_format($item->getSubtotal(), 2, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
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
document.getElementById("sources-form").addEventListener("submit", function(event){
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
document.getElementById("sources-form").addEventListener("submit", function(event) {
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