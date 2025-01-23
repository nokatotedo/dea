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

    $kd_kriteria = $data["kd_kriteria"];
    $nama = $data["nama"];
    $jenis = $data["jenis"];
    $bobot = $data["bobot"];
    $query = mysqli_query($conn, "INSERT INTO tbl_kriteria (kd_kriteria, nama, jenis, bobot)
      VALUES('$kd_kriteria', '$nama', '$jenis', '$bobot')
    ");

    header('location:kriteria_read.php');
  }

  function create_alternatif($data) {
    global $conn;

    $kd_alternatif = $data['kd_alternatif'];
    $nama = $data['nama'];
    $query = mysqli_query($conn, "INSERT INTO tbl_alternatif (kd_alternatif, nama)
      VALUES('$kd_alternatif', '$nama')
    ");

    header('location:alternatif_read.php');
  }

  function delete_kriteria($id) {
    global $conn;

    $id = (int)$id;

    if($id > 0) {
      mysqli_query($conn, "DELETE FROM tbl_kriteria WHERE id_kriteria = '$id'");
      mysqli_query($conn, "DELETE FROM tbl_subkriteria WHERE id_kriteria = '$id'");
      mysqli_query($conn, "DELETE FROM tbl_penilaian WHERE id_kriteria = '$id'");
    }
    header('location:kriteria_read.php');
  }

  function delete_alternatif($id) {
    global $conn;

    $id = (int)$id;
    if($id > 0) {
      mysqli_query($conn, "DELETE FROM tbl_alternatif WHERE id_alternatif = '$id'");
      mysqli_query($conn, "DELETE FROM tbl_penilaian WHERE id_alternatif = '$id'");
    }
    header('location:alternatif_read.php');
  }

  function update_kriteria($id, $data) {
    global $conn;

    $kd_kriteria = $data["kd_kriteria"];
    $nama = $data["nama"];
    $jenis = $data["jenis"];
    $bobot = $data["bobot"];
    $query = "UPDATE tbl_kriteria SET
      nama = '$nama',
      jenis = '$jenis',
      bobot = $bobot,
      kd_kriteria = '$kd_kriteria'
      WHERE id_kriteria = $id
    ";

    mysqli_query($conn, $query);
    header('location:kriteria_read.php');
  }

  function update_alternatif($id, $data) {
    global $conn;

    $kd_alternatif = $data['kd_alternatif'];
    $nama = $data['nama'];
    $query = "UPDATE tbl_alternatif SET
      nama = '$nama',
      kd_alternatif = '$kd_alternatif'
      WHERE id_alternatif = $id
    ";

    mysqli_query($conn, $query);
    header('location:alternatif_read.php');
  }

  function create_sub_kriteria($data) {
    global $conn;

    $id_kriteria = $data["id_kriteria"];
    $nama = $data["nama"];
    $nilai = $data["nilai"];
    $kriteria = get("SELECT * FROM tbl_subkriteria WHERE id_kriteria = '$id_kriteria'");
    if(count($kriteria) == 0) {
      $id_kriteria_delete = (int)$id_kriteria;
      mysqli_query($conn, "DELETE FROM tbl_penilaian WHERE id_kriteria = '$id_kriteria_delete'");
    }

    $query = mysqli_query($conn, "INSERT INTO tbl_subkriteria (id_kriteria, nama, nilai)
      VALUES('$id_kriteria', '$nama', '$nilai')
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
      WHERE id_sub = $id
    ";

    mysqli_query($conn, $query);
    $result = get("SELECT * FROM tbl_subkriteria WHERE id_sub = '$id'");
    $kriteria = $result[0];
    $kriteria_id = $kriteria["id_kriteria"];
    header("location:sub_kriteria_update.php?id=$kriteria_id");
  }

  function delete_sub_kriteria($id) {
    global $conn;

    $id = (int)$id;

    if($id > 0) {
      $result = get("SELECT * FROM tbl_subkriteria WHERE id_sub = '$id'");
      $kriteria = $result[0];
      $kriteria_id = $kriteria["id_kriteria"];
      mysqli_query($conn, "DELETE FROM tbl_subkriteria WHERE id_sub = '$id'");
      mysqli_query($conn, "DELETE FROM tbl_penilaian WHERE id_sub = '$id'");
    }
    
    header("location:sub_kriteria_update.php?id=$kriteria_id");
  }

  function create_penilaian($data) {
    global $conn;

    foreach($data as $d) {
      $id_alternatif = (int)$d['id_alternatif'];
      $id_kriteria = (int)$d['id_kriteria'];
      $id_sub = isset($d['id_sub']) ? (int)$d["id_sub"] : null;
      $nilai = isset($d['nilai']) ? (float)$d['nilai'] : null;
      $query = mysqli_query($conn, "INSERT INTO tbl_penilaian (id_alternatif, id_kriteria, id_sub, nilai)
        VALUES('$id_alternatif', '$id_kriteria', " . (is_null($id_sub) ? 'NULL' : "'$id_sub'") . ", " . (is_null($nilai) ? 'NULL' : "'$nilai'") . ")
      ");
    }

    header('location:penilaian_read.php');
  }

  function update_penilaian($id, $data) {
    global $conn;

    mysqli_query($conn, "DELETE FROM tbl_penilaian WHERE id_alternatif = $id");
    foreach($data as $d) {
      $id_alternatif = (int)$d['id_alternatif'];
      $id_kriteria = (int)$d['id_kriteria'];
      $id_sub = isset($d['id_sub']) ? (int)$d["id_sub"] : null;
      $nilai = isset($d['nilai']) ? (float)$d['nilai'] : null;
      $query = mysqli_query($conn, "INSERT INTO tbl_penilaian (id_alternatif, id_kriteria, id_sub, nilai)
        VALUES('$id_alternatif', '$id_kriteria', " . (is_null($id_sub) ? 'NULL' : "'$id_sub'") . ", " . (is_null($nilai) ? 'NULL' : "'$nilai'") . ")
      ");
    }

    header('location:penilaian_read.php');
  }

  function delete_penilaian($id) {
    global $conn;

    mysqli_query($conn, "DELETE FROM tbl_penilaian WHERE id_alternatif = $id");
    header('location:penilaian_read.php');
  }

  function get_perhitungan() {
    $perhitungan = get("SELECT * FROM tbl_penilaian");
    $data = [];
    foreach($perhitungan as $p) {
      $alternatif = get("SELECT * FROM tbl_alternatif WHERE id_alternatif = " . $p["id_alternatif"]);
      $kriteria = get("SELECT * FROM tbl_kriteria WHERE id_kriteria = " . $p["id_kriteria"]);
      $nilai = $p["nilai"];
      $subkriteria = $p["id_sub"] ? (get("SELECT * FROM tbl_subkriteria WHERE id_sub = " . $p["id_sub"]))[0] : null;
      
      $isAlternatifExists = false;
      foreach($data as &$alternatifExists) {
        if($alternatifExists['alternatif']['id_alternatif'] == $alternatif[0]['id_alternatif']) {
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
      $head[] = $k["kd_kriteria"];
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

  function get_hasil() {
    $kriteria_head = get_perhitungan_head();
    $data = get_perhitungan();

    $data_matrix = [];
    foreach($data as $index => $d) {
      $data_matrix_alternatif = [];

      foreach($kriteria_head as $kh) {
        foreach($d["kriteria"] as $dk) {
          if($dk["kriteria"]["kd_kriteria"] == $kh) {
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

        $data_matrix_alternatif[] = [
          "nilai" => $nilai,
          "bobot" => $bobot,
          "is_cost" => $is_cost
        ];
      }

      $data_matrix[] = $data_matrix_alternatif;
    }

    $normalized_matrix = get_normalized_matrix($data_matrix, get_squared_sum_column($data_matrix));
    
    $data_result = [];
    foreach($data as $index => $d) {
      $max = 0;
      $min = 0;
      $yi = 0;

      foreach($normalized_matrix[$index] as $normalized_value) {
        if($normalized_value["is_cost"]) {
          $min += $normalized_value["nilai_normalized"] * $normalized_value["bobot"];
        } else {
          $max += $normalized_value["nilai_normalized"] * $normalized_value["bobot"];
        }
      }

      $yi = $max - $min;
      $data_result[] = [
        "kd_alternatif" => $d["alternatif"]["kd_alternatif"],
        "nama" => $d["alternatif"]["nama"],
        "min" => $min,
        "max" => $max,
        "yi" => $yi
      ];
    }

    return $data_result;
  }
?>