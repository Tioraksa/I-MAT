<div class="container-fluid">
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h2 class="m-0 font-weight-bold text-primary">Supply Material </h2>
            <button class="btn btn-success float-right" data-toggle="modal" data-target="#scanSupplyModal">Scan Tambah Supply</button>
            <a href="<?= base_url('export/export_data'); ?>" class="btn btn-success">Export ke Excel</a>

        </div>
        <div class="card-body">
        <table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Model</th>
            <th>Lot</th>
            <th>Tanggal</th>
            <th>Material</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($supply)): ?>
        <?php foreach ($supply as $row): ?>
            <tr>
                <td><?= isset($row['nama']) ? htmlspecialchars($row['nama']) : 'Unknown'; ?></td>
                <td><?= htmlspecialchars($row['model']); ?></td>
                <td><?= !empty($row['lot']) ? htmlspecialchars($row['lot']) : '-'; ?></td>
                <td><?= isset($row['tanggal']) ? date('d-m-Y H:i:s', strtotime($row['tanggal'])) : '-'; ?></td>
                <td><?= htmlspecialchars($row['material_name']); ?></td>
                <td><?= htmlspecialchars($row['quantity']) . ' ' . htmlspecialchars($row['unit']); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" class="text-center text-muted">Tidak ada data supply material.</td>
        </tr>
    <?php endif; ?>
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

<label for="lot">Lot:</label>
    <select name="lot" id="lot" required>
        <option value="">Pilih Lot</option>
    </select>

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

<!-- lot -->
<script>
    document.getElementById('model').addEventListener('change', function() {
        let model = this.value;
        fetch("<?= base_url('admin/get_lot_by_model_today/'); ?>" + model)
            .then(response => response.json())
            .then(data => {
                console.log(data); // Debugging untuk memastikan data diterima
                let lotSelect = document.getElementById('lot');
                lotSelect.innerHTML = '<option value="">Pilih Lot</option>';
                
                if (data.status === "error") {
                    lotSelect.innerHTML += `<option value="">${data.message}</option>`;
                } else {
                    data.forEach(item => {
                        lotSelect.innerHTML += `<option value="${item.lot}">${item.lot}</option>`;
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>


<script>
function updateTime() {
    var now = new Date();
    var options = { 
        timeZone: 'Asia/Jakarta', 
        hour12: false,
        year: 'numeric', month: '2-digit', day: '2-digit', 
        hour: '2-digit', minute: '2-digit', second: '2-digit' 
    };
    var timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
    
    document.querySelectorAll('.realtime-clock').forEach(el => {
        el.textContent = timeString.replace(/\//g, '-');
    });
}

// Update setiap detik
setInterval(updateTime, 1000);
updateTime(); // Panggil pertama kali saat halaman dimuat
</script>
