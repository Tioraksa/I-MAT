                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

                    <div class="row">
                        <div class="col-lg-8">
                            <?= form_open_multipart('user/edit'); ?>

                            <!-- Input Nama -->
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" name="name" class="form-control" id="name" 
                                    value="<?= $user['name']; ?>" required>
                            </div>

                            <!-- Input Email (tidak bisa diubah) -->
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" class="form-control" id="email" 
                                    value="<?= $user['email']; ?>" readonly>
                            </div>

                            <!-- Input Foto Profil -->
                            <div class="form-group">
                                <label for="image">Profile Picture</label>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail">
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="file" name="image" id="image" class="form-control">
                                        <small>File yang didukung: JPG, PNG, GIF. Maksimal ukuran: 2MB.</small>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="<?= base_url('user'); ?>" class="btn btn-secondary">Cancel</a>

                            <?= form_close(); ?>
                        </div>
                    </div>

</div>
<!-- /.container-fluid -->


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            