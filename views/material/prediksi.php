<div class="container">
    <h2>Prediksi Kebutuhan Material</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Material</th>
                <th>Rata-rata Penggunaan (3 Bulan)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($predictions as $item) : ?>
                <tr>
                    <td><?= $item['material_name']; ?></td>
                    <td><?= round($item['avg_usage'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
