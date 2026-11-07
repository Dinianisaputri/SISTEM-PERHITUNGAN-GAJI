<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PKWT Satpam</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        p {
            margin: 4px 0;
            text-align: justify;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .identitas {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }

        .identitas td {
            padding: 1px 0;
            vertical-align: top;
        }

        .identitas .label {
            width: 115px;
        }

        .identitas .titik-dua {
            width: 12px;
        }

        .pasal {
            font-weight: bold;
            text-align: center;
            margin-top: 8px;
            margin-bottom: 4px;
            page-break-after: avoid;
        }

        .ayat {
            padding-left: 18px;
            text-indent: -18px;
            margin: 3px 0;
        }

        .party-description {
            margin-bottom: 30px;
        }

        
.tanggal {
    margin-top: 14px;
    margin-bottom: 0;
    text-align: left;
    page-break-after: avoid;
}

.ttd {
    width: 100%;
    border-collapse: collapse;
    margin-top: 63px;
    page-break-inside: avoid;
}

.ttd td {
    width: 50%;
    text-align: center;
    vertical-align: top;
    padding: 0;
}

.ttd .tanggal-ttd {
    padding-bottom: 4px;
}

.ttd .ruang-ttd {
    height: 45px;
    line-height: 45px;
}

.ttd .nama-ttd {
    font-weight: bold;
    text-decoration: underline;
    white-space: nowrap;
}

    </style>
</head>
<body>
@php
    $employee = $contract->employee;

    $formatTanggal = function ($tanggal) {
        if (!$tanggal) {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($tanggal)->format('d/m/Y');
        } catch (\Throwable $e) {
            return $tanggal;
        }
    };

    $tanggalBerakhir = $contract->tanggal_berakhir
        ? \Carbon\Carbon::parse($contract->tanggal_berakhir)->format('m/d/Y')
        : '-';

    $tanggalTandaTangan = $contract->tanggal_mulai
        ? \Carbon\Carbon::parse($contract->tanggal_mulai)
            ->locale('id')
            ->translatedFormat('d F Y')
        : now()->locale('id')->translatedFormat('d F Y');

    $namaKaryawan = $employee->name ?? '-';

    $alamatBagian = collect([
        $employee->alamat ?? null,
        $employee->desa->nama ?? null,
        $employee->kecamatan->nama ?? null,
        $employee->kabupaten->nama ?? null,
        $employee->provinsi->nama ?? null,
    ])->filter(fn ($item) => filled($item))->unique()->values();

    $alamat = $alamatBagian->isNotEmpty()
        ? $alamatBagian->implode(', ')
        : '-';
@endphp

<div class="judul">PERJANJIAN PEKERJA WAKTU TERTENTU</div>

<p>Yang bertanda tangan di bawah ini masing-masing :</p>

<table class="identitas">
    <tr>
        <td class="label">Nama</td>
        <td class="titik-dua">:</td>
        <td>YUDI PORWANTO</td>
    </tr>
    <tr>
        <td class="label">Jabatan</td>
        <td>:</td>
        <td>GA-HRD</td>
    </tr>
</table>

<p>
    Dalam hal ini mewakili dan bertindak untuk dan atas nama PT. Starfood
    International yang beralamat di Jl. Raya Deandels Desa Kandangsemangkon
    Kec. Paciran Kab. Lamongan yang selanjutnya disebut sebagai PIHAK PERTAMA.
</p>

<table class="identitas">
    <tr>
        <td class="label">Nama</td>
        <td class="titik-dua">:</td>
        <td>{{ $namaKaryawan }}</td>
    </tr>
    <tr>
        <td class="label">Jenis Kelamin</td>
        <td>:</td>
        <td>{{ $employee->jenis_kelamin ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Tempat Lahir</td>
        <td>:</td>
        <td>{{ $employee->tempat_lahir ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Tanggal Lahir</td>
        <td>:</td>
        <td>{{ $formatTanggal($employee->tanggal_lahir ?? null) }}</td>
    </tr>
    <tr>
        <td class="label">Alamat</td>
        <td>:</td>
        <td>{{ $alamat }}</td>
    </tr>
</table>

<p class="party-description">
    Dalam hal ini bertindak untuk dan atas nama dirinya sendiri yang
    selanjutnya disebut sebagai PIHAK KEDUA.
</p>

<p>
    Sehubungan dengan keadaan dan kondisi bahan baku yang bersifat musiman
    di perusahaan yang sewaktu-waktu berubah, bisa meningkat ataupun menurun
    dan apabila suatu saat terjadi peningkatan produksi serta untuk memenuhi
    kebutuhan produksi yang sifatnya temporer sesuai dengan musim bahan baku,
    maka diperlukan tambahan tenaga kerja.
</p>

<p>
    Pada saat ini keadaan tersebut terjadi yaitu peningkatan pekerjaan karena
    musim bahan baku, yang sifatnya temporer maka untuk mengisi kekurangan
    tenaga kerja yang dibutuhkan, sehubungan dengan hal tersebut Pihak Kedua
    telah bersedia untuk mengadakan hubungan kerja dengan ketentuan dan
    syarat-syarat tersebut di bawah ini :
</p>

<div class="pasal">Pasal 1</div>

<p>
    Bahwa dengan ini Pihak Kedua bersedia dipekerjakan sebagai pekerja di
    PT. Starfood International di bagian Keamanan / Security.
</p>

<div class="pasal">Pasal 2</div>

<p>
    Bahwa Pihak Kedua sebagai pekerja bersedia untuk melaksanakan pekerjaan
    yang diberikan oleh Pihak Pertama dan sanggup untuk melaksanakan
    pekerjaan tersebut dengan penuh rasa tanggung jawab.
</p>

<div class="pasal">Pasal 3</div>

<p>
    Bahwa Pihak Kedua sebagai pekerja bersedia untuk mematuhi dan mentaati
    tata tertib dan kedisiplinan kerja yang berlaku di PT. Starfood International.
</p>

<div class="pasal">Pasal 4</div>

<p class="ayat">
    1. Pihak Kedua diberikan upah yang dibayar berdasarkan hari kerjanya
    sesuai tarif per hari yang berlaku minimal sesuai dengan upah minimal
    Kabupaten Lamongan dan dibayarkan setiap 1 (satu) bulan sekali pada
    hari aktif di tiap bulannya.
</p>

<p class="ayat">
    2. Pihak Kedua bekerja sesuai jadwal shift yang telah ditentukan oleh
    PT Starfood International.
</p>

<div class="pasal">Pasal 5</div>

<p class="ayat">
    1. Perjanjian kerja ini berlaku terhitung sejak ditanda tangani oleh
    Pihak Pertama dan Pihak Kedua dan berakhir atau habis masa berlakunya
    pada tanggal {{ $tanggalBerakhir }}.
</p>

<p class="ayat">
    2. Perjanjian kerja ini dapat diperpanjang kembali dengan
    mempertimbangkan kebutuhan pekerjaan yang ada pada Pihak Pertama.
</p>

<div class="pasal">Pasal 6</div>

<p>Surat perjanjian ini dapat dibatalkan dan tidak berlaku apabila :</p>

<p class="ayat">
    1. Pihak Kedua mengundurkan diri atas kemauan sendiri.
</p>

<p class="ayat">
    2. Pihak Kedua tidak masuk kerja selama 5 (lima) hari secara
    berturut-turut tanpa keterangan yang sah.
</p>

<p class="ayat">3. Pihak Kedua meninggal dunia.</p>

<p class="ayat">
    4. Pihak Pertama atau Pihak Kedua ingkar dan tidak mampu memenuhi
    isi perjanjian ini.
</p>

<p class="ayat">
    5. Adanya keadaaan atau kejadian tertentu sesuai yang dicantumkan
    dalam peraturan perusahaan dapat menyebabkan berakhirnya hubungan kerja.
</p>

<div class="pasal">Pasal 7</div>

<p>
    Apabila sampai batas waktu masa berlakunya perjanjian ini berakhir
    sebagaimana ketentuan dalam Pasal 5 dan Pihak Pertama telah mengakhiri
    perjanjian kerja ini maka :
</p>

<p class="ayat">
    1. Hubungan kerja antara Pihak Pertama dengan Pihak Kedua berakhir
    dengan sendirinya atau batal demi hukum.
</p>

<p class="ayat">
    2. Bahwa Pihak Pertama tidak berkewajiban untuk memberikan uang
    pesangon, uang jasa atau ganti rugi berupa apapun kepada Pihak Kedua.
</p>

<p class="ayat">
    3. Bahwa Pihak Kedua tidak berhak menuntut apapun kepada Pihak Pertama
    sebagaimana dimaksud dalam ketentuan ayat 2 pasal ini termasuk di
    dalamnya adalah tuntutan perubahan status ketenagakerjaan.
</p>

<div class="pasal">Pasal 8</div>

<p class="ayat">
    1. Surat perjanjian kerja ini sudah dibaca dan dipahami oleh Pihak Kedua
    sesuai dengan isinya tanpa ada paksaan dari pihak manapun.
</p>

<p class="ayat">
    2. Surat perjanjian kerja ini bermaterai cukup dan mempunyai kekuatan hukum.
</p>


<table class="ttd">
    <tr>
        <td class="tanggal-ttd">Lamongan, {{ $tanggalTandaTangan }}</td>
        <td></td>
    </tr>
    <tr>
        <td>Pihak Pertama,</td>
        <td>Pihak Kedua,</td>
    </tr>
    <tr>
        <td class="ruang-ttd"></td>
        <td class="ruang-ttd"></td>
    </tr>
    <tr>
        <td class="nama-ttd">YUDI PORWANTO</td>
        <td class="nama-ttd">{{ $namaKaryawan }}</td>
    </tr>
</table>
</body>
</html>
