<?php
  $conn = mysqli_connect("localhost", "root", "panduganteng", "skripsi_spk");

  function login($username, $password) {
    global $conn;

    $query = mysqli_query($conn, "SELECT * FROM tbl_user WHERE username LIKE '$username' AND password='$password'");
    if(mysqli_num_rows($query) > 0) {
      $user = mysqli_fetch_assoc($query);

      $_SESSION['login'] = $user['nama'];
      header('location:index.php');
    } else {
      header('location:login.php?error=true');
    }
  }

  function get($query) {
    global $conn;

    $result = mysqli_query($conn, $query);
    $rows = [];
    while($row = mysqli_fetch_assoc($result)) {
      $rows[] = $row;
    }

    return $rows;
  }

  function search_kriteria($keyword) {
    header('location:kriteria_read.php?search=' . urlencode($keyword));
  }

  function search_alternatif($keyword) {
    header('location:alternatif_read.php?search=' . urlencode($keyword));
  }

  function search_sub_kriteria($keyword) {
    header('location:sub_kriteria_read.php?search=' . urlencode($keyword));
  }

  function search_penilaian($keyword) {
    header('location:penilaian_read.php?search=' . urlencode($keyword));
  }

  function create_kriteria($data) {
    global $conn;

    $kode = $data["kode"];
    $nama = $data["nama"];
    $jenis = $data["jenis"];
    $bobot = $data["bobot"];
    $query = mysqli_query($conn, "INSERT INTO tbl_kriteria (kode, nama, jenis, bobot)
      VALUES('$kode', '$nama', '$jenis', '$bobot')
    ");

    header('location:kriteria_read.php');
  }

  function create_alternatif($data) {
    global $conn;

    $kode = $data['kode'];
    $nama = $data['nama'];
    $query = mysqli_query($conn, "INSERT INTO tbl_alternatif (kode, nama)
      VALUES('$kode', '$nama')
    ");

    header('location:alternatif_read.php');
  }

  function delete_kriteria($id) {
    global $conn;

    $id = (int)$id;

    if($id > 0) {
      mysqli_query($conn, "DELETE FROM tbl_kriteria WHERE kd_kriteria = '$id'");
      mysqli_query($conn, "DELETE FROM tbl_subkriteria WHERE kd_kriteria = '$id'");
      mysqli_query($conn, "DELETE FROM tbl_penilaian WHERE kd_kriteria = '$id'");
    }
    header('location:kriteria_read.php');
  }

  function delete_alternatif($id) {
    global $conn;

    $id = (int)$id;
    if($id > 0) {
      mysqli_query($conn, "DELETE FROM tbl_alternatif WHERE kd_alternatif = '$id'");
      mysqli_query($conn, "DELETE FROM tbl_penilaian WHERE kd_alternatif = '$id'");
    }
    header('location:alternatif_read.php');
  }

  function update_kriteria($id, $data) {
    global $conn;

    $kode = $data["kode"];
    $nama = $data["nama"];
    $jenis = $data["jenis"];
    $bobot = $data["bobot"];
    $query = "UPDATE tbl_kriteria SET
      nama = '$nama',
      jenis = '$jenis',
      bobot = $bobot,
      kode = '$kode'
      WHERE kd_kriteria = $id
    ";

    mysqli_query($conn, $query);
    header('location:kriteria_read.php');
  }

  function update_alternatif($id, $data) {
    global $conn;

    $kode = $data['kode'];
    $nama = $data['nama'];
    $query = "UPDATE tbl_alternatif SET
      nama = '$nama',
      kode = '$kode'
      WHERE kd_alternatif = $id
    ";

    mysqli_query($conn, $query);
    header('location:alternatif_read.php');
  }

  function create_sub_kriteria($data) {
    global $conn;

    $kd_kriteria = $data["kd_kriteria"];
    $nama = $data["nama"];
    $nilai = $data["nilai"];
    $query = mysqli_query($conn, "INSERT INTO tbl_subkriteria (kd_kriteria, nama, nilai)
      VALUES('$kd_kriteria', '$nama', '$nilai')
    ");

    header('location:sub_kriteria_read.php');
  }

  function update_sub_kriteria($id, $data) {
    global $conn;

    $nama = $data['nama'];
    $nilai = $data['nilai'];
    $query = "UPDATE tbl_subkriteria SET
      nama = '$nama',
      nilai = $nilai
      WHERE kd_sub = $id
    ";

    mysqli_query($conn, $query);
    $result = get("SELECT * FROM tbl_subkriteria WHERE kd_sub = '$id'");
    $kriteria = $result[0];
    $kriteria_id = $kriteria["kd_kriteria"];
    header("location:sub_kriteria_update.php?id=$kriteria_id");
  }

  function delete_sub_kriteria($id) {
    global $conn;

    $id = (int)$id;

    if($id > 0) {
      $result = get("SELECT * FROM tbl_subkriteria WHERE kd_sub = '$id'");
      $kriteria = $result[0];
      $kriteria_id = $kriteria["kd_kriteria"];
      mysqli_query($conn, "DELETE FROM tbl_subkriteria WHERE kd_sub = '$id'");
      mysqli_query($conn, "DELETE FROM tbl_penilaian WHERE kd_sub = '$id'");
    }
    
    header("location:sub_kriteria_update.php?id=$kriteria_id");
  }

  function create_penilaian($data) {
    global $conn;

    foreach($data as $d) {
      $kd_alternatif = (int)$d['kd_alternatif'];
      $kd_kriteria = (int)$d['kd_kriteria'];
      $kd_sub = isset($d['kd_sub']) ? (int)$d["kd_sub"] : null;
      $nilai = isset($d['nilai']) ? (float)$d['nilai'] : null;
      $query = mysqli_query($conn, "INSERT INTO tbl_penilaian (kd_alternatif, kd_kriteria, kd_sub, nilai)
        VALUES('$kd_alternatif', '$kd_kriteria', " . (is_null($kd_sub) ? 'NULL' : "'$kd_sub'") . ", " . (is_null($nilai) ? 'NULL' : "'$nilai'") . ")
      ");
    }

    header('location:penilaian_read.php');
  }

  function update_penilaian($id, $data) {
    global $conn;

    mysqli_query($conn, "DELETE FROM tbl_penilaian WHERE kd_alternatif = $id");
    foreach($data as $d) {
      $kd_alternatif = (int)$d['kd_alternatif'];
      $kd_kriteria = (int)$d['kd_kriteria'];
      $kd_sub = isset($d['kd_sub']) ? (int)$d["kd_sub"] : null;
      $nilai = isset($d['nilai']) ? (float)$d['nilai'] : null;
      $query = mysqli_query($conn, "INSERT INTO tbl_penilaian (kd_alternatif, kd_kriteria, kd_sub, nilai)
        VALUES('$kd_alternatif', '$kd_kriteria', " . (is_null($kd_sub) ? 'NULL' : "'$kd_sub'") . ", " . (is_null($nilai) ? 'NULL' : "'$nilai'") . ")
      ");
    }

    header('location:penilaian_read.php');
  }

  function delete_penilaian($id) {
    global $conn;

    mysqli_query($conn, "DELETE FROM tbl_penilaian WHERE kd_alternatif = $id");
    header('location:penilaian_read.php');
  }

  function get_perhitungan_bobot() {
    $perhitungan = get("SELECT * FROM tbl_penilaian");
    $data = [];
    foreach($perhitungan as $p) {
      $alternatif = get("SELECT * FROM tbl_alternatif WHERE kd_alternatif = " . $p["kd_alternatif"]);
      $kriteria = get("SELECT * FROM tbl_kriteria WHERE kd_kriteria = " . $p["kd_kriteria"]);
      $nilai = $p["nilai"];
      $subkriteria = $p["kd_sub"] ? (get("SELECT * FROM tbl_subkriteria WHERE kd_sub = " . $p["kd_sub"]))[0] : null;
      
      $isAlternatifExists = false;
      foreach($data as &$alternatifExists) {
        if($alternatifExists['alternatif']['kd_alternatif'] == $alternatif[0]['kd_alternatif']) {
          $isAlternatifExists = true;
          $alternatifExists['kriteria'][] = [
            "kriteria" => $kriteria[0],
            "subkriteria" => $subkriteria,
            "nilai" => $nilai
          ];
          break;
        }
      }

      if (!$isAlternatifExists) {
        $data[] = [
          "alternatif" => $alternatif[0],
          "kriteria" => [
            [
              "kriteria" => $kriteria[0],
              "subkriteria" => $subkriteria,
              "nilai" => $nilai
            ]
          ]
        ];
      }
    }

    return $data;
  }

  function get_perhitungan_head() {
    $head = [];
    $kriteria = get("SELECT * FROM tbl_kriteria");
    foreach($kriteria as $k) {
      $head[] = $k["kode"];
    }

    return $head;
  }

  function get_squared_sum_column($matrix) {
    $squared_sum_column = [];
    foreach($matrix as $row) {
      foreach($row as $col_index => $col) {
        if(!isset($squared_sum_column[$col_index])) {
          $squared_sum_column[$col_index] = 0;
        }

        $squared_sum_column[$col_index] += pow($col["nilai"], 2);
      }
    }

    return $squared_sum_column;
  }

  function get_normalized_matrix($matrix, $squared_sum_column) {
    $normalized_matrix = [];
    foreach($matrix as $row) {
      $normalized_row = [];
      foreach($row as $col_index => $col) {
        $sqrt_sum = sqrt($squared_sum_column[$col_index]);
        $normalized_row[] = [
          "nilai_normalized" => $col["nilai"] / $sqrt_sum,
          "nilai" => $col["nilai"],
          "bobot" => $col["bobot"],
          "is_cost" => $col["is_cost"]
        ];
      }

      $normalized_matrix[] = $normalized_row;
    }

    return $normalized_matrix;
  }
?>