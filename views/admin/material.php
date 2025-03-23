

<!-- Begin Page Content -->
<div class="container-fluid">

<!-- DataTales Example -->
<div class="card shadow mb-4">
<div class="card-header py-3 d-flex align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary">MATERIAL LIST</h6>
    
    <div>
        <button class="btn btn-success mr-2" data-toggle="modal" data-target="#modalAddStock">Tambah Stok</button>
        <a class="btn btn-primary" href="<?= base_url('admin/material_predictions'); ?>">Kebutuhan Material</a>
    </div>
</div>

                        <!-- Button Monitoring Pengeluaran Material -->
                        <a href="<?= base_url('admin/monitoring'); ?>" class="btn btn-info mb-3">
                            <i class="fas fa-eye"></i> Monitoring Material
                        </a>
                        

                        <div class="card-body">
                            <!-- Search Bar -->
                            <div class="d-flex justify-content-between mb-3">
                            <form method="GET" action="<?= base_url('admin/material'); ?>" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
    <div class="input-group">
        <input type="text" name="search" class="form-control bg-light border-0 small"
               placeholder="Search for..." value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search fa-sm"></i>
            </button>
        </div>
    </div>
</form>
        </div>
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
                    </div>
    <!-- Modal Tambah Stok -->
<div class="modal fade" id="modalAddStock" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Stok Material</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= base_url('admin/add_stock'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="barcode">Barcode Material</label>
                        <input type="text" class="form-control" name="barcode" id="barcode" required>
                    </div>
                    <div class="form-group">
                        <label for="material_name">Nama Material</label>
                        <input type="text" class="form-control" id="material_name" readonly>
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

<script>
    $(document).ready(function () {
        $('#barcode').on('change', function () {
            let barcode = $(this).val();
            $.ajax({
                url: "<?= base_url('admin/get_material_by_barcode'); ?>",
                method: "POST",
                data: { barcode: barcode },
                dataType: "json",
                success: function (data) {
                    if (data.status == "success") {
                        $('#material_name').val(data.material_name);
                    } else {
                        $('#material_name').val("Material tidak ditemukan");
                    }
                }
            });
        });

        $('#barcode_monitor').on('change', function () {
            let barcode = $(this).val();
            $.ajax({
                url: "<?= base_url('admin/get_material_by_barcode'); ?>",
                method: "POST",
                data: { barcode: barcode },
                dataType: "json",
                success: function (data) {
                    if (data.status == "success") {
                        $('#material_name_monitor').val(data.material_name);
                    } else {
                        $('#material_name_monitor').val("Material tidak ditemukan");
                    }
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        let dataTable = $('#dataTable').DataTable({
            "paging": true,
            "searching": true, // Mengaktifkan fitur pencarian
            "ordering": true,
            "info": true
        });

        // Pencarian real-time saat mengetik
        $('#searchBox').on('keyup', function() {
            dataTable.search($(this).val()).draw();
        });

        // Pencarian saat tombol search diklik
        $('#searchButton').on('click', function() {
            let searchValue = $('#searchBox').val();
            dataTable.search(searchValue).draw();
        });
    });
</script>


<!-- /.container-fluid -->
