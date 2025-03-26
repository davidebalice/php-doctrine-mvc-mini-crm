<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-address-card" aria-hidden="true" style="font-size:22px"></i>
                <h1>Quotation details</h1>
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


    <?php
        if (!isset($quotation)) {
            echo "<p>Quotation not found.</p>";
            return;
        }
        ?>

        <h2>Quotation Details</h2>

        <table>
            <tr>
                <th>Created At:</th>
                <td><?= $quotation->getCreatedAt()->format('d/m/Y') ?></td>
            </tr>
            <tr>
                <th>Title:</th>
                <td><?= htmlspecialchars($quotation->getTitle(), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
            <tr>
                <th>Code:</th>
                <td><?= htmlspecialchars($quotation->getCode(), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
            <tr>
                <th>Status:</th>
                <td><?= htmlspecialchars($quotation->getStatus(), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        </table>

        <h3>Items</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalAmount = 0;
                foreach ($quotation->getItems() as $item):
                    $itemTotal = $item->getPrice() * $item->getQuantity();
                    $totalAmount += $itemTotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item->getServiceName(), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($item->getDescription(), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= number_format($item->getPrice(), 2) ?> €</td>
                    <td><?= $item->getQuantity() ?></td>
                    <td><?= number_format($itemTotal, 2) ?> €</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">Total:</th>
                    <th><?= number_format($totalAmount, 2) ?> €</th>
                </tr>
            </tfoot>
        </table>

















            
                </div>
            </div>
        </div>
