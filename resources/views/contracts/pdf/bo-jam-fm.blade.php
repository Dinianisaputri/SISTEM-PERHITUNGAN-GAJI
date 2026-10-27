@php
    $contract->load([
        'employee.provinsi',
        'employee.kabupaten',
        'employee.kecamatan',
        'employee.desa',
    ]);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PKWT BO Jam FF</title>
<style>
    @page {
        size: A4 portrait;
        margin: 13mm 17mm 13mm 17mm;
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 11pt;
        color: #000;
        line-height: 1.4;
    }

    h1 {
        font-family: Arial, sans-serif;
        font-size: 12pt;
        font-weight: bold;
        text-align: center;
        text-decoration: underline;
        margin: 0 0 12px;
    }

    p {
        font-size: 11pt;
        line-height: 1.4;
        margin: 8px 0;
        text-align: justify;
    }

    .identity {
        width: 100%;
        border-collapse: collapse;
        margin: 8px 0;
        font-size: 11pt;
    }

    .identity td {
        padding: 2px 0;
        vertical-align: top;
        font-size: 11pt;
        line-height: 1.4;
    }

    .label {
        width: 125px;
    }

    .colon {
        width: 12px;
    }

    .pasal {
        font-size: 11pt;
        font-weight: bold;
        text-align: center;
        margin: 10px 0 8px;
        page-break-after: avoid;
    }

    .signatures {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin-top: 0;
    }

    .signatures td {
        width: 50%;
        text-align: center;
        vertical-align: top;
        padding: 0 3px;
        font-size: 9pt;
        line-height: 1.4;
    }

    .signature-space {
        height: 68px;
    }

    .sign-name {
        font-size: 9pt;
        font-weight: bold;
        text-decoration: underline;
        white-space: nowrap;
    }
</style>
</head>
<body>

<h1>SURAT PERJANJIAN WAKTU TERTENTU</h1>

<p>Yang bertanda tangan di bawah ini masing-masing:</p>

<table class="identity">
    <tr>
        <td class="label">Nama</td>
        <td class="colon">:</td>
        <td>YUDI PORWANTO</td>
    </tr>
    <tr>
        <td class="label">Jabatan</td>
        <td class="colon">:</td>
        <td>GA-HRD</td>
    </tr>
</table>

<p>
    Dalam hal ini mewakili dan bertindak untuk dan atas nama
    PT. Starfood International yang beralamat di Jl. Raya Deandels
    Desa Kandangsemangkon Kec. Paciran Kab. Lamongan yang selanjutnya
    disebut sebagai PIHAK PERTAMA.
</p>

<table class="identity">
    <tr>
        <td class="label">Nama</td>
        <td class="colon">:</td>
        <td>{{ $contract->employee->name ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Jenis Kelamin</td>
        <td class="colon">:</td>
        <td>{{ $contract->employee->jenis_kelamin ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Tempat Lahir</td>
        <td class="colon">:</td>
        <td>{{ $contract->employee->tempat_lahir ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Tanggal Lahir</td>
        <td class="colon">:</td>
        <td>
            {{ $contract->employee->tanggal_lahir
                ? \Carbon\Carbon::parse($contract->employee->tanggal_lahir)->format('d/m/Y')
                : '-' }}
        </td>
    </tr>
    <tr>
        
<td class="label">Alamat</td>
<td class="colon">:</td>
<td>
    
{{
    collect([
        $contract->employee->detail_alamat,
        $contract->employee->desa?->nama,
        $contract->employee->kecamatan?->nama,
        $contract->employee->kabupaten?->nama,
        $contract->employee->provinsi?->nama,
        $contract->employee->rt ? 'RT ' . $contract->employee->rt : null,
        $contract->employee->rw ? 'RW ' . $contract->employee->rw : null,
    ])->filter()->implode(', ') ?: '-'
}}
</td>
    </tr>
</table>

<p>
    Dalam hal ini bertindak untuk dan atas nama dirinya sendiri
    yang selanjutnya disebut sebagai PIHAK KEDUA.
</p>

<p>
    Sehubungan dengan keadaan dan kondisi bahan baku yang bersifat
    musiman di perusahaan yang sewaktu-waktu berubah, bisa meningkat
    ataupun menurun dan apabila suatu saat terjadi peningkatan produksi
    serta untuk memenuhi kebutuhan produksi yang sifatnya temporer
    sesuai dengan musim bahan baku, maka diperlukan tambahan tenaga
    kerja tidak tetap.
</p>

<p>
    Pada saat ini keadaan tersebut terjadi yaitu peningkatan pekerjaan
    karena musim bahan baku, yang sifatnya temporer maka untuk mengisi
    kekurangan tenaga kerja yang dibutuhkan, sehubungan dengan hal
    tersebut Pihak Kedua telah bersedia untuk mengadakan hubungan kerja
    tidak tetap dengan ketentuan dan syarat-syarat tersebut di bawah ini:
</p>

<div class="pasal">Pasal 1</div>

<p>
    1. Pihak Pertama karena jabatannya menerima Pihak Kedua untuk
    melaksanakan jenis tugas/pekerjaan yang bersifat musiman sesuai
    kapasitas bahan baku.
</p>

<p>
    2. Pihak Kedua bersedia melaksanakan pekerjaan sebagaimana yang
    dimaksud pada ayat 1 dan ditempatkan pada unit
    {{ $contract->bagian ?: 'Frozen Fish' }}.
</p>

<div class="pasal">Pasal 2</div>

<p>
    1. Pihak Pertama memberikan upah kerja kepada Pihak Kedua dengan
    didasarkan atas hasil pekerjaan yang dilakukan oleh Pihak Kedua
    dengan tarif upah borongan jam kerja.
</p>

<p>
    2. Pihak Pertama akan membayar upah borongan kepada Pihak Kedua
    setiap hari Selasa setelah pekerjaan selesai dilakukan.
</p>

<p>
    3. Upah borongan yang diterima Pihak Kedua adalah sebesar lamanya
    jam kerja dikalikan Rp18.000 dengan tidak menghitung waktu istirahat.
</p>

<p>
    4. Karena sifat pekerjaan yang tidak dapat ditunda maka jika terdapat
    tanggal merah nasional tetap masuk kerja normal dengan sistem
    penggantian hari libur.
</p>

<div class="pasal">Pasal 3</div>

<p>
    Pihak Kedua bersedia mematuhi dan melaksanakan kewajiban-kewajiban
    dan tidak melanggar peraturan perusahaan yang diberlakukan di
    lingkungan PT. Starfood International.
</p>

<div class="pasal">Pasal 4</div>

<p>
    1. Perjanjian kerja ini berlaku terhitung sejak ditandatangani
    oleh Pihak Pertama dan Pihak Kedua dan berakhir atau habis masa
    berlakunya pada tanggal
    {{ $contract->tanggal_berakhir
        ? \Carbon\Carbon::parse($contract->tanggal_berakhir)->format('d/m/Y')
        : '................................' }}.
</p>

<p>
    2. Perjanjian kerja ini dapat diperpanjang kembali dengan
    mempertimbangkan kebutuhan pekerjaan yang ada pada Pihak Pertama.
</p>

<p>
    3. Jika dalam sisa kurun waktu perjanjian kerja terdapat kondisi
    dimana bahan baku habis atau tidak musim maka hubungan kerja ini
    dapat berakhir dengan sendirinya.
</p>

<div class="pasal">Pasal 5</div>

<p>Surat perjanjian ini dapat dibatalkan dan tidak berlaku apabila:</p>

<p>1. Pihak Kedua mengundurkan diri atas kemauan sendiri.</p>
<p>2. Pihak Kedua tidak masuk kerja selama 5 (lima) hari secara berturut-turut tanpa keterangan yang sah.</p>
<p>3. Pihak Kedua meninggal dunia.</p>
<p>4. Pihak Pertama atau Pihak Kedua ingkar dan tidak mampu memenuhi isi perjanjian ini.</p>
<p>5. Volume bahan baku menurun dan atau tidak musim.</p>
<p>6. Adanya keadaan atau kejadian tertentu sesuai yang dicantumkan dalam peraturan perusahaan dapat menyebabkan berakhirnya hubungan kerja.</p>

<div class="pasal">Pasal 6</div>

<p>
    Apabila sampai batas waktu masa berlakunya perjanjian kerja ini
    berakhir sebagaimana ketentuan dalam Pasal 4 dan Pihak Pertama
    telah mengakhiri perjanjian kerja ini maka:
</p>

<p>
    1. Hubungan kerja antara Pihak Pertama dan Pihak Kedua berakhir
    dengan sendirinya atau batal demi hukum.
</p>

<p>
    2. Bahwa Pihak Pertama tidak berkewajiban untuk memberikan uang
    pesangon, uang jasa atau ganti rugi berupa apapun kepada Pihak Kedua.
</p>

<p>
    3. Bahwa Pihak Kedua tidak berhak menuntut apapun kepada Pihak
    Pertama sebagaimana dimaksud dalam ketentuan ayat 2 pasal ini
    termasuk di dalamnya adalah tuntutan perubahan status ketenagakerjaan.
</p>

<div class="pasal">Pasal 7</div>

<p>
    1. Surat perjanjian kerja ini sudah dibaca dan dipahami oleh
    Pihak Kedua sesuai dengan isinya tanpa ada paksaan dari pihak manapun.
</p>

<p>
    2. Surat perjanjian kerja ini bermaterai cukup dan mempunyai
    kekuatan hukum.
</p>

<p style="margin-top: 22px;">
    Dibuat di: Lamongan<br>
    Pada tanggal:
    {{ \Carbon\Carbon::parse($contract->tanggal_mulai)->translatedFormat('l, d F Y') }}
</p>

<table class="signatures">
    <tr>
        <td>Pihak Pertama,</td>
        <td>Pihak Kedua,</td>
    </tr>
    <tr>
        <td class="signature-space"></td>
        <td class="signature-space"></td>
    </tr>
    <tr>
        <td class="sign-name">YUDI PORWANTO</td>
        <td class="sign-name">{{ $contract->employee->name ?? '-' }}</td>
    </tr>
</table>

</body>
</html>
