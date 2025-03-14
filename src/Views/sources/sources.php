

<div class="homepage">
    <h2>Sources</h2>

    <?php if (!empty($sources)): ?>
        <ul>
            <?php foreach ($sources as $source): ?>
                <li>
                    <strong>Name:</strong> <?= htmlspecialchars($source->getName()) ?> <br>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Sources not found</p>
    <?php endif; ?>
</div>
