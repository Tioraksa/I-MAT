<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5 col-lg-7 mx-auto">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                        </div>
                        <form class="user" method="post" action="<?= base_url('auth/registration') ?>">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="name" name="name"
                                    placeholder="Full Name" value="<?= set_value('name'); ?>">
                                <?= form_error('name', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="npk" name="npk"
                                    placeholder="Npk" value="<?= set_value('npk'); ?>">
                                <?= form_error('npk', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user" id="password1" name="password1"
                                        placeholder="Password">
                                    <?= form_error('password1', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" id="password2" name="password2"
                                        placeholder="Repeat Password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Register Account
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="forgot-password.html">Forgot Password?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="<?= base_url('auth'); ?>">Already have an account? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Dropdown -->
<script>
        const apiBase = 'https://www.emsifa.com/api-wilayah-indonesia/api';

        async function fetchData(endpoint) {
            const response = await fetch(`${apiBase}/${endpoint}.json`);
            return response.json();
        }

        async function loadProvinces() {
            const provinces = await fetchData('provinces');
            const provinceSelect = document.getElementById("province");

            provinces.forEach(province => {
                const option = new Option(province.name, province.id);
                provinceSelect.add(option);
            });
        }

        async function loadRegencies(provinceId) {
            const regencies = await fetchData(`regencies/${provinceId}`);
            const regencySelect = document.getElementById("regency");

            regencySelect.innerHTML = "<option value=''>Pilih Kabupaten/Kota</option>";

            regencies.forEach(regency => {
                const option = new Option(regency.name, regency.id);
                regencySelect.add(option);
            });

            regencySelect.disabled = false;
        }

        async function loadDistricts(regencyId) {
            const districts = await fetchData(`districts/${regencyId}`);
            const districtSelect = document.getElementById("district");

            districtSelect.innerHTML = "<option value=''>Pilih Kecamatan</option>";

            districts.forEach(district => {
                const option = new Option(district.name, district.id);
                districtSelect.add(option);
            });

            districtSelect.disabled = false;
        }

        document.getElementById("province").addEventListener("change", function() {
            const provinceId = this.value;
            if (provinceId) {
                loadRegencies(provinceId);
                document.getElementById("district").innerHTML = "<option value=''>Pilih Kecamatan</option>";
                document.getElementById("district").disabled = true;
            }
        });

        document.getElementById("regency").addEventListener("change", function() {
            const regencyId = this.value;
            if (regencyId) {
                loadDistricts(regencyId);
            }
        });

        loadProvinces();
    </script>
