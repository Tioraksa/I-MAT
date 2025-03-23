

<!-- Begin Page Content -->
<div class="container-fluid">

<!-- DataTales Example -->
<div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">MATERIAL LIST</h6>
                        </div>
                        <!-- Button Tambah Stok Material -->
                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#inputStockModal">
                            <i class="fas fa-plus"></i> Input Stok Material
                        </button>
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

    <!-- Modal Input Stok Material -->
<div class="modal fade" id="inputStockModal" tabindex="-1" aria-labelledby="inputStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inputStockModalLabel">Tambah Stok Material</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('material/add_stock'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="barcode">Barcode Material</label>
                        <input type="text" class="form-control" name="barcode" id="barcode" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Jumlah Stok</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Stok</button>
                </div>
            </form>
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
