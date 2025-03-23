

<!-- Begin Page Content -->
<div class="container-fluid">

<!-- DataTales Example -->
<div class="card shadow mb-4">
<div class="card-header py-3 d-flex align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary">MATERIAL LIST</h6>
    
    <div>
        <a class="btn btn-primary" href="<?= base_url('user/material_predictions'); ?>">Kebutuhan Material</a>
    </div>
</div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Barcode</th>
                    <th>Material Name</th>
                    <th>Min Stock</th>
                    <th>Stock</th>
                    <th>Unit</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($materials as $material): ?>
                    <tr>
                        <td><?= $material['barcode']; ?></td>
                        <td><?= $material['material_name']; ?></td>
                        <td><?= $material['min_stock']; ?></td>
                        <td><?= $material['stock']; ?></td>
                        
                        <td><?= $material['unit']; ?></td>
                        <td><?= date('d-m-Y H:i:s', strtotime($material['date_added'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
                            </div>
                        </div>
                    </div>

    
</div>

<script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables.min.js'); ?>"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
<!-- /.container-fluid -->
