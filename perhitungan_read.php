<?php
  require 'functions.php';
  session_start();

  if(!isset($_SESSION['login'])) {
    header("location:login.php");
  }

  $first_head = ["No.", "Kode Alternatif", "Nama Alternatif"];
  $kriteria_head = get_perhitungan_head();
  $head = array_merge($first_head, $kriteria_head);

  $data = get_perhitungan();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mooraha</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <aside class="left-sidebar">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="index.php" class="text-nowrap logo-img d-flex justify-content-center align-items-center gap-2">
            <img src="assets/images/logos/dark-logo.svg" width="40" alt="" />
            <span class="fw-bolder fs-6">Mooraha</span>
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">HOME</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">MASTER DATA</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="alternatif_read.php" aria-expanded="false">
                <span>
                  <i class="ti ti-map-pin"></i>
                </span>
                <span class="hide-menu">Alternatif Lokasi</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="kriteria_read.php" aria-expanded="false">
                <span>
                  <i class="ti ti-list-details"></i>
                </span>
                <span class="hide-menu">Kriteria Penilaian</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="sub_kriteria_read.php" aria-expanded="false">
                <span>
                  <i class="ti ti-sitemap"></i>
                </span>
                <span class="hide-menu">Sub Kriteria Penilaian</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">SPK</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="penilaian_read.php" aria-expanded="false">
                <span>
                  <i class="ti ti-numbers"></i>
                </span>
                <span class="hide-menu">Proses Penilaian</span>
              </a>
            </li>
            <li class="sidebar-item selected">
              <a class="sidebar-link active" href="perhitungan_read.php" aria-expanded="false">
                <span>
                  <i class="ti ti-math-symbols"></i>
                </span>
                <span class="hide-menu">Proses Perhitungan</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="hasil_read.php" aria-expanded="false">
                <span>
                  <i class="ti ti-presentation"></i>
                </span>
                <span class="hide-menu">Hasil Akhir</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </aside>
    <div class="body-wrapper">
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <p class="mx-3">Selamat Datang,</br><span class="fw-bold fs-4"><?php echo $_SESSION['login']; ?></span></p>
                    <a href="logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <div class="container-fluid">
        <div class="container-fluid">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <span class="fs-6">
                <i class="ti ti-database fs-6 me-2"></i>Data Perhitungan
              </span>
            </div>
          </div>
          <div class="card mt-3">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-semibold">
                  <i class="ti ti-table me-2"></i>
                  Tabel Nilai Alternatif
                </h5>
              </div>
              <div class="table-responsive mt-3">
                <?php $data_matrix = []; ?>
                <table class="table text-nowrap mb-0 align-middle" style="border: 1px solid #ebf1f6">
                  <thead class="text-dark fs-4">
                    <tr>
                      <?php foreach($head as $h) : ?>
                      <th class="border-bottom-1">
                        <h6 class="fw-semibold mb-0"><?php echo $h ?></h6>
                      </th>
                      <?php endforeach; ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($data as $index => $d) : ?>
                    <?php $data_martix_alternatif = []; ?>
                    <tr>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $index + 1 ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $d["alternatif"]["kode"] ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $d["alternatif"]["nama"] ?></h6>
                      </td>
                      <?php foreach($kriteria_head as $kh) : ?>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0">
                          <?php
                            $nilai = 0; $bobot = 0; $is_cost = true;
                            foreach($d["kriteria"] as $dk) {
                              if($dk["kriteria"]["kode"] == $kh) {
                                if(isset($dk["subkriteria"])) {
                                  $nilai = (float)$dk["subkriteria"]["nilai"];
                                } else if(isset($dk["nilai"])) {
                                  $nilai = (float)$dk["nilai"];
                                }
                                $bobot = (float)$dk["kriteria"]["bobot"];
                                $is_cost = $dk["kriteria"]["jenis"] === "Cost" ? true : false;
                                break;
                              }
                            }

                            $data_martix_alternatif[] = [
                              "nilai" => $nilai,
                              "bobot" => $bobot,
                              "is_cost" => $is_cost
                            ];
                            echo number_format($nilai, 3);
                          ?>
                        </h6>
                      </td>
                      <?php endforeach;
                        $data_matrix[] = $data_martix_alternatif; ?>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <br><br>

              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-semibold">
                  <i class="ti ti-table me-2"></i>
                  Tabel Nilai Normalisasi Matriks
                </h5>
              </div>
              <div class="table-responsive mt-3">
                <?php $normalized_matrix = get_normalized_matrix($data_matrix, get_squared_sum_column($data_matrix)); ?>
                <table class="table text-nowrap mb-0 align-middle" style="border: 1px solid #ebf1f6">
                  <thead class="text-dark fs-4">
                    <tr>
                      <?php foreach($head as $h) : ?>
                      <th class="border-bottom-1">
                        <h6 class="fw-semibold mb-0"><?php echo $h ?></h6>
                      </th>
                      <?php endforeach; ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($data as $index => $d) : ?>
                    <tr>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $index + 1 ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $d["alternatif"]["kode"] ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $d["alternatif"]["nama"] ?></h6>
                      </td>
                      <?php foreach($normalized_matrix[$index] as $normalized_value) : ?>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0">
                          <?php
                            echo number_format($normalized_value["nilai_normalized"], 3);
                          ?>
                        </h6>
                      </td>
                      <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <br><br>
              
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-semibold">
                  <i class="ti ti-table me-2"></i>
                  Tabel Nilai Normalisasi Matriks Terbobot
                </h5>
              </div>
              <div class="table-responsive mt-3">
                <table class="table text-nowrap mb-0 align-middle" style="border: 1px solid #ebf1f6">
                  <thead class="text-dark fs-4">
                    <tr>
                      <?php foreach($head as $h) : ?>
                      <th class="border-bottom-1">
                        <h6 class="fw-semibold mb-0"><?php echo $h ?></h6>
                      </th>
                      <?php endforeach; ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($data as $index => $d) : ?>
                    <tr>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $index + 1 ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $d["alternatif"]["kode"] ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $d["alternatif"]["nama"] ?></h6>
                      </td>
                      <?php foreach($normalized_matrix[$index] as $normalized_value) : ?>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0">
                          <?php
                            echo number_format($normalized_value["nilai_normalized"] * $normalized_value["bobot"], 3);
                          ?>
                        </h6>
                      </td>
                      <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/dashboard.js"></script>
</body>

</html>