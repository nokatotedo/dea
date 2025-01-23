<?php
  require 'functions.php';
  session_start();

  if(!isset($_SESSION['login'])) {
    header("location:login.php");
  }
  
  $penilaian = null;
  if(isset($_GET['id'])) {
    $id_alternatif = $_GET["id"];
    $penilaian = get("SELECT * FROM tbl_penilaian WHERE id_alternatif = $id_alternatif");
    if(empty($penilaian)) {
      $penilaian = null;
    } else {
      $data_alternatif_penilaian = get("SELECT * FROM tbl_alternatif WHERE id_alternatif = " . $id_alternatif);
    }
  }

  if(isset($_POST['search'])) {
    $search = $_POST['keyword'];

    search_penilaian($search);
  }

  $head = ["No.", "Kode Kriteria", "Nama Kriteria", "Aksi"];
  $data = [];
  $keyword = '';
  if(isset($_GET['search']) && !empty($_GET['search'])) {
    $keyword = htmlspecialchars($_GET['search']);
    $data = get("SELECT * FROM tbl_alternatif WHERE id_alternatif IN (SELECT id_alternatif FROM tbl_penilaian) AND (nama LIKE '%$keyword%' OR kd_alternatif LIKE '%$keyword%')");
  } else {
    $data = get("SELECT * FROM tbl_alternatif WHERE id_alternatif IN (SELECT id_alternatif FROM tbl_penilaian)");
  }

  $alternatif = get("SELECT id_alternatif, nama, kd_alternatif FROM tbl_alternatif");
  $kriteria = get("SELECT id_kriteria, nama, kd_kriteria FROM tbl_kriteria");
  $sub_kriteria = get("SELECT id_sub, id_kriteria, nama, nilai FROM tbl_subkriteria");

  if(isset($_POST['create'])) {
    $id_alternatif = $_POST['id_alternatif'];
    $data = [];

    foreach($kriteria as $k) {
      $id_kriteria = $_POST["id_kriteria_" . $k["id_kriteria"]];
      $this_sub_kriteria = array_filter($sub_kriteria, function ($sub) use ($k) {
        return $sub['id_kriteria'] == $k['id_kriteria'];
      });

      if(!empty($this_sub_kriteria)) {
        $id_sub = $_POST["id_sub_" . $k["id_kriteria"]];
        $nilai = null;
      } else {
        $id_sub = null;
        $nilai = $_POST["nilai_sub_" . $k["id_kriteria"]];
      }

      $data[] = [
        "id_alternatif" => $id_alternatif,
        "id_kriteria" => $id_kriteria,
        "id_sub" => $id_sub,
        "nilai" => $nilai
      ];
    }

    create_penilaian($data);
  }

  if(isset($_POST['update'])) {
    $id_alternatif = $_POST['id_alternatif_update'];
    $data = [];

    foreach($kriteria as $k) {
      $id_kriteria = $_POST["id_kriteria_update_" . $k["id_kriteria"]];
      $this_sub_kriteria = array_filter($sub_kriteria, function ($sub) use ($k) {
        return $sub['id_kriteria'] == $k['id_kriteria'];
      });

      if(!empty($this_sub_kriteria)) {
        $id_sub = $_POST["id_sub_update_" . $k["id_kriteria"]];
        $nilai = null;
      } else {
        $id_sub = null;
        $nilai = $_POST["nilai_sub_update_" . $k["id_kriteria"]];
      }

      $data[] = [
        "id_alternatif" => $id_alternatif,
        "id_kriteria" => $id_kriteria,
        "id_sub" => $id_sub,
        "nilai" => $nilai
      ];
    }

    update_penilaian($id_alternatif, $data);
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
  <?php if(isset($penilaian) && $penilaian != null) : ?>
  <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1">Tambah Data Penilaian</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post">
            <div class="mb-2">
              <label for="id_alternatif_update_value">Alternatif</label>
              <input type="text" class="form-control" id="id_alternatif_update" name="id_alternatif_update" value="<?php echo $data_alternatif_penilaian[0]["id_alternatif"] ?>" hidden required>
              <input type="text" class="form-control" id="id_alternatif_update_value" name="id_alternatif_update_value" value="<?php echo $data_alternatif_penilaian[0]["kd_alternatif"] ?> - <?php echo $data_alternatif_penilaian[0]["nama"] ?>" disabled required>
            </div>
            <?php foreach($kriteria as $k) : ?>
            <div class="mb-2">
              <input type="number" hidden value="<?php echo $k["id_kriteria"] ?>" name="id_kriteria_update_<?php echo $k["id_kriteria"] ?>" id="id_kriteria_update_<?php echo $k["id_kriteria"] ?>">
              <?php
                $this_sub_kriteria = array_filter($sub_kriteria, function ($sub) use ($k) {
                  return $sub['id_kriteria'] == $k['id_kriteria'];
                });

                $this_penilaian_kriteria = array_filter($penilaian, function ($p) use ($k) {
                  return $p["id_kriteria"] == $k["id_kriteria"];
                });
                $this_penilaian_kriteria = reset($this_penilaian_kriteria);
                
                if (!empty($this_sub_kriteria)) : ?>
                  <label for="id_sub_update_<?php echo $k["id_kriteria"] ?>"><?php echo $k["kd_kriteria"] ?> - <?php echo $k["nama"] ?></label>
                  <select class="form-select" id="id_sub_update_<?php echo $k['id_kriteria']; ?>" name="id_sub_update_<?php echo $k['id_kriteria']; ?>" required>
                      <option <?php echo !isset($this_penilaian_kriteria["id_sub"]) ? "selected" : "" ?> disabled value="">Pilih</option>
                      <?php foreach ($this_sub_kriteria as $sub) : ?>
                        <option value="<?php echo $sub['id_sub']; ?>"
                        <?php
                          echo $this_penilaian_kriteria && $this_penilaian_kriteria["id_sub"] == $sub["id_sub"] ? "selected" : ""
                        ?>><?php echo $sub['nama']; ?></option>
                      <?php endforeach; ?>
                  </select>
              <?php else : ?>
                  <label for="nilai_sub_update_<?php echo $k["id_kriteria"] ?>"><?php echo $k["kd_kriteria"] ?> - <?php echo $k["nama"] ?></label>
                  <input type="number" class="form-control" id="nilai_sub_update_<?php echo $k['id_kriteria']; ?>" name="nilai_sub_update_<?php echo $k['id_kriteria']; ?>" step="0.01" min="0" placeholder="Nilai"
                    value="<?php echo isset($this_penilaian_kriteria["nilai"]) ? $this_penilaian_kriteria["nilai"] : 0 ?>"
                  required/>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-primary" name="update">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Data Penilaian</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post">
            <div class="mb-2">
              <label for="id_alternatif">Alternatif</label>
              <select class="form-select" id="id_alternatif" name="id_alternatif" required>
                <option disabled selected value="">Pilih</option>
                <?php foreach($alternatif as $a) : ?>
                  <?php
                    $query = "SELECT * FROM tbl_penilaian WHERE id_alternatif = " . (int)$a["id_alternatif"];
                    $result = get($query);
                    if(empty($result)) :
                  ?>
                    <option value="<?php echo $a["id_alternatif"] ?>"><?php echo $a["kd_alternatif"] ?> - <?php echo $a["nama"] ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </div>
            <?php foreach($kriteria as $k) : ?>
            <div class="mb-2">
              <input type="number" hidden value="<?php echo $k["id_kriteria"] ?>" name="id_kriteria_<?php echo $k["id_kriteria"] ?>" id="id_kriteria_<?php echo $k["id_kriteria"] ?>">
              <?php
                $this_sub_kriteria = array_filter($sub_kriteria, function ($sub) use ($k) {
                  return $sub['id_kriteria'] == $k['id_kriteria'];
                });
                
                if (!empty($this_sub_kriteria)) : ?>
                  <label for="id_sub_<?php echo $k["id_kriteria"] ?>"><?php echo $k["kd_kriteria"] ?> - <?php echo $k["nama"] ?></label>
                  <select class="form-select" id="id_sub_<?php echo $k['id_kriteria']; ?>" name="id_sub_<?php echo $k['id_kriteria']; ?>" required>
                    <option disabled selected value="">Pilih</option>
                    <?php foreach ($this_sub_kriteria as $sub) : ?>
                        <option value="<?php echo $sub['id_sub']; ?>"><?php echo $sub['nama']; ?></option>
                    <?php endforeach; ?>
                  </select>
              <?php else : ?>
                  <label for="nilai_sub_<?php echo $k["id_kriteria"] ?>"><?php echo $k["kd_kriteria"] ?> - <?php echo $k["nama"] ?></label>
                  <input type="number" class="form-control" id="nilai_sub_<?php echo $k['id_kriteria']; ?>" name="nilai_sub_<?php echo $k['id_kriteria']; ?>" step="0.01" min="0" placeholder="Nilai" required/>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-primary" name="create">Simpan</button>
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
            <li class="sidebar-item selected">
              <a class="sidebar-link active" href="penilaian_read.php" aria-expanded="false">
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
                <i class="ti ti-database fs-6 me-2"></i>Data Penilaian
              </span>
            </div>
            <div>
              <button type="button" class="btn btn-outline-danger m-1" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="ti ti-plus me-2"></i>Tambah Data
              </button>
            </div>
          </div>
          <div class="card mt-3">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-semibold">
                  <i class="ti ti-table me-2"></i>
                  Daftar Data Penilaian
                </h5>
                <form method="post" class="d-flex gap-2">
                  <input type="search" class="form-control" style="height: fit-content !important" aria-label="Search" placeholder="Cari data..."
                    value="<?php echo $keyword ?>" name="keyword">
                  <button name="search" class="btn btn-primary rounded-2">Cari</a>
                </form>
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
                        <h6 class="fw-normal mb-0"><?php echo $d["kd_alternatif"] ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $d["nama"] ?></h6>
                      </td>
                      <td class="border-bottom-0 d-flex gap-2">
                        <a href="penilaian_read.php?<?php echo $keyword ? "search=" . urlencode($keyword) . "&" : "" ?>id=<?php echo $d["id_alternatif"] ?>" class="btn btn-warning" data-bs-target="#exampleModal1">Edit</a>
                        <a href="penilaian_delete.php?delete=<?php echo $d["id_alternatif"] ?>" class="btn btn-danger">Hapus</a>
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
    <?php if($penilaian !== null) : ?>
      const modal = new bootstrap.Modal(document.getElementById('exampleModal1'));
      modal.show();
    <?php endif; ?>
  </script>
</body>

</html>