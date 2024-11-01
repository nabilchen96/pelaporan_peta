<div class="bg-primary">
    <div class="container">
        <div class="col-12 list-produk">
            <div class="row d-flex">
                <?php
                // Mulai session
                session_start();

                // Misalnya, periksa apakah sesi 'logged_in' ada dan bernilai true
                $isLoggedIn = isset($_SESSION['user_id']) === true;
                ?>

                <div class="col-lg-2 pt-3">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown Menu
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton2">
                            <li><a class="dropdown-item" href="/pelaporan_peta">Beranda</a></li>

                            <!-- Tampilkan hanya jika user sudah login -->
                            <?php if ($isLoggedIn): ?>
                                <li><a class="dropdown-item" href="/pelaporan_peta/user">User</a></li>
                                <li><a class="dropdown-item" href="/pelaporan_peta/berita">Berita</a></li>
                                <li><a class="dropdown-item" href="/pelaporan_peta/peta">Peta</a></li>
                            <?php endif; ?>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <!-- Ganti Logout menjadi Login jika user belum login -->
                            <?php if ($isLoggedIn): ?>
                                <li><a class="dropdown-item" href="/pelaporan_peta/controller/logout.php">Logout</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="/pelaporan_peta/controller/login.php">Login</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-10 pt-3">
                    <ul class="nav nav-lt-tab">
                        <?php

                        include $_SERVER['DOCUMENT_ROOT'] . '/pelaporan_peta/config.php';

                        // Menyertakan file config.php
                        
                        // Mengambil data pengguna dari database
                        $sql = "SELECT * FROM berita ORDER BY RAND()";
                        $result = $conn->query($sql);

                        $data = array(); // Array untuk menyimpan data
                        
                        foreach ($result as $row) {

                            ?>
                            <li class="nav-item pb-2 pe-3" style="width: 300px;">
                                <div class="card" style="
                                    border-radius: 8px;
                                    border: none;
                                    background-image: linear-gradient(360deg, black, transparent),
                                    url('../pelaporan_peta/asset/gambar_berita/<?= $row['gambar']; ?>');
                                    background-position: center;
                                    background-size: cover;
                                    ">
                                    <div class="card-body testimony-text" style="white-space: normal;">
                                        <p class="mb-0 mt-4"
                                            style="color: white; position: absolute; bottom: 10px; right: 10px; left: 10px; font-size: 14px;">
                                            <a style="color: white; text-decoration: none; "
                                                href="../pelaporan_peta/detail_berita?id=<?= $row['id']; ?>">
                                                <?= substr($row['judul'], 0, 80); ?>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>