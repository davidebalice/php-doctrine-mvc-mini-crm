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
        <?php
            include('selected_lead.php');
        ?>
    </div>

    <div class="page-body">
        <div class="tabs-container">
            <?php
                $currentTab="detail";
                include('menu.php');
            ?>
            
            <div class="tab-content" id="content-detail">
                <h2>Lead details</h2>
                <div class="box">
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