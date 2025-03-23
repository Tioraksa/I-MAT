<div class="container-fluid">
    <h3 class="mb-4"><?= $title; ?></h3>

    <!-- Dropdown Pilih Model -->
    <div class="mb-3 text-right">
        <select id="modelSelect" class="form-control w-25 d-inline">
            <option value="">Pilih Model</option>
            <option value="BUA">BUA</option>
            <option value="BWE">BWE</option>
            <option value="BDL">BDL</option>
            <option value="BRW">BRW</option>
        </select>
    </div>

    <!-- Tabel Material -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Material Name</th>
                <th>Quantity</th>
                <th>Unit</th>
            </tr>
        </thead>
        <tbody id="materialTable">
            <tr>
                <td colspan="3" class="text-center">Silakan pilih model</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $("#modelSelect").change(function() {
        var model = $(this).val();

        if (model !== "") {
            $.ajax({
                url: "<?= base_url('user/get_materials_by_model'); ?>",
                method: "GET",
                data: { model: model },
                dataType: "json",
                success: function(data) {
                    $("#materialTable").empty();

                    if (data.length > 0) {
                        $.each(data, function(index, item) {
                            $("#materialTable").append(
                                "<tr><td>" + item.material_name + "</td><td>" + item.quantity + "</td><td>" + item.unit + "</td></tr>"
                            );
                        });
                    } else {
                        $("#materialTable").append('<tr><td colspan="3" class="text-center">Tidak ada data</td></tr>');
                    }
                }
            });
        } else {
            $("#materialTable").html('<tr><td colspan="3" class="text-center">Silakan pilih model</td></tr>');
        }
    });
});
</script>
