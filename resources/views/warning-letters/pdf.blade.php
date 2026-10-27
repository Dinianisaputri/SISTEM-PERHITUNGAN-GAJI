<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Peringatan</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 18mm 20mm 20mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.35;
            color: #111;
        }

        .kop {
            text-align: right;
            font-size: 9pt;
            line-height: 1.25;
            border-bottom: 2px solid #222;
            padding-bottom: 10px;
        }

        .nama-perusahaan {
            font-size: 16pt;
            font-weight: bold;
        }

        .judul {
            text-align: center;
            margin-top: 22px;
            margin-bottom: 30px;
        }

        .judul h2 {
            font-size: 14pt;
            text-decoration: underline;
            margin-bottom: 2px;
        }

        .isi {
            text-align: justify;
        }

        .identitas {
            margin: 12px 0 18px 24px;
        }

        .identitas td {
            padding: 1px 5px 1px 0;
            vertical-align: top;
        }

        .tanda-tangan {
            width: 100%;
            margin-top: 45px;
            text-align: center;
        }

        .ruang-tanda-tangan {
            height: 65px;
        }
    </style>
</head>
<body>
    <div class="kop">
        <div class="nama-perusahaan">PT. Starfood International</div>
        Office: Jl. KIG Raya Selatan Kav. C-7<br>
        (Kawasan Industri Gresik), Gresik 61121, Jatim – Indonesia<br>
        Telp.: +62 31 398-6727; Fax: +62 31 397-6348<br>
        Factory: Jl. Raya Deandles Km 76, Ds. Kandang Semangkon<br>
        Kec. Paciran, Lamongan – Indonesia<br>
        Telp.: +62 322 666 463–64; Fax: +62 322 666 466<br>
        E-mail: food@starfoodcorp.com
    </div>

    <div class="judul">
        <h2>SURAT PERINGATAN</h2>
        NO: {{ $warningLetter->nomor_surat }}
    </div>

    <div class="isi">
        <p>Telah terjadi pelanggaran yang dilakukan oleh:</p>

        <table class="identitas">
            <tr>
                <td width="110">Nama</td>
                <td>:</td>
                <td>{{ $warningLetter->employee->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $warningLetter->employee->jabatan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Unit kerja</td>
                <td>:</td>
                <td>{{ $warningLetter->employee->bagian ?? '-' }}</td>
            </tr>
        </table>

        <p>Adapun pelanggaran yang dilakukan karyawan tersebut:</p>

        <p style="margin-left: 24px;">
            &bull; {{ $warningLetter->alasan_pelanggaran }}
        </p>

        <p>Manajemen memberikan surat kepada saudara dengan ketentuan berikut:</p>

        <ol>
            <li>
                1.Surat ini berlaku sejak
                {{ $warningLetter->tanggal_mulai_berlaku->format('d F Y') }}
                @if ($warningLetter->tanggal_akhir_berlaku)
                    sampai dengan
                    {{ $warningLetter->tanggal_akhir_berlaku->format('d F Y') }}.
                @else
                    sampai batas waktu yang ditentukan perusahaan.
                @endif
            </li>
            <li>
                2.Jika dalam waktu yang sudah ditentukan, saudara tidak melakukan tindakan/kesalahan yang menjadi dasar diterbitkannya Surat Teguran ini, maka surat teguran ini dinyatakan tidak berlaku lagi.
            </li>
        </ol>

        <p style="margin-top: 28px;">
            Demikian surat ini dibuat untuk menjadi perhatian dan pembinaan
            bagi karyawan yang bersangkutan.
        </p>
    </div>

    <p style="text-align: right; margin-top: 28px;">
        Lamongan,
        {{ $warningLetter->tanggal_surat->format('d F Y') }}<br>
        PT Starfood International
    </p>

    <table class="tanda-tangan">
        <tr>
            <td width="33%">Diberikan Kepada</td>
            <td width="33%">Mengetahui</td>
            <td width="34%">PT Starfood International</td>
        </tr>
        <tr><td class="ruang-tanda-tangan"></td><td></td><td></td></tr>
        <tr>
            <td>{{ $warningLetter->employee->name ?? '-' }}</td>
            <td>________________</td>
            <td>Yudi Porwanto</td>
        </tr>
    </table>
</body>
</html>
