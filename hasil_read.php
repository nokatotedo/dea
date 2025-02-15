<?php
  require 'functions.php';
  
  session_start();
  if(!isset($_SESSION['login'])) {
    header("location:login.php");
  }

  $data = get_hasil();
  usort($data, function($a, $b) {
    return $b['yi'] <=> $a['yi'];
  });

  $categories = [];
  $yi_values = [];
  $max_values = [];
  $min_values = [];
  foreach ($data as $d) {
    $categories[] = $d['nama'];
    $yi_values[] = number_format($d['yi'], 3);
    $max_values[] = number_format($d['max'], 3);
    $min_values[] = number_format($d['min'], 3);
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
            <li class="sidebar-item">
              <a class="sidebar-link" href="perhitungan_read.php" aria-expanded="false">
                <span>
                  <i class="ti ti-math-symbols"></i>
                </span>
                <span class="hide-menu">Proses Perhitungan</span>
              </a>
            </li>
            <?php endif; ?>
            <li class="sidebar-item selected">
              <a class="sidebar-link active" href="hasil_read.php" aria-expanded="false">
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
                <i class="ti ti-database fs-6 me-2"></i>Data Hasil
              </span>
            </div>
          </div>
          <div class="card mt-3">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-semibold">
                  <i class="ti ti-table me-2"></i>
                  Ranking Hasil 
                </h5>
              </div>
              <div id="chart" class="mt-3"></div>
              <div class="d-flex gap-2 align-items-center justify-content-center">
                <div style="width: 10px; height: 10px; background-color: #FA896B; border-radius: 50%"></div>
                <div class="me-4">Max</div>
                <div style="width: 10px; height: 10px; background-color: #5D87FF; border-radius: 50%"></div>
                <div class="me-4">Yi (Max - Min)</div>
                <div style="width: 10px; height: 10px; background-color: #49BEFF; border-radius: 50%"></div>
                <div class="me-4">Min</div>
              </div>
              <div class="table-responsive mt-3">
                <div class="text-center fs-4 fw-bolder mb-3 d-none" id="table-title">Laporan Perangkingan Sistem Pembuat Keputusan</br>Penentuan Lokasi Strategis Pembangunan Komplek Perumahan</div>
                <table class="table text-nowrap mb-0 align-middle" style="border: 1px solid #ebf1f6" id="table">
                  <thead class="text-dark fs-4">
                    <tr>
                      <?php foreach(["Kode Alternatif", "Nama Alternatif", "Maksimum", "Minimum", "Yi (Max-Min)", "Ranking"] as $h) : ?>
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
                        <h6 class="fw-normal mb-0"><?php echo $d["kd_alternatif"] ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $d["nama"] ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo number_format($d["max"], 3) ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo number_format($d["min"], 3) ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo number_format($d["yi"], 3) ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0"><?php echo $index + 1 ?></h6>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                <div class="flex-column align-items-end mt-5 d-none" id="signature-date">
                  <p class="mb-0" id="date"></p>
                  <p class="mb-0 mt-5 pt-5">Direktur PT Graha Citra Mulia</p>
                </div>
                <?php if(isset($data[0])) : ?>
                <div class="mt-3 text-center fst-italic">Dari hasil penilaian menggunakan metode MOORA, disimpulkan bahwa alternatif lokasi strategis pembangunan komplek perumahan ada di <span class="fw-bolder"><?php echo $data[0]["nama"] ?></span>.<br>Klik tombol cetak untuk export data.</div>
                <?php endif; ?>
                <div class="d-flex gap-2 justify-content-center">
                  <div class="w-full text-center">
                    <button id="download" class="btn btn-outline-success mt-3" onclick="generateResult()">Cetak Hasil</button>
                  </div>
                  <div class="w-full text-center">
                    <button id="download" class="btn btn-outline-success mt-3" onclick="generateRecommendation('<?php echo $data[0]['nama']; ?>')">Cetak Rekomendasi</button>
                  </div>
                </div>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" integrity="sha512-YcsIPGdhPK4P/uRW6/sruonlYj+Q7UHWeKfTAkBW+g83NKM+jMJFJ4iAPfSnVp7BKD4dKMHmVSvICUbE/V1sSw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    var chart = {
      series: [
        { name: "Max", data: <?php echo json_encode($max_values); ?> },
        { name: "Yi (Max - Min)", data: <?php echo json_encode($yi_values); ?> },
        { name: "Min", data: <?php echo json_encode($min_values); ?> },
      ],

      chart: {
        type: "bar",
        height: 345,
        toolbar: { show: true },
        foreColor: "#adb0bb",
        fontFamily: 'inherit',
        sparkline: { enabled: false },
      },

      colors: ["#FA896B", "#5D87FF", "#49BEFF"],

      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: "35%",
          borderRadius: [6],
          borderRadiusApplication: 'end',
          borderRadiusWhenStacked: 'all'
        },
      },
      markers: { size: 0 },

      dataLabels: {
        enabled: false,
      },

      legend: {
        show: false,
      },

      grid: {
        borderColor: "rgba(0,0,0,0.1)",
        strokeDashArray: 3,
        xaxis: {
          lines: {
            show: false,
          },
        },
      },

      xaxis: {
        type: "category",
        categories: <?php echo json_encode($categories); ?>,
        labels: {
          style: { cssClass: "grey--text lighten-2--text fill-color" },
        },
      },

      yaxis: {
        show: true,
        min: 0,
        max: <?php echo $max_values[0] + 0.1; ?>,
        tickAmount: 4,
        labels: {
          style: {
            cssClass: "grey--text lighten-2--text fill-color",
          },
        },
      },
      stroke: {
        show: true,
        width: 3,
        lineCap: "butt",
        colors: ["transparent"],
      },

      tooltip: { theme: "light" },

      responsive: [
        {
          breakpoint: 600,
          options: {
            plotOptions: {
              bar: {
                borderRadius: 3,
              }
            },
          }
        }
      ]
    };

    var chart = new ApexCharts(document.querySelector("#chart"), chart);
    chart.render();

    function setDate() {
      const today = new Date()
      const options = { day: 'numeric', month: 'long', year: 'numeric' }
      const formattedDate = today.toLocaleDateString('id-ID', options)
      document.getElementById('date').textContent = `Padang, ${formattedDate}`
    }

    function generateResult() {
      const title = document.getElementById('table-title')
      const signatureDate = document.getElementById('signature-date')
      const element = document.getElementById('table')
      const rows = table.querySelectorAll('tbody tr');
      
      const clonedTitle = title.cloneNode(true)
      const clonedSignatureDate = signatureDate.cloneNode(true)
      clonedTitle.classList.remove('d-none')
      clonedSignatureDate.classList.remove('d-none')
      clonedSignatureDate.classList.add('d-flex')
      const wrapper = document.createElement('div')
      wrapper.appendChild(clonedTitle)
      wrapper.appendChild(element.cloneNode(true))
      wrapper.appendChild(clonedSignatureDate)

      const clonedTable = wrapper.querySelector('table')
      const clonedRows = clonedTable.querySelectorAll('tbody tr');
      const clonedHeaders = clonedTable.querySelectorAll('thead tr th');
      clonedHeaders[2].remove();
      clonedHeaders[3].remove();

      clonedRows[0].style.backgroundColor = '#d4edda'
      clonedRows.forEach(row => {
        row.children[2].remove()
        row.children[2].remove()
      });
      
      const options = {
        margin: 10,
        filename: `Hasil - ${getFormattedDate()}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
      }

      html2pdf().set(options).from(wrapper).save()
    }

    function generateRecommendation(location) {
      const doc = new jsPDF();

      doc.setFont("helvetica", "normal");
      doc.setFontSize(12);
      let y = 20;
      const x = 20;
      const maxWidth = 170;

      doc.setFont("helvetica", "bold");
      doc.setFontSize(14);
      const title = "Rekomendasi Lokasi Strategis Pembangunan Komplek Perumahan";
      const textWidth = doc.getTextWidth(title);
      const pageWidth = doc.internal.pageSize.width;
      doc.text(title, (pageWidth - textWidth) / 2, y);
      y += 10;

      doc.setFont("helvetica", "normal");
      doc.setFontSize(12);
      const paragraph = `Berdasarkan hasil analisis sistem terkait penentuan lokasi strategis pembangunan komplek perumahan menggunakan metode MOORA, ` +
        `${location} terpilih menjadi alternatif terbaik. Lokasi ini direkomendasikan sebagai kawasan paling strategis untuk pembangunan komplek perumahan ` +
        `karena memenuhi berbagai aspek utama yang mendukung keberlanjutan serta memberikan keuntungan bagi pengembang dan calon penghuni.`;
      const wrappedParagraph = doc.splitTextToSize(paragraph, maxWidth);
      doc.text(wrappedParagraph, x, y);
      y += wrappedParagraph.length * 6;

      doc.text("Berikut adalah lima alasan utama direkomendasikannya " + location + " sebagai lokasi strategis:", x, y - 5);
      const points = [
        "1. Ketersediaan Lahan yang Memadai\n" + location + " memiliki luas lahan yang cukup untuk mendukung pembangunan komplek perumahan. " +
        "Lahan yang tersedia juga memiliki kondisi yang relatif datar, sehingga memudahkan proses konstruksi dan mengurangi biaya pematangan lahan.",
        "2. Harga Tanah yang Kompetitif\nDibandingkan dengan alternatif lain, harga tanah di " + location + " relatif lebih terjangkau. " +
        "Hal ini memberikan keuntungan bagi pengembang dalam menekan biaya investasi awal serta memungkinkan harga jual rumah yang lebih kompetitif bagi masyarakat.",
        "3. Potensi Perkembangan Wilayah yang Baik\n" + location + " menunjukkan potensi perkembangan yang tinggi dengan adanya proyek infrastruktur dan fasilitas umum yang mulai berkembang di sekitar lokasi. " +
        "Keberadaan akses jalan yang memadai serta rencana pengembangan ekonomi daerah menjadikan lokasi ini prospektif untuk jangka panjang.",
        "4. Jarak yang Relatif Dekat ke Pusat Kota\nLokasi " + location + " lebih dekat ke pusat kota dibandingkan dengan alternatif lainnya. " +
        "Jarak yang lebih dekat ini memudahkan aksesibilitas bagi penghuni komplek perumahan untuk bekerja, bersekolah, atau mendapatkan layanan kesehatan dan fasilitas umum lainnya.",
        "5. Kondisi Alam yang Mendukung\nKondisi geografis " + location + " dinilai lebih stabil dibandingkan beberapa alternatif lain yang memiliki risiko bencana alam seperti banjir atau tanah longsor. " +
        "Faktor ini sangat penting dalam perencanaan perumahan untuk menjamin keamanan dan kenyamanan penghuni di masa depan."
      ];

      points.forEach((point,index) => {
        const wrappedPoint = doc.splitTextToSize(point, maxWidth);
        doc.text(wrappedPoint, x, y);
        y += (wrappedPoint.length * 6);
      });

      y += 10;
      const today = new Date()
      const options = { day: 'numeric', month: 'long', year: 'numeric' }
      const formattedDate = today.toLocaleDateString('id-ID', options)
      doc.text("Padang, " + formattedDate, x, y);
      y += 5;
      doc.text("Hormat kami,", x, y);
      y += 25;
      doc.text("Direktur PT Graha Citra Mulia", x, y);

      doc.save("Rekomendasi - " + getFormattedDate() + ".pdf");
    }

    function getFormattedDate() {
      const today = new Date();
      const year = today.getFullYear();
      const month = String(today.getMonth() + 1).padStart(2, '0');
      const day = String(today.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    }

    setDate()
  </script>
</body>

</html>