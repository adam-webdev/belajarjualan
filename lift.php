<?php
require '../../vendor/autoload.php';
include '../../php/koneksi/koneksi.php';


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;


if (isset($_GET['id_gedung'])) {
  // Query untuk mengambil data tower berdasarkan id_gedung dan paging
  // Ambil ID Gedung dari tombol export (misalnya via POST atau GET)

  $id_gedung = (int) $_GET['id_gedung'];
  // Query dengan JOIN
  $sql = "
    SELECT
        gedung.id_gedung,
        gedung.nama_gedung,
        gedung.project_code,
        gedung.address,
        gedung.created_at AS gedung_created_at,

        audit_tower.id_tower,
        audit_tower.nama_tower,
        audit_tower.pic,
        audit_tower.jumlah_lantai,
        audit_tower.created_at AS tower_created_at,

        audit_lift.id_lift,
        audit_lift.lift_no,
        audit_lift.lift_brand,
        audit_lift.lift_type,

        audit_komponen.id AS audit_komponen_id,
        audit_komponen.keterangan AS audit_komponen_keterangan,
        audit_komponen.foto_bukti AS audit_komponen_foto_bukti,
        audit_komponen.prioritas AS audit_komponen_prioritas,
        temuan_komponen.nama_temuan AS audit_komponen_temuan,
        solusi_komponen.nama_solusi AS audit_komponen_solusi,

        instalations.id_instalasi,
        instalations.foto_instalasi,
        instalations.nama_instalasi,
        instalations.deskripsi AS instalasi_deskripsi,

        komponen.id_komponen,
        komponen.code_komponen,
        komponen.nama_komponen,
        komponen.keterangan AS komponen_keterangan

    FROM audit_komponen

    -- Join ke table tower
    LEFT JOIN gedung ON gedung.id_gedung = audit_komponen.id_gedung


    -- Join ke table audit_komponen
    LEFT JOIN audit_tower ON audit_komponen.id_tower = audit_tower.id_tower

    -- Join ke table lift
    LEFT JOIN audit_lift ON audit_komponen.id_lift = audit_lift.id_lift

    -- Join ke table temuan_komponen
    LEFT JOIN temuan_komponen ON audit_komponen.id_temuan = temuan_komponen.id_temuan

    -- Join ke table solusi_komponen
    LEFT JOIN solusi_komponen ON audit_komponen.id_solusi = solusi_komponen.id_solusi

    -- Join ke table instalasi
    LEFT JOIN instalations ON audit_lift.id_lift = instalations.id_lift

    -- Join ke table komponen
    LEFT JOIN komponen ON audit_komponen.id_komponen = komponen.id_komponen

    WHERE audit_komponen.id_gedung = ?
    ORDER BY komponen.id_komponen ASC
  ";
  // Eksekusi query
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $id_gedung);
  $stmt->execute();
  $results = $stmt->get_result();
  // Ambil data
  if ($results->num_rows > 0) {
    // Proses hasil query menjadi array
    $data = [];
    $lift_komp = [];
    $lift = [];
    $komp_keterangan = [];
    $komp_keterangan_tes = [];

    $test = [];
    while ($row = $results->fetch_assoc()) {
      // Menambahkan semua data ke array $data

      $data[] = $row;


      // Ambil keterangan dan no_lift
      $keterangan = $row['komponen_keterangan'];
      $no_lift = $row['lift_no'];

      $komp_keterangan[$keterangan][] = $row;
      $lift[$no_lift][] = $row;
      // Jika sudah ada lift dengan nomor yang sama, tambahkan entri keterangan baru
      if (!isset($lift_komp[$no_lift])) {
        $lift_komp[$no_lift] = [];
      }

      // Menambahkan data ke keterangan yang sesuai
      if (!isset($lift_komp[$no_lift][$keterangan])) {
        $lift_komp[$no_lift][$keterangan] = [];
      }
      // Tambahkan row ke dalam keterangan
      $lift_komp[$no_lift][$keterangan][] = $row;
    }
  } else {
    echo "Data tidak ditemukan.";
  }

  // Cek hasil

}
try {
  // 1. Buat spreadsheet baru

  $templatePath = './templates/audit.xlsx';
  $spreadsheet = IOFactory::load($templatePath);
  $sheetUtama = $spreadsheet->getSheet(0);
  $sheetUtama->setTitle('Defect Keseluruhan');

  $nama_gedung = $data[0]['nama_gedung'];
  $address = $data[0]['address'];
  $gedung_created_at = $data[0]['gedung_created_at'];
  $lift_no = $data[0]['lift_no'];

  $sheetUtama->setCellValue('C3', ' : ' . $nama_gedung);
  $sheetUtama->setCellValue('C4', ' : ' . $address);
  $sheetUtama->setCellValue('F3', ' : ALL UNIT');
  $sheetUtama->setCellValue('F4', ' : ' . $gedung_created_at);

  $targetCell = 'G'; // Kolom target awal
  $headerRowTop = 8; // Baris untuk header lift
  $headerRow = 9; // Baris untuk header lift
  $dataStartRow = 12; // Baris awal untuk data lift
  $index = 0; // Untuk menghitung kolom target

  $data_lift = [];
  foreach ($lift as $nama_lift => $dataLift) {
    $data_lift[] = $nama_lift;
    // Tentukan kolom target untuk setiap lift (G, H, I, dst.)
    $targetColumn = chr(ord($targetCell) + $index++);


    // Set header lift di baris headerRow (baris 9)
    $headerCellTop = "{$targetColumn}{$headerRowTop}";

    $headerCell = "{$targetColumn}{$headerRow}";


    $sheetUtama->getStyle("{$headerCellTop}:{$headerCell}")->applyFromArray([
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => 'AFEEEE'], // Warna kuning
      ],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      ],
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
    ]);

    // header
    $sheetUtama->setCellValue($headerCell, $nama_lift);
    $sheetUtama->setCellValue($headerCellTop, null);

    $sheetUtama->getStyle($headerCell)->getFont()->setBold(true);
    // auto filter
    $sheetUtama->setAutoFilter("A9:{$targetColumn}{$headerRow}");

    $currentRow = $dataStartRow;


    $komp_number = 11;
    $fillmesin = 10;
    $no_sheet_utama = 1;

    $indexKomponen = 0; // Untuk menghitung kolom target
    $komp_number_utama = 11;
    $targetCellKomponen = 'G';
    // Kolom target awal

    foreach ($komp_keterangan as $i => $dataKeterangan) {
      $sheetUtama->setCellValue('B' . $komp_number, $i);
      $sheetUtama->getStyle('B' . $komp_number)->getFont()->setBold(true);
      // backgroun mesin
      $sheetUtama->getStyle("A{$fillmesin}:{$targetColumn}{$fillmesin}")->applyFromArray([
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          ],
        ],
        'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => ['argb' => 'FFFF00'], // Warna kuning
        ],
        'font' => [
          'color' => ['argb' => '000000'], // Warna font hitam
        ],
      ]);

      // bakcground komponen
      $sheetUtama->getStyle("A{$komp_number}:{$targetColumn}{$komp_number}")->applyFromArray([
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          ],
        ],
        'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => ['argb' => 'FFFF00'], // Warna kuning
        ],
        'font' => [
          'color' => ['argb' => '000000'], // Warna font hitam
        ],
      ]);
      $komp_number++;

      // Group komponen by nama_komponen
      $groupedKomponen = [];
      foreach ($dataKeterangan as $komponen) {
        $namaKomponen = $komponen['nama_komponen'];
        if (!isset($groupedKomponen[$namaKomponen])) {
          $groupedKomponen[$namaKomponen] = [
            'data' => $komponen,
            'lifts' => []
          ];
        }
        $groupedKomponen[$namaKomponen]['lifts'][] = $komponen['lift_no'];
      }

      // Write grouped komponen
      foreach ($groupedKomponen as $namaKomponen => $group) {
        $komponen = $group['data'];
        $sheetUtama->setCellValue('A' . $komp_number, $no_sheet_utama++);
        $sheetUtama->setCellValue('B' . $komp_number, $namaKomponen);
        $sheetUtama->setCellValue('C' . $komp_number, $komponen['audit_komponen_prioritas']);
        $sheetUtama->setCellValue('D' . $komp_number, $komponen['audit_komponen_temuan']);
        $sheetUtama->setCellValue('E' . $komp_number, $komponen['audit_komponen_solusi']);
        $sheetUtama->setCellValue('F' . $komp_number, $komp_number - 10);

        // Tentukan posisi lift yang sesuai (kolom G dan H)
        $col = 'G'; // Kolom pertama untuk data lift
        foreach ($lift as $lift_no => $lift_items) {
          // Cek jika lift_no ada dalam array lifts untuk komponen ini
          if (in_array($lift_no, $group['lifts'])) {
            $sheetUtama->setCellValue($col . $komp_number, 'V');
          } else {
            $sheetUtama->setCellValue($col . $komp_number, '');
          }
          $col++; // Berpindah ke kolom berikutnya
        }

        $sheetUtama->getStyle("A{$komp_number}:{$targetColumn}{$komp_number}")->applyFromArray([
          'borders' => [
            'allBorders' => [
              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
          ],
        ]);
        $komp_number++;
      }
    }
  }

  $templateSheet = $spreadsheet->getSheetByName('template');
  $templateSheet->setCellValue('C3', ' : ' . $nama_gedung);
  $templateSheet->setCellValue('C4', ' : ' . $address);
  $templateSheet->setCellValue('F4', ' : ' . $gedung_created_at);

  foreach ($lift_komp as $i => $dataLift) {
    $templateSheet->setCellValue('F3', ' : ' . $i);

    $sheet = clone $templateSheet;
    $sheet->setTitle($i);

    // Tambahkan sheet ke spreadsheet
    $spreadsheet->addSheet($sheet);

    $row = 11;
    $no = 1;

    foreach ($dataLift as $keterangan => $dataKomponen) {
      // sheet by lift
      $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          ],
        ],
        'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => ['argb' => 'FFFF00'], // Warna kuning
        ],
        'font' => [
          'color' => ['argb' => '000000'], // Warna font hitam
        ],
      ]);
      $sheet->setCellValue('B' . $row, $keterangan);
      $sheet->getStyle('B' . $row)->getFont()->setBold(true);
      $row++;

      // Group komponen by nama_komponen
      $groupedKomponen = [];
      foreach ($dataKomponen as $komponen) {
        $namaKomponen = $komponen['nama_komponen'];
        if (!isset($groupedKomponen[$namaKomponen])) {
          $groupedKomponen[$namaKomponen] = [
            'data' => $komponen,
            'keterangan' => []
          ];
        }
        if (!empty($komponen['audit_komponen_keterangan'])) {
          $groupedKomponen[$namaKomponen]['keterangan'][] = $komponen['audit_komponen_keterangan'];
        }
      }

      // Write grouped komponen
      foreach ($groupedKomponen as $namaKomponen => $group) {
        $komponen = $group['data'];
        $sheet->setCellValue('A' . $row, $no++);
        $sheet->setCellValue('B' . $row, $namaKomponen);
        $sheet->setCellValue('C' . $row, $komponen['audit_komponen_prioritas']);
        $sheet->setCellValue('D' . $row, $komponen['audit_komponen_temuan']);
        $sheet->setCellValue('E' . $row, $komponen['audit_komponen_solusi']);
        $sheet->setCellValue('F' . $row, $row - 10);

        // Gabungkan semua keterangan yang ada
        if (!empty($group['keterangan'])) {
          $sheet->setCellValue('G' . $row, implode(', ', array_unique($group['keterangan'])));
        } else {
          $sheet->setCellValue('G' . $row, 'V');
        }

        // Terapkan all border pada baris yang baru saja diisi
        $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
          'borders' => [
            'allBorders' => [
              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
          ],
        ]);

        $row++;
      }
    }
  }



  $fileName = 'audit.xlsx';
  $writer = new Xlsx($spreadsheet);

  // Set headers for direct download
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="' . $fileName . '"');
  header('Cache-Control: max-age=0');

  // Save to PHP output stream instead of file
  $writer->save('php://output');
  exit();
} catch (Exception $e) {
  echo "Terjadi kesalahan: " . $e->getMessage();
}