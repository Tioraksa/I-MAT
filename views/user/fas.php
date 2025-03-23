<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- DataTales Example -->
    <!-- Tabel Data FAS -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">FAS LIST</h6>
        </div>
        <div class="card-body">
            <!-- Button Tambah FAS -->
            <button">
                <a href="<?= base_url('user/material_predictions'); ?>" class="btn btn-primary mb-3">Kebutuhan Material</a>
            </button>



            

            <!-- Filter Tanggal -->
            <div class="d-flex justify-content-between mb-3">
                <a href="<?= base_url('user/fas?date=' . $prev_date); ?>" class="btn btn-secondary">← Sebelumnya</a>

                <form method="GET" action="<?= base_url('user/fas'); ?>">
                    <input type="date" name="date" value="<?= $selected_date; ?>" class="form-control d-inline-block" style="width: 200px;">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </form>

                <a href="<?= base_url('user/fas?date=' . $next_date); ?>" class="btn btn-secondary">Selanjutnya →</a>
            </div>

        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Warna Lot</th>
                        <th>Tanggal</th>
                        <th>Invoice</th>
                        <th>Lot</th>
                        <th>Model</th>
                        <th>CKD Set Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($fas)) : ?>
                        <?php foreach ($fas as $data): ?>
                            <tr>
                                <td><?= $data['warna_lot']; ?></td>
                                <td><?= date('d-m-Y', strtotime($data['tanggal'])); ?></td>
                                <td><?= $data['invoice']; ?></td>
                                <td><?= $data['lot']; ?></td>
                                <td><?= $data['model']; ?></td>
                                <td><?= $data['ckd_set_name']; ?></td>
                                <td><?= $data['status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-danger font-weight-bold">
                                Tidak ada data pada tanggal ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



    <!-- Modal untuk menampilkan Material -->
    <div class="modal fade" id="materialModal" tabindex="-1" role="dialog" aria-labelledby="materialModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="materialModalLabel">Material Needed</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Material Name</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                            </tr>
                        </thead>
                        <tbody id="materialList">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
$(document).ready(function(){
    $(".show-material").click(function(){
        var model = $(this).data("model");

        $.ajax({
            url: "<?= base_url('user/get_materials'); ?>",
            method: "GET",
            data: { model: model },
            dataType: "json",
            success: function(data) {
                $("#materialList").empty();
                if (data.length > 0) {
                    $.each(data, function(index, item) {
                        $("#materialList").append(
                            "<tr><td>" + item.material_name + "</td><td>" + item.quantity + "</td><td>" + item.unit + "</td></tr>"
                        );
                    });
                } else {
                    $("#materialList").append('<tr><td colspan="3" class="text-center">Tidak ada data</td></tr>');
                }
                $("#materialModal").modal("show");
            }
        });
    });
});
</script>

<script>
$(document).ready(function(){
    // Data model, invoice, dan CKD Set Name
    var modelData = {
        "BUA1A9-010A": { invoice: "BFLC31", ckd_set_name: "GPD155-A PHL 25" },
        "BUA1C2-010A": { invoice: "BFLC41", ckd_set_name: "GPD155-A PHL 25" },
        "BUA1C3-010A": { invoice: "BFLC51", ckd_set_name: "GPD155-A PHL 25" },
        "BWE1E3-010A": { invoice: "BFLBB6", ckd_set_name: "LTK125-A BRA 26" },
        "BRW6C4-010A": { invoice: "BFLCH1", ckd_set_name: "DR155 (NO-SCCU) PHL" }
    };

    // Event saat model dipilih
    $("#modelSelect").change(function(){
        var selectedModel = $(this).val();
        
        if (modelData[selectedModel]) {
            $("#invoiceInput").val(modelData[selectedModel].invoice);
            $("#ckdSetInput").val(modelData[selectedModel].ckd_set_name);
        } else {
            $("#invoiceInput").val("");
            $("#ckdSetInput").val("");
        }
    });
});
</script>

<script>
$(document).ready(function(){
    $("#modelSelect").change(function(){
        var model = $(this).val();
        
        $.ajax({
            url: "<?= base_url('user/get_material_data'); ?>",
            method: "GET",
            data: { model: model },
            dataType: "json",
            success: function(data) {
                $("#materialList").empty();
                if (data.length > 0) {
                    $.each(data, function(index, item) {
                        $("#materialList").append(
                            "<tr><td>" + item.material_name + "</td><td>" + item.quantity + "</td><td>" + item.unit + "</td></tr>"
                        );
                    });
                } else {
                    $("#materialList").append('<tr><td colspan="3" class="text-center">Tidak ada data</td></tr>');
                }
            }
        });
    });
});
</script>

