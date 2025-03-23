<!-- Begin Page Content -->
<div class="container-fluid">

<div class="container text-center mt-5">
    <div class="row justify-content-center g-4">
        <div class="col-md-3">
            <a href="<?= base_url('admin/fas'); ?>" class="custom-btn">
                <div class="custom-box">
                    <span>fas</span>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= base_url('admin/material'); ?>" class="custom-btn">
                <div class="custom-box">
                    <span>Material List</span>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= base_url('admin/supply'); ?>" class="custom-btn">
                <div class="custom-box">
                    <span>Supply Material</span>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- Tabel Kebutuhan Material Hari Ini -->
<div class="card shadow mb-4">
<div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Kebutuhan Material - <?= date('d-m-Y'); ?></h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th>Model</th>
                    <th>Lot</th>
                    <th>Material</th>
                    <th>Jumlah</th>  
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($material_predictions)) : ?>
                    <?php foreach ($material_predictions as $mp) : ?>
                        <tr>
                            <td><?= $mp['model']; ?></td>
                            <td><?= $mp['lot']; ?></td>
                            <td><?= $mp['material_name']; ?></td>
                            <td><?= $mp['quantity']; ?> Pcs</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center text-danger">Tidak ada data kebutuhan material untuk hari ini</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            </table>
        </div>
</div>

<!-- Tabel Supply Material Hari Ini -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Supply Material - <?= date('d-m-Y'); ?></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>NPK</th>
                        <th>Model</th>
                        <th>Lot</th>
                        <th>Tanggal</th>
                        <th>Material</th>
                        <th>Quantity</th>
                        <th>Standar</th>
                        <th>Selisih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($supply)) : ?>
                        <?php foreach ($supply as $row): ?>
                            <?php 
                                $selisih = $row['quantity'] - $row['standar']; 
                                $selisih_color = ($selisih < 0) ? 'text-danger' : 'text-success';
                            ?>
                            <tr>
                                <td><?= isset($row['nama']) ? htmlspecialchars($row['nama']) : 'Unknown'; ?></td>
                                <td><?= $row['model']; ?></td>
                                <td><?= isset($row['lot']) ? htmlspecialchars($row['lot']) : '-'; ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])); ?></td>
                                <td><?= isset($row['material_name']) ? $row['material_name'] : '-'; ?></td>
                                <td><?= $row['quantity']; ?> Pcs</td>
                                <td><?= $row['standar']; ?> Pcs </td>
                                <td>
                                    <span class="<?= $selisih_color; ?> font-weight-bold">
                                        <?= $selisih; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center text-danger">Tidak ada data supply material untuk hari ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


    


<!-- Card Statistik -->
<div class="row">
        
    </div>
    <!-- Chart -->
    <div class="row">
        <div class="col-md-6">
            <canvas id="pieChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="barChart"></canvas>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        $.getJSON("<?= base_url('admin/get_chart_data'); ?>", function(data) {
            
            // Data untuk Bar Chart (Stock vs Min Stock)
            let barChart = new Chart($("#barChart"), {
                type: "bar",
                data: {
                    labels: data.materials.map(m => m.material_name),
                    datasets: [{
                        label: "Stock",
                        backgroundColor: "#36A2EB",
                        data: data.materials.map(m => m.stock)
                    }, {
                        label: "Min Stock",
                        backgroundColor: "#FF6384",
                        data: data.materials.map(m => m.min_stock)
                    }]
                }
            });

            // Data untuk Line Chart (Penggunaan Material)
            let lineChart = new Chart($("#lineChart"), {
                type: "line",
                data: {
                    labels: data.transactions.map(t => t.material_name),
                    datasets: [{
                        label: "Total Penggunaan",
                        borderColor: "#FF6384",
                        fill: false,
                        data: data.transactions.map(t => t.total_usage)
                    }]
                }
            });

        });
    });
</script>
