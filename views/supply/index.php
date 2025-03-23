<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h2 class="m-0 font-weight-bold text-primary">Supply Material</h2>
            <button class="btn btn-success float-right" data-toggle="modal" data-target="#supplyModal">Tambah Supply</button>
            <button class="btn btn-success float-right" data-toggle="modal" data-target="#scanSupplyModal">Scan Tambah Supply</button>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="dataTable">
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
                    <?php foreach ($supply as $row): ?>
                        <tr>
                            <td><?= $row['nama']; ?></td>
                            <td><?= $row['model']; ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])); ?></td>
                            <td><?= $row['material_name']; ?></td>
                            <td><?= $row['quantity']; ?> <?= $row['unit']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Scan Tambah Supply -->
    <div class="modal fade" id="scanSupplyModal" tabindex="-1" role="dialog" aria-labelledby="scanSupplyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan Tambah Supply</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="scanSupplyForm">
    <div class="modal-body">
        <input type="hidden" id="material_id" name="material_id">
        <div class="form-group">
    <label for="model">Model</label>
    <select id="model" name="model" class="form-control" required>
        <option value="">Pilih Model</option>
        <?php foreach ($models as $model): ?>
            <option value="<?= htmlspecialchars($model['model']); ?>">
                <?= htmlspecialchars($model['model']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

        <div class="form-group">
            <label for="scanned_material">Scan Barcode</label>
            <input type="text" id="scanned_material" name="barcode" class="form-control" autofocus required>
        </div>
        <div class="form-group">
            <label for="material_name">Nama Material</label>
            <input type="text" id="material_name" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label for="quantity">Jumlah</label>
            <input type="number" id="quantity" name="quantity" class="form-control" min="1" required>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>


            </div>
        </div>
    </div>

    
</div>


<script>
$(document).ready(function() {
    $("#scanSupplyForm").submit(function(event) {
        event.preventDefault();

        let formData = $(this).serialize();
        console.log("Data yang dikirim:", formData); // Debugging di Console

        $.ajax({
            url: "<?= base_url('supply/add_supply'); ?>",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                console.log("Response dari server:", response);
                if (response.status === "success") {
                    alert(response.message);
                    location.reload();
                } else {
                    alert("Gagal: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log("AJAX Error:", xhr.responseText);
                alert("Terjadi kesalahan saat menyimpan data!");
            }
        });
    });

    $("#scanned_material").keypress(function(event) {
        if (event.which == 13) { // Enter key
            event.preventDefault();

            var barcode = $(this).val();

            $.ajax({
                url: "<?= base_url('supply/get_material_by_barcode'); ?>",
                type: "POST",
                data: { barcode: barcode },
                dataType: "json",
                success: function(response) {
                    if (response.status == "success") {
                        $("#material_id").val(response.material_id);
                        $("#material_name").val(response.material_name);
                    } else {
                        alert("Barcode tidak ditemukan!");
                        $("#material_id").val("");
                        $("#material_name").val("");
                    }
                },
                error: function() {
                    alert("Terjadi kesalahan saat mencari data!");
                }
            });
        }
    });
});
</script>

