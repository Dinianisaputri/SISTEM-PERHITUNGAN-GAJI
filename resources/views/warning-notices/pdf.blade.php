<!DOCTYPE html>
@php
    $bulanIndonesia = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    $formatTanggalIndonesia = function ($tanggal) use ($bulanIndonesia) {
        if (!$tanggal) {
            return '-';
        }

        $tanggal = \Carbon\Carbon::parse($tanggal);

        return $tanggal->format('d') . ' '
            . $bulanIndonesia[(int) $tanggal->format('m')] . ' '
            . $tanggal->format('Y');
    };
@endphp
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Teguran</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 20mm 10mm 20mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11pt;
            line-height: 1.15;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .letterhead {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 23%;
            vertical-align: middle;
            text-align: left;
            padding-right: 8px;
        }

        .logo {
            width: 225px;
            max-height: 150px;
        }

        .company-info {
            width: 77%;
            text-align: right;
            vertical-align: middle;
            font-size: 8pt;
            line-height: 1.15;
        }

        .company-name {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .header-line {
            border: 0;
            border-top: 1px solid #000;
            margin: 7px 0 11px;
        }

        .title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0 0 3px;
        }

        .number {
            text-align: center;
            font-size: 10.5pt;
            margin-bottom: 15px;
        }

        p {
            margin: 7px 0;
            text-align: justify;
        }

        .employee-data {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0 9px 10px;
        }

        .employee-data td {
            padding: 1px 0;
            vertical-align: top;
        }

        .employee-data .label {
            width: 85px;
        }

        .employee-data .colon {
            width: 15px;
        }

        .violation {
            margin: 5px 0 9px 10px;
            text-align: justify;
            white-space: pre-line;
            overflow-wrap: break-word;
        }

        .rules {
            margin: 5px 0 9px;
            padding-left: 23px;
            text-align: justify;
        }

        .rules li {
            padding-left: 2px;
            margin-bottom: 4px;
            text-align: justify;
        }

        .closing {
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .tanda-tangan-wrapper {
            width: 100%;
            margin-top: 9px;
            page-break-inside: avoid;
        }

        .date {
            text-align: right;
            margin: 0 0 12px;
            page-break-after: avoid;
        }

        .signatures {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin: 0;
            page-break-inside: avoid;
        }

        .signatures tr {
            page-break-inside: avoid;
        }

        .signatures td {
            width: 33.333%;
            padding: 0 3px;
            text-align: center;
            vertical-align: top;
            font-size: 10.5pt;
        }

        .signature-space td {
            height: 48px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            white-space: normal;
            overflow-wrap: break-word;
        }
    </style>
</head>

<body>
    <table class="letterhead">
        <tr>
            <td class="logo-cell">
                <img
                    src="{{ public_path('images/starfood-logo.png') }}"
                    class="logo"
                    alt="Logo Starfood International"
                >
            </td>

            <td class="company-info">
                <div class="company-name">PT. Starfood International</div>

                <strong>Office :</strong> Jl. KIG Raya Selatan Kav. C-7<br>
                (Kawasan Industri Gresik)<br>
                Gresik 61121, Jatim – Indonesia<br>
                Telp. : +62 31 398-6727; Fax : +62 31 397-6348<br>

                <strong>Factory :</strong> Jl. Raya Deandles Km 76, Ds. Kandang Semangkon<br>
                Kec. Paciran, Lamongan – Indonesia<br>
                Telp. : +62 322 666 463 – 64; Fax : +62 322 666 466<br>
                E-mail : food@starfoodcorp.com
            </td>
        </tr>
    </table>

    <hr class="header-line">

    <h3 class="title">SURAT TEGURAN</h3>

    <div class="number">
        NO : {{ $warningNotice->nomor_surat }}
    </div>

    <p>Telah terjadi pelanggaran yang dilakukan oleh :</p>

    <table class="employee-data">
        <tr>
            <td class="label">Nama</td>
            <td class="colon">:</td>
            <td>{{ $warningNotice->employee->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="colon">:</td>
            <td>{{ $warningNotice->employee->jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Unit kerja</td>
            <td class="colon">:</td>
            <td>{{ $warningNotice->employee->bagian ?? '-' }}</td>
        </tr>
    </table>

    <p>Adapun pelanggaran yang dilakukan karyawan tersebut :</p>

    <div class="violation">{{ $warningNotice->alasan_pelanggaran }}</div>

    <p>
        Manajemen memberikan Surat Teguran kepada saudara dengan ketentuan berikut:
    </p>

    <ol class="rules">
        <li>
            Surat teguran ini berlaku sejak tanggal diterbitkan sampai dengan
            6 bulan ke depan
            {{ $formatTanggalIndonesia($warningNotice->tanggal_mulai_berlaku) }}
            s.d.
            {{ $formatTanggalIndonesia($warningNotice->tanggal_akhir_berlaku) }}
        </li>
        <li>
            Jika dalam waktu yang sudah ditentukan, saudara tidak melakukan
            tindakan/kesalahan yang menjadi dasar diterbitkannya Surat Teguran
            ini, maka surat teguran ini dinyatakan tidak berlaku lagi.
        </li>
        <li>
            Jika dalam waktu yang sudah ditentukan di atas, saudara kembali
            melakukan tindakan/kesalahan yang sama atau lainnya, maka akan
            diterbitkan Surat Peringatan I (SP I).
        </li>
        <li>
            Jika setelah Surat Peringatan I (SP I), saudara tetap melakukan
            tindakan/kesalahan yang sama atau lainnya, maka akan ditingkatkan
            menjadi Surat Peringatan II (SP II), Surat Peringatan III (SP III),
            skorsing hingga pemutusan hubungan kerja.
        </li>
    </ol>

    <p class="closing">
        Demikian surat teguran ini dibuat sebagai bentuk pembinaan untuk
        kebaikan bersama serta memperbaiki kinerja saudara. Surat teguran ini
        akan ditinjau kembali di kemudian hari jika ditemukan kekeliruan dalam
        penerbitannya.
    </p>

    <div class="tanda-tangan-wrapper">
        <div class="date">
            Lamongan, {{ $formatTanggalIndonesia($warningNotice->tanggal_surat) }}
        </div>

        <table class="signatures">
            <tr>
                <td>Diberikan Kepada</td>
                <td>Mengetahui</td>
                <td>PT Starfood international</td>
            </tr>

            <tr class="signature-space">
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td>
                    <div class="signature-name">
                        {{ $warningNotice->employee->name ?? '' }}
                    </div>
                </td>
                <td>
                    <div class="signature-name">
                        {{ $warningNotice->nama_kepala_unit }}
                    </div>
                </td>
                <td>
                    <div class="signature-name">Yudi Porwanto</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
