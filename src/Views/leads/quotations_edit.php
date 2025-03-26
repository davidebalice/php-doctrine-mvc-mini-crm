<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-address-card" aria-hidden="true" style="font-size:22px"></i>
                <h1>Edit Quotation</h1>
            </div>
        </div>
    </div>
    <div class="page-subheader">
        <a href="/leads/quotations/<?= $lead->getId() ?>">
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


        <form action="/leads/quotations/update" method="post" class="form form2" id="leads-form">
            <h2>Edit Quotation</h2>
            <label>Title:</label>
            <input type="text" name="title" value="<?= $quotation->getTitle() ?>" required>
            
            <label>Code:</label>
            <input type="text" name="code" value="<?= $quotation->getCode() ?>" required>

            <label>Status:</label>
            <select name="status" required>
                <option value="Pending" <?= $quotation->getStatus() == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Approved" <?= $quotation->getStatus() == 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Rejected" <?= $quotation->getStatus() == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>

            <h3>Items</h3>
            <div id="items-container">
                <?php foreach ($quotation->getItems() as $index => $item) : ?>
                    <div class="item-row">
                        <input type="text" name="items[<?= $index ?>][service_name]" value="<?= $item->getServiceName() ?>" required>
                        <input type="text" name="items[<?= $index ?>][description]" value="<?= $item->getDescription() ?>">
                        <input type="number" name="items[<?= $index ?>][price]" value="<?= $item->getPrice() ?>" step="0.01" required oninput="updateTotal()">
                        <input type="number" name="items[<?= $index ?>][quantity]" value="<?= $item->getQuantity() ?>" min="1" required oninput="updateTotal()">
                        <button type="button" onclick="removeItem(this)">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" onclick="addItem()">Add Item</button>

            <h3>Total: €<span id="total">0.00</span></h3>
            
            <button type="submit">Update Quotation</button>
            <input type="hidden" name="lead_id" value="<?= $lead->getId() ?>">
            <input type="hidden" name="id" value="<?= $quotation->getId() ?>">
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
        <input type="text" name="items[${index}][service_name]" placeholder="Service" required>
        <input type="text" name="items[${index}][description]" placeholder="Description">
        <input type="number" name="items[${index}][price]" placeholder="Price" step="0.01" required oninput="updateTotal()">
        <input type="number" name="items[${index}][quantity]" placeholder="Quantity" min="1" required value="1" oninput="updateTotal()">
        <button type="button" onclick="removeItem(this)">Remove</button>
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

updateTotal();
</script>
