<?php
  require 'functions.php';
  session_start();

  if(!isset($_SESSION['login'])) {
    header("location:login.php");
  }

  if($_SESSION['role'] == "User") {
    header("location:index.php");
  }

  $head = ["No.", "Sub Kriteria", "Nilai", "Aksi"];
  if(isset($_GET["id"])) {
    $id = $_GET['id'];
    $result = get("SELECT * FROM tbl_kriteria WHERE id_kriteria = '$id'");
    if($result) {
      $kriteria = $result[0];
      $sub_kriteria = get("SELECT * FROM tbl_subkriteria WHERE id_kriteria = '$id' ORDER BY nilai DESC");
    } else {
      header('location:sub_kriteria_read.php');
    }
  } else {
    header('location:sub_kriteria_read.php');
  }

  if(isset($_POST["update"])) {
    $nama = $_POST['nama'];
    $nilai = $_POST['nilai'];

    $data = [
      "nama" => $nama,
      "nilai" => $nilai
    ];
    update_sub_kriteria($_POST['id_sub'], $data);
  }

  if(isset($_GET['delete'])) {
    delete_sub_kriteria($_GET['delete']);
  }
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mooha</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>

<body>
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Data Sub Kriteria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post">
            <input type="number" hidden id="id_sub" name="id_sub">
            <div class="mb-2">
              <label for="nama">Sub Kriteria</label>
              <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-6">
              <label for="nilai">Nilai</label>
              <input type="number" class="form-control" id="nilai" name="nilai" step="0.01" min="0" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-primary" name="update">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <aside class="left-sidebar">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="index.php" class="text-nowrap logo-img d-flex justify-content-center align-items-center gap-2">
            <img src="assets/images/logos/dark-logo.svg" width="40" alt="" />
            <span class="fw-bolder fs-6">Mooha</span>
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
            <?php
                if($_SESSION['role'] != "User") :
            ?>
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
            <li class="sidebar-item selected">
              <a class="sidebar-link active" href="sub_kriteria_read.php" aria-expanded="false">
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
            <li class="sidebar-item">
              <a class="sidebar-link" href="perhitungan_read.php" aria-expanded="false">
                <span>
                  <i class="ti ti-math-symbols"></i>
                </span>
                <span class="hide-menu">Proses Perhitungan</span>
              </a>
            </li>
            <?php endif; ?>
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
                <i class="ti ti-database fs-6 me-2"></i>Data Sub Kriteria
              </span>
            </div>
            <div>
              <a href="sub_kriteria_read.php" class="btn btn-outline-danger m-1">
                <i class="ti ti-arrow-left me-2"></i>Kembali
              </a>
            </div>
          </div>
          <div class="card mt-3">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-semibold">
                  <i class="ti ti-table me-2"></i>
                  Daftar Data Sub Kriteria - <?php echo $kriteria["nama"] ?>
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
                    <?php foreach($sub_kriteria as $index => $d) : ?>
                    <tr>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $index + 1 ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $d["nama"] ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $d["nilai"] ?></h6>
                      </td>
                      <td class="border-bottom-0 d-flex gap-2">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal"
                          data-bs-nama="<?php echo $d["nama"] ?>"
                          data-bs-nilai="<?php echo $d["nilai"] ?>"
                          data-bs-id="<?php echo $d["id_sub"] ?>">Edit</button>
                        <a href="sub_kriteria_update.php?delete=<?php echo $d["id_sub"] ?>" class="btn btn-danger">Hapus</a>
                      </td>
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
  <script>
    var exampleModal = document.getElementById('exampleModal')
    exampleModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget
      var nama = button.getAttribute('data-bs-nama')
      var nilai = button.getAttribute('data-bs-nilai')
      var id = button.getAttribute('data-bs-id')

      var modalNama = exampleModal.querySelector('#nama')
      var modalNilai = exampleModal.querySelector('#nilai')
      var modalId = exampleModal.querySelector('#id_sub')

      modalNama.value = nama
      modalNilai.value = nilai
      modalId.value = id
    })
  </script>
</body>

</html>