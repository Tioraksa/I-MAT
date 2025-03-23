<div class="container">
    <h3>Export & Import Excel</h3>

    <!-- Export -->
    <a href="<?= base_url('ExcelController/export'); ?>" class="btn btn-success">Download Excel</a>

    <!-- Import -->
    <form action="<?= base_url('ExcelController/import'); ?>" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit" class="btn btn-primary">Upload Excel</button>
    </form>

    <!-- Delete by Excel -->
    <form action="<?= base_url('ExcelController/delete_by_excel'); ?>" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit" class="btn btn-danger">Delete via Excel</button>
    </form>
</div>
