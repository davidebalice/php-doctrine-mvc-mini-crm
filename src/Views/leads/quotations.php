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
                $currentTab="quotations";
                include('menu.php');
            ?>

            <div class="tab-content" id="content-detail">
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