<?php


if (isset($_GET['model'])) {
    $model = $_GET['model'];
    $query = $db->prepare("
        SELECT m.material_name, mm.quantity, m.unit 
        FROM model_materials mm
        JOIN materials m ON mm.material_id = m.id
        WHERE mm.model = ?
    ");
    $query->execute([$model]);
    $materials = $query->fetchAll(PDO::FETCH_ASSOC);

    if (count($materials) > 0) {
        foreach ($materials as $row) {
            echo "<tr>
                    <td>{$row['material_name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['unit']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3' class='text-center'>Tidak ada data</td></tr>";
    }
}
?>



<!-- Begin Page Content -->
<div class="container-fluid">

    

<!-- DataTales Example -->
<div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">FAS LIST</h6>
                        </div>
                        <div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Lot</th>
                    <th>Model</th>
                    <th>Destination</th>
                    <th>Raw Material</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fas as $data): ?>
                    <tr>
                        <td><?= date('d-m-Y', strtotime($data['tanggal'])); ?></td>
                        <td><?= $data['lot']; ?></td>
                        <td><?= $data['model']; ?></td>
                        <td><?= $data['destination']; ?></td>
                        <td>
                            <button class="btn btn-primary show-material" data-model="<?= $data['model']; ?>" data-toggle="modal" data-target="#materialModal">Show Material</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
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

<script>
$(document).ready(function(){
    $(".show-material").click(function(){
        var model = $(this).data("model");

        $.ajax({
            url: "<?= base_url('fas/get_materials/'); ?>" + model,
            method: "GET",
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
</div>