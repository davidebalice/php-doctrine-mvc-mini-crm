<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-address-card" aria-hidden="true" style="font-size:22px"></i>
                <h1>Edit source</h1>
            </div>
        </div>
    </div>
    <div class="page-subheader">
        <a href="/sources">
            <div class="button">
                <i class="fa fa-angle-left" aria-hidden="true"></i>
                <span>Back</span>
            </div>
        </a>
    </div>
    <div class="page-body">
        <form action="/sources/update" method="post" class="form" id="sources-form">
            <label>Name of source:</label>
            <input type="text" name="name" class="input-form" data-mandatory="true" required value="<?php echo $source->getName(); ?>">
            <input type="hidden" name="id" value="<?php echo $source->getId(); ?>">
            <input type="submit" class="input-submit" value="Save">
            <p id="error-message" style="color: red; display: none;"></p>
        </form>
    </div>
</div>

<script>
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