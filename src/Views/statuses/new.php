<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-bars" aria-hidden="true" style="font-size:22px"></i>
                <h1>New status</h1>
            </div>
        </div>
    </div>
    <div class="page-subheader">
        <a href="/statuses">
            <div class="button">
                <i class="fa fa-angle-left" aria-hidden="true"></i>
                <span>Back</span>
            </div>
        </a>
    </div>
    <div class="page-body">
        <form action="/statuses/create" method="post" class="form" id="status-form">
            <input type="text" name="name" class="input-form" data-mandatory="true" >
            <input type="submit" class="input-submit">
            <p id="error-message" style="color: red; display: none;"></p>
        </form>
    </div>
</div>



<script>
document.getElementById("status-form").addEventListener("submit", function(event) {
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