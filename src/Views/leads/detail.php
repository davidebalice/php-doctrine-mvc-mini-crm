<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-address-card" aria-hidden="true" style="font-size:22px"></i>
                <h1>Lead detail</h1>
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
                <li class="tab" id="tab-note" onclick="showTab('note')">Note</li>
                <li class="tab" id="tab-call" onclick="showTab('call')">Call</li>
                <li class="tab" id="tab-task" onclick="showTab('task')">Task</li>
            </ul>

            <div class="tab-content" id="content-detail">
                <p><?php echo $lead->getFirstName(); ?></p>
            </div>
            <div class="tab-content" id="content-history">
                <p>History content goes here.</p>
            </div>
            <div class="tab-content" id="content-note">
                <p>Note content goes here.</p>
            </div>
            <div class="tab-content" id="content-call">
                <p>Call content goes here.</p>
            </div>
            <div class="tab-content" id="content-task">
                <p>Task content goes here.</p>
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