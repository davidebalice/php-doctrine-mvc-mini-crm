

<div class="homepage">
    <h2>Leads</h2>

    <?php if (!empty($leads)): ?>
        <ul>
            <?php foreach ($leads as $lead): ?>
                <li>
                    <strong>Name:</strong> <?= htmlspecialchars($lead->getFirstName()) ?> <br>
                    <strong>Email:</strong> <?= htmlspecialchars($lead->getEmail()) ?> <br>
                    <strong>Status:</strong> <?= htmlspecialchars($lead->getStatus()->getName()) ?> <br>
                    <strong>Source:</strong> <?= htmlspecialchars($lead->getSource()->getName()) ?> <br>
                    <strong>User:</strong> <?= htmlspecialchars($lead->getAssignedUser()->getName()) ?> <br>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Leads not found</p>
    <?php endif; ?>
</div>
