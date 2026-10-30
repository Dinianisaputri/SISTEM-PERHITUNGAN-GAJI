<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Peringatan</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 18mm 12mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11pt;
            line-height: 1.2;
            color: #111;
            margin: 0;
        }

            .kop {
                font-size: 8pt;
                line-height: 1.15;
                border-bottom: 2px solid #222;
                padding-bottom: 8px;
            }

            .kop-tabel {
                width: 100%;
                border-collapse: collapse;
            }

            .kop-tabel td {
                vertical-align: middle;
            }

            .kop-logo {
                width: 25%;
                text-align: left;
                padding-right: 12px;
            }

            .logo-starfood {
                width: 225px;
                height: auto;
            }

            .kop-informasi {
                width: 75%;
                text-align: right;
            }

            .nama-perusahaan {
                font-size: 14pt;
                font-weight: bold;
            }

        .nama-perusahaan {
            font-size: 14pt;
            font-weight: bold;
        }

        .judul {
            text-align: center;
            margin-top: 14px;
            margin-bottom: 18px;
        }

        .judul h2 {
            font-size: 13pt;
            text-decoration: underline;
            margin-bottom: 2px;
        }

        .isi {
            text-align: justify;
        }

        .identitas {
            margin: 8px 0 12px 24px;
        }

        .identitas td {
            padding: 1px 5px 1px 0;
            vertical-align: top;
        }

        .tanda-tangan {
            width: 100%;
            margin-top:22px;
            text-align: center;
        }

        .ruang-tanda-tangan {
            height: 55px;
        }
    </style>
</head>
<body>
    
<div class="kop">
    <table class="kop-tabel">
        <tr>
            <td class="kop-logo">
                <img src="{{ public_path('images/starfood-logo.png') }}"
                     alt="Logo Starfood"
                     class="logo-starfood">
            </td>

            <td class="kop-informasi">
                <div class="nama-perusahaan">PT. Starfood International</div>
                Office: Jl. KIG Raya Selatan Kav. C-7<br>
                (Kawasan Industri Gresik), Gresik 61121, Jatim – Indonesia<br>
                Telp.: +62 31 398-6727; Fax: +62 31 397-6348<br>
                Factory: Jl. Raya Deandles Km 76, Ds. Kandang Semangkon<br>
                Kec. Paciran, Lamongan – Indonesia<br>
                Telp.: +62 322 666 463–64; Fax: +62 322 666 466<br>
                E-mail: food@starfoodcorp.com
            </td>
        </tr>
    </table>
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
        Surat ini berlaku sejak
        {{ $warningLetter->tanggal_mulai_berlaku->format('d F Y') }}
        @if ($warningLetter->tanggal_akhir_berlaku)
            sampai dengan
            {{ $warningLetter->tanggal_akhir_berlaku->format('d F Y') }}.
        @else
            sampai batas waktu yang ditentukan perusahaan.
        @endif
    </li>

    <li>
        Jika dalam waktu yang sudah ditentukan, Saudara tidak melakukan
        tindakan atau kesalahan yang menjadi dasar diterbitkannya surat
        peringatan ini, maka surat peringatan ini dinyatakan tidak berlaku lagi.
    </li>

    <li>
        @php
            $tingkatSP = strtoupper(trim((string) $warningLetter->tingkat_sp));

            $tingkatSP = match ($tingkatSP) {
                '1', 'SP 1', 'SP I' => 1,
                '2', 'SP 2', 'SP II' => 2,
                '3', 'SP 3', 'SP III' => 3,
                default => 0,
            };
        @endphp

        @if ($tingkatSP === 1)
            Jika dalam masa berlakunya Surat Peringatan I (SP I),
            Saudara tetap melakukan tindakan atau kesalahan yang sama
            maupun lainnya, maka perusahaan dapat meningkatkan sanksi
            menjadi Surat Peringatan II (SP II), dilanjutkan dengan
            Surat Peringatan III (SP III), skorsing, hingga pemutusan
            hubungan kerja sesuai ketentuan perusahaan.

        @elseif ($tingkatSP === 2)
            Jika dalam masa berlakunya Surat Peringatan II (SP II),
            Saudara tetap melakukan tindakan atau kesalahan yang sama
            maupun lainnya, maka perusahaan dapat meningkatkan sanksi
            menjadi Surat Peringatan III (SP III), skorsing, hingga
            pemutusan hubungan kerja sesuai ketentuan perusahaan.

        @elseif ($tingkatSP === 3)
            Jika dalam masa berlakunya Surat Peringatan III (SP III),
            Saudara tetap melakukan tindakan atau kesalahan yang sama
            maupun lainnya, maka perusahaan dapat mengambil tindakan
            lanjutan, termasuk skorsing atau pemutusan hubungan kerja,
            sesuai ketentuan perusahaan.

        @else
            Redaksi tindak lanjut belum ditentukan untuk tingkat
            Surat Peringatan ini.
        @endif
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
