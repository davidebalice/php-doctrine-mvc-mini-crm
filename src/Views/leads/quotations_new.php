<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-address-card" aria-hidden="true" style="font-size:22px"></i>
                <h1>New lead</h1>
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


    <form action="/leads/quotations/store" method="post" class="form form2" id="leads-form">
        <h2>New quotation</h2>
        <label>Titolo:</label>
        <input type="text" name="title" required>
        
        <label>Codice:</label>
        <input type="text" name="code" required>

        <label>Stato:</label>
        <select name="status" required>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
        </select>

        <h3>Items</h3>
        <div id="items-container"></div>
        <button type="button" onclick="addItem()">Aggiungi Item</button>

        <h3>Totale: €<span id="total">0.00</span></h3>
        
        <button type="submit">Crea Preventivo</button>
        <p id="error-message" style="color: red; display: none;"></p>
        <input type="hidden" name="lead_id" value="<?= $lead_id?>" required>
    </form>












      
        </div>
    </div>
</div>

<script>
function addItem() {
    let itemsContainer = document.getElementById("items-container");
    let index = document.querySelectorAll(".item-row").length;
    
    let row = document.createElement("div");
    row.classList.add("item-row");
    row.innerHTML = `
        <input type="text" name="items[${index}][service_name]" placeholder="Servizio" required>
        <input type="text" name="items[${index}][description]" placeholder="Descrizione">
        <input type="number" name="items[${index}][price]" placeholder="Prezzo" step="0.01" required oninput="updateTotal()">
        <input type="number" name="items[${index}][quantity]" placeholder="Quantità" min="1" required value="1" oninput="updateTotal()">
        <button type="button" onclick="removeItem(this)">Rimuovi</button>
    `;
    itemsContainer.appendChild(row);
    updateTotal();
}

function removeItem(button) {
    button.parentElement.remove();
    updateTotal();
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll(".item-row").forEach(row => {
        let price = parseFloat(row.querySelector("[name*='[price]']").value) || 0;
        let quantity = parseInt(row.querySelector("[name*='[quantity]']").value) || 1;
        total += price * quantity;
    });
    document.getElementById("total").innerText = total.toFixed(2);
}


// Variabile per indicare se il sistema è in modalità demo
const demoMode = <?php echo DEMO_MODE ? 'true' : 'false'; ?>;

// Aggiungi l'event listener per il submit del form
document.getElementById("leads-form").addEventListener("submit", function(event){
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

document.getElementById("leads-form").addEventListener("submit", function(event) {
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