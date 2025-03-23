<div class="container-fluid">
        <div class="card-header py-3">
            <H2 class="m-0 font-weight-bold text-primary">Kebutuhan Material </H2>
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
    $(document).ready(function () {
        $("#modelSelect").change(function () {
            var model = $(this).val();

            if (model !== "") {
                console.log("Model dikirim:", model); // Debugging

                $.ajax({
                    url: "<?= base_url('admin/get_materials_by_model'); ?>",
                    method: "GET",
                    data: { model: model },
                    dataType: "json",
                    success: function (data) {
                        console.log("Respon dari server:", data); // Debugging

                        $("#materialTable").empty();

                        if (Array.isArray(data) && data.length > 0) {
                            $.each(data, function (index, item) {
                                $("#materialTable").append(
                                    "<tr><td>" + item.material_name + "</td><td>" + item.quantity + "</td><td>" + item.unit + "</td></tr>"
                                );
                            });
                        } else {
                            $("#materialTable").append('<tr><td colspan="3" class="text-center">Tidak ada data</td></tr>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error: ", xhr.responseText);
                        $("#materialTable").html('<tr><td colspan="3" class="text-center">Gagal mengambil data</td></tr>');
                    }
                });
            } else {
                $("#materialTable").html('<tr><td colspan="3" class="text-center">Silakan pilih model</td></tr>');
            }
        });
    });
</script>
