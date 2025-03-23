<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Monitoring Stok Material</h1>

    <!-- Data Pemasukan -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pemasukan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tablePemasukan" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Material Name</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?= htmlspecialchars($transaction['barcode']); ?></td>
                                <td><?= htmlspecialchars($transaction['material_name']); ?></td>
                                <td><?= ucfirst(htmlspecialchars($transaction['type'])); ?></td>
                                <td><?= htmlspecialchars($transaction['quantity']); ?></td>
                                <td><?= htmlspecialchars($transaction['unit']); ?></td>
                                <td><?= date('d-m-Y H:i:s', strtotime($transaction['date_added'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Data Pengeluaran -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pengeluaran</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="tablePengeluaran" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>NPK</th>
                        <th>Model</th>
                        <th>Tanggal</th>
                        <th>Material</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($supply)): ?>
                        <?php foreach ($supply as $row): ?>
                        <tr>
                            <td><?= $row['nama']; ?></td>
                            <td><?= $row['model']; ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])); ?></td>
                            <td><?= $row['material_name']; ?></td>
                            <td><?= $row['quantity']; ?> <?= $row['unit']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data pengeluaran</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<!-- JS Libraries -->
<script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables.min.js'); ?>"></script>
<script>
    $(document).ready(function() {
        $('#tablePemasukan').DataTable();
        $('#tablePengeluaran').DataTable();
    });
</script>
