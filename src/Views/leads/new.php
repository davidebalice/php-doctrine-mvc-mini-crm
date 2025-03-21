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
        <form action="/leads/store" method="post" class="form" id="leads-form">
            <h2>New lead</h2>
            <div class="row">
                <div class="col">
                    <label>Name:</label>
                    <input type="text" name="name" class="input-form" data-mandatory="true" required>
                    
                    <label>Phone:</label>
                    <input type="text" name="phone" class="input-form" data-mandatory="true" required>
                   
                    <label>Source:</label>
                    <select name="source" data-mandatory="true" required>
                        <option value="">Select Source</option>

                        <?php foreach ($sources as $source): ?>
                            <option value="<?php echo htmlspecialchars($source->getId()); ?>"><?php echo htmlspecialchars($source->getName()); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label>City:</label>
                    <input type="text" name="city" class="input-form" data-mandatory="true" required>

                    <label>ZIP Code:</label>
                    <input type="text" name="zip" class="input-form" data-mandatory="true" required>
                </div>
                <div class="col">
                    <label>Surname:</label>
                    <input type="text" name="surname" class="input-form" data-mandatory="true" required>
                    
                    <label>Email:</label>
                    <input type="email" name="email" class="input-form" data-mandatory="true" required>

                    <label>Status:</label>
                    <select name="status" data-mandatory="true" required>
                        <option value="">Select Status</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo htmlspecialchars($status->getId()); ?>"><?php echo htmlspecialchars($status->getName()); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label>Address:</label>
                    <input type="text" name="address" class="input-form" data-mandatory="true" required>

                    <label>Country:</label>
                    <input type="text" name="country" class="input-form" data-mandatory="true" required>
                </div>
            </div>

            <br />

            <label>Note:</label>
            <textarea name="notes"></textarea>
            <br /><br />
            <input type="submit" class="input-submit" value="Send">
            <p id="error-message" style="color: red; display: none;"></p>
        </form>
    </div>
</div>

<script>
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