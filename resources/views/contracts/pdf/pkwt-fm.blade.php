<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PKWT FM</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 13mm 17mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10.5pt;
            line-height: 1.35;
            color: #000;
        }

        .judul {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 18px;
        }

        p {
            text-align: justify;
            margin: 7px 0;
        }

        .identitas {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }

        .identitas td {
            padding: 1px 0;
            vertical-align: top;
        }

        .label {
            width: 125px;
        }

        .titik-dua {
            width: 12px;
        }

        .pasal {
            text-align: center;
            font-weight: bold;
            margin: 13px 0 8px;
        }

        .ayat {
            margin: 5px 0;
            padding-left: 22px;
            text-indent: -22px;
            text-align: justify;
        }

        .paragraf {
            margin: 6px 0;
            text-align: justify;
        }

        .page-break {
            page-break-before: always;
        }

        .tanggal {
            text-align: right;
            margin-top: 20px;
        }

        .ttd {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            text-align: center;
        }

        .ttd td {
            width: 50%;
            vertical-align: top;
        }

        .ruang-ttd {
            height: 75px;
        }

        .nama-ttd {
            font-weight: bold;
            text-decoration: underline;
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

    $tanggalMulai = $formatTanggal($contract->tanggal_mulai);
    $tanggalBerakhir = $contract->tanggal_berakhir
        ? \Carbon\Carbon::parse($contract->tanggal_berakhir)->translatedFormat('d F Y')
        : '-';

    $tanggalTandaTangan = $contract->tanggal_mulai
        ? \Carbon\Carbon::parse($contract->tanggal_mulai)->translatedFormat('d F Y')
        : now()->translatedFormat('d F Y');

    $jenisKelamin = $employee->jenis_kelamin ?? '-';

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

    $namaKaryawan = $employee->name ?? '-';
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
        Dalam hal ini mewakili dan bertindak untuk dan atas nama PT. Starfood International
        yang beralamat di Jl. Raya Deandels Desa Kandangsemangkon Kec. Paciran Kab.
        Lamongan yang selanjutnya disebut sebagai PIHAK PERTAMA.
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
            <td>{{ $jenisKelamin }}</td>
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

    <p>
        Dalam hal ini bertindak untuk dan atas nama dirinya sendiri yang selanjutnya disebut
        sebagai PIHAK KEDUA.
    </p>

    <p>
        Sehubungan dengan keadaan dan kondisi bahan baku yang bersifat musiman di
        perusahaan yang sewaktu-waktu berubah, bisa meningkat ataupun menurun dan apabila
        suatu saat terjadi peningkatan produksi serta untuk memenuhi kebutuhan produksi
        yang sifatnya temporer sesuai dengan musim bahan baku, maka diperlukan tambahan
        tenaga kerja tidak tetap.
    </p>

    <p>
        Pada saat ini keadaan tersebut terjadi yaitu peningkatan pekerjaan karena musim
        bahan baku, yang sifatnya temporer maka untuk mengisi kekurangan tenaga kerja yang
        dibutuhkan, sehubungan dengan hal tersebut Pihak Kedua telah bersedia untuk
        mengadakan hubungan kerja dengan ketentuan dan syarat-syarat tersebut di bawah ini :
    </p>

    <div class="pasal">Pasal 1</div>

    <p class="paragraf">
        Bahwa dengan ini Pihak Kedua bersedia dipekerjakan sebagai pekerja tidak tetap di
        PT. Starfood International di bagian {{ $contract->bagian ?: 'Fish Meal' }}.
    </p>

    <div class="pasal">Pasal 2</div>

    <p class="paragraf">
        Bahwa Pihak Kedua sebagai pekerja bersedia untuk melaksanakan pekerjaan yang
        diberikan oleh Pihak Pertama dan sanggup untuk melaksanakan pekerjaan tersebut
        dengan penuh rasa tanggung jawab.
    </p>

    <div class="pasal">Pasal 3</div>

    <p class="paragraf">
        Bahwa Pihak Kedua sebagai pekerja bersedia untuk mematuhi dan mentaati tata tertib
        dan kedisiplinan kerja yang berlaku di PT. Starfood International
    </p>

    <div class="pasal">Pasal 4</div>

    <p class="ayat">
        1. Pihak Kedua diberikan upah yang dibayar berdasarkan hari kerjanya sesuai tarif per
        hari yang berlaku minimal sesuai dengan upah minimal Kabupaten Lamongan dan
        dibayarkan setiap 1 (satu) minggu sekali pada hari Sabtu setelah tugas dan pekerjaan
        dilaksanakan.
    </p>

    <p class="ayat">
        2. Apabila Pihak Pertama meminta Pihak Kedua untuk kerja lembur, maka perhitungan
        upah lembur diberikan sesuai peraturan yang berlaku.
    </p>

    <div class="pasal">Pasal 5</div>

    <p class="ayat">
        1. Perjanjian kerja ini berlaku terhitung sejak ditanda tangani oleh Pihak Pertama dan
        Pihak Kedua dan berakhir atau habis masa berlakunya pada tanggal
        {{ $tanggalBerakhir }}.
    </p>

    <p class="ayat">
        2. Perjanjian kerja ini dapat diperpanjang kembali dengan mempertimbangkan
        kebutuhan pekerjaan yang ada pada Pihak Pertama.
    </p>

    <div class="page-break"></div>

    <div class="pasal">Pasal 6</div>

    <p>Surat perjanjian ini dapat dibatalkan dan tidak berlaku apabila :</p>

    <p class="ayat">
        1. Pihak Kedua mengundurkan diri atas kemauan sendiri.
    </p>
    <p class="ayat">
        2. Pihak Kedua tidak masuk kerja selama 5 (lima) hari secara berturut-turut tanpa
        keterangan yang sah.
    </p>
    <p class="ayat">
        3. Pihak Kedua meninggal dunia.
    </p>
    <p class="ayat">
        4. Pihak Pertama atau Pihak Kedua ingkar dan tidak mampu memenuhi isi perjanjian ini.
    </p>
    <p class="ayat">
        5. Volume bahan baku menurun dan atau tidak musim.
    </p>
    <p class="ayat">
        6. Adanya keadaaan atau kejadian tertentu sesuai yang dicantumkan dalam peraturan
        perusahaan dapat menyebabkan berakhirnya hubungan kerja.
    </p>

    <div class="pasal">Pasal 7</div>

    <p>
        Apabila sampai batas waktu masa berlakunya perjanjian ini berakhir sebagaimana
        ketentuan dalam pasal 5 dan pihak pertama telah mengakhiri perjanjian kerja ini maka :
    </p>

    <p class="ayat">
        1. Hubungan kerja antara Pihak Pertama dengan Pihak Kedua berakhir dengan
        sendirinya atau batal demi hukum.
    </p>
    <p class="ayat">
        2. Bahwa Pihak Pertama tidak berkewajiban untuk memberikan uang pesangon, uang
        jasa atau ganti rugi berupa apapun kepada pihak kedua.
    </p>
    <p class="ayat">
        3. Bahwa Pihak Kedua tidak berhak menuntut apapun kepada Pihak Pertama
        sebagaimana dimaksud dalam ketentuan ayat 2 pasal ini termasuk didalamnya adalah
        tuntutan perubahan status ketenaga kerjaan.
    </p>

    <div class="pasal">Pasal 8</div>

    <p class="ayat">
        1. Surat perjanjian kerja ini sudah dibaca dan dipahami oleh pihak kedua sesuai dengan
        isinya tanpa ada paksaan dari pihak manapun.
    </p>
    <p class="ayat">
        2. Surat perjanjian kerja ini bermaterei cukup dan mempunyai kekuatan hukum.
    </p>

    <p class="tanggal">Lamongan, {{ $tanggalTandaTangan }}</p>

    <table class="ttd">
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
