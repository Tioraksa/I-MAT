<div class="container">
    <h2>Monitoring Stok Material</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Material</th>
                <th>Stok Saat Ini</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stock as $item) : ?>
                <tr>
                    <td><?= $item['name']; ?></td>
                    <td><?= $item['stock']; ?></td>
                    <td>
                        <span class="badge badge-<?= ($item['status'] == 'Low Stock') ? 'danger' : 'success'; ?>">
                            <?= $item['status']; ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
