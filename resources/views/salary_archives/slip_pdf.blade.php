<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <style>

        @page {

            size: A4 portrait;

            margin: 4mm;

        }

        * {

            box-sizing: border-box;

        }

        html, body {

            margin: 0;

            padding: 0;

            font-family: DejaVu Sans, sans-serif;

            font-size: 9px;

            color: #17202a;

        }

        table.sheet {

            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;

        }

        table.sheet tr {

            page-break-inside: avoid;

            height: auto;

        }

        td.slip-cell {

            width: 50%;

            height: auto;

            padding: 0.8mm;

            vertical-align: top;

        }

.slip {
    border: 1px solid #253746;
    padding: 4px;
    height: auto;
    page-break-inside: avoid;
    overflow: visible;
}

        .empty-slip {

            visibility: hidden;

        }

        .company {

            font-size: 10px;

            font-weight: bold;

            text-align: center;

            margin-bottom: 1px;

        }

        .subtitle {

            font-size: 9px;

            text-align: center;

            margin-bottom: 4px;

        }

        .period {

            text-align: center;

            font-weight: bold;

            border-top: 1px solid #687785;

            border-bottom: 1px solid #687785;

            padding: 4px 0;

            margin-bottom: 4px;

        }

        table.info,

        table.money,

        table.deduction-table {

            width: 100%;

            border-collapse: collapse;

        }

        table.employee-info {

            table-layout: fixed;

            margin-bottom: 5px;

        }

        .employee-info td {

            padding: 2px 1px;

            vertical-align: top;

        }

        .employee-info td.label {

            width: 15%;

            white-space: nowrap;

        }

        .employee-info td.value {

            width: 35%;

            overflow-wrap: break-word;

        }

        .employee-info td:nth-child(3) {

            padding-left: 5px;

        }

        .section-title {

            font-weight: bold;

            font-size: 9px;

            border-bottom: 1px solid #8795a1;

            padding: 2px 0 2px;

            margin-top: 2px;

            margin-bottom: 1px;

        }

        table.money td {

            padding: 2px 3px;

            vertical-align: top;

            border-bottom: 0.3px solid #e0e5e9;

        }

        table.money td:first-child {

            width: 62%;

        }

        table.money td:last-child {

            text-align: right;

            white-space: nowrap;

        }

        .salary-table {

            margin-top: 2px;

            border-top: 1px solid #8795a1;

        }

        .salary-table .subtotal td {

            font-weight: bold;

            background: #f1f4f6;

            border-top: 1px solid #b5c0c8;

        }

        .salary-table .net td {

            font-size: 10px;

            font-weight: bold;

            padding: 5px 4px;

            background: #e8edf3;

            border-top: 1px solid #253746;

            border-bottom: 1px solid #253746;

        }

        .deduction-box {

            margin-top: 5px;

            border: 0.6px solid #aab4bd;

            page-break-inside: avoid;

        }

        .deduction-heading {

            padding: 4px;

            font-size: 9px;

            font-weight: bold;

            background: #e8edf3;

            border-bottom: 0.6px solid #aab4bd;

        }

        table.deduction-table {

            table-layout: fixed;

            font-size: 9px;

        }

        .deduction-table th {

            text-align: left;

            padding: 4px 3px;

            background: #f5f7f9;

            border-bottom: 0.5px solid #c4cdd4;

        }

        .deduction-table td {

            padding: 4px 3px;

            vertical-align: top;

            border-bottom: 0.3px solid #e0e5e9;

            overflow-wrap: break-word;

        }

        .deduction-table th:nth-child(1),

        .deduction-table td:nth-child(1) {

            width: 30%;

        }

        .deduction-table th:nth-child(2),

        .deduction-table td:nth-child(2) {

            width: 40%;

        }

        .deduction-table th:nth-child(3),

        .deduction-table td:nth-child(3) {

            width: 30%;

            text-align: right;

        }

        .deduction-empty {

            padding: 4px;

            font-size: 8px;

        }

        .reason {

            margin-top: 4px;

            font-size: 8px;

            line-height: 1.3;

        }

        .footer {

            margin-top: 5px;

            font-size: 7px;

            text-align: center;

            color: #596773;

        }

    </style>

</head>

<body>

@php

    $rupiah = fn ($value) => 'Rp ' . number_format(

        (float) ($value ?? 0),

        0,

        ',',

        '.'

    );

    $jam = fn ($value) => number_format(

        (float) ($value ?? 0),

        2,

        ',',

        '.'

    );

    $periode = ($salaryArchive->start_date

        ? $salaryArchive->start_date->format('d/m/Y')

        : '-') . ' - ' . ($salaryArchive->end_date

        ? $salaryArchive->end_date->format('d/m/Y')

        : '-');

@endphp

@foreach ($details->chunk(6) as $pageDetails)

    <table class="sheet"

        @if (!$loop->last)

            style="page-break-after: always;"

        @endif

    >

        @foreach ($pageDetails->chunk(2) as $row)

            <tr>

                @foreach ($row as $detail)

@php
    $rincianPotongan = $detail->rincian_potongan ?? [];

    if (is_string($rincianPotongan)) {
        $rincianPotongan = json_decode($rincianPotongan, true) ?? [];
    }

    if (!is_array($rincianPotongan)) {
        $rincianPotongan = [];
    }

    $adaPotonganBpjs = collect($rincianPotongan)->contains(
        fn ($potongan) => str_contains(
            strtolower((string) ($potongan['nama_potongan'] ?? '')),
            'bpjs'
        ) || str_contains(
            strtolower((string) ($potongan['keterangan'] ?? '')),
            'bpjs'
        )
    );

    if (!$adaPotonganBpjs && (float) ($detail->total_potongan ?? 0) > 0) {
        $employee = $detail->employee;
        $tarifBpjs = (float) ($employee?->tarif_bpjs ?? 0);
        $terdaftarBpjs = (bool) ($employee?->bpjs_tk_terdaftar ?? false)
            || (bool) ($employee?->bpjs_kesehatan_terdaftar ?? false);

        $adaPotonganBpjs = $terdaftarBpjs
            && $tarifBpjs > 0
            && round((float) $detail->total_potongan, 2) === round($tarifBpjs, 2);
    }
@endphp

                    <td class="slip-cell">

                        <div class="slip">

                            <div class="company">

                                PT STARFOOD INTERNATIONAL

                            </div>

                            <div class="subtitle">

                                SLIP GAJI KARYAWAN

                            </div>

                            <div class="period">

                                PERIODE {{ $periode }}

                            </div>

                            <table class="info employee-info">

                                <tr>

                                    <td class="label">Nama</td>

                                    <td class="value">

                                        <strong>{{ $detail->employee_name }}</strong>

                                    </td>

                                    <td class="label">ID / PIN</td>

                                    <td class="value">

                                        {{ $detail->fingerprint_pin ?? '-' }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="label">Bagian</td>

                                    <td class="value">

                                        {{ $detail->bagian ?? '-' }}

                                    </td>

                                    <td class="label">Jenis</td>

                                    <td class="value">

                                        {{ $detail->jenis_karyawan ?? '-' }}

                                    </td>

                                </tr>

                            </table>

                            <div class="section-title">RINCIAN KERJA</div>

                            <table class="money">

                                <tr>

                                    <td>Hari hadir</td>

                                    <td>{{ $jam($detail->hari_hadir) }} hari</td>

                                </tr>

                                <tr>

                                    <td>Total jam kerja</td>

                                    <td>{{ $jam($detail->jam_kerja) }} jam</td>

                                </tr>

                                <tr>

                                    <td>Lembur biasa</td>

                                    <td>{{ $jam($detail->jam_lembur_biasa) }} jam</td>

                                </tr>

                                <tr>

                                    <td>Lembur hari libur</td>

                                    <td>{{ $jam($detail->jam_lembur_libur) }} jam</td>

                                </tr>

                            </table>

                            <div class="section-title">RINCIAN GAJI</div>

                            <table class="money salary-table">

                                <tr>

                                    <td>Gaji pokok</td>

                                    <td>{{ $rupiah($detail->gaji_pokok) }}</td>

                                </tr>

                                <tr>

                                    <td>Total lembur</td>

                                    <td>{{ $rupiah($detail->total_lembur) }}</td>

                                </tr>

                                <tr>

                                    <td>Uang makan lembur</td>

                                    <td>{{ $rupiah($detail->uang_makan_lembur) }}</td>

                                </tr>

                                <tr class="subtotal">
    <td>Gaji kotor</td>
    <td>{{ $rupiah($detail->gaji_kotor) }}</td>
</tr>

                                @if ((float) ($detail->total_tambahan ?? 0) > 0)
                                    @php
                                        $rincianNominalTambahan = $detail->rincian_nominal_tambahan ?? [];
                                    @endphp
                                    @foreach ($rincianNominalTambahan as $tambahan)
                                        <tr>
                                            <td>{{ $tambahan['nama_tambahan'] ?? 'Nominal tambahan' }}</td>
                                            <td>+{{ $rupiah($tambahan['nominal'] ?? 0) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>Total nominal tambahan</td>
                                        <td>+{{ $rupiah($detail->total_tambahan) }}</td>
                                    </tr>
                                @endif


                                <tr>
    <td>
        Total potongan
        @if ($adaPotonganBpjs)
            <small style="color: #596773;">(BPJS)</small>
        @endif
    </td>
    <td>-{{ $rupiah($detail->total_potongan) }}</td>
</tr>

                                @if ((float) $detail->potongan_tambahan > 0)
                                    @php

        $rincianTambahan = $detail->rincian_potongan_tambahan ?? [];

        if (is_string($rincianTambahan)) {

            $rincianTambahan = json_decode($rincianTambahan, true) ?? [];

        }

        if (!is_array($rincianTambahan)) {

            $rincianTambahan = [];

        }

    @endphp

    @foreach ($rincianTambahan as $potongan)

        <tr>

            <td>{{ $potongan['nama_potongan'] ?? 'Potongan' }}</td>

            <td>-{{ $rupiah($potongan['nominal'] ?? 0) }}</td>

        </tr>

    @endforeach

                                @endif

                                <tr class="net">

                                    <td>GAJI BERSIH</td>

                                    <td>{{ $rupiah($detail->gaji_bersih) }}</td>

                                </tr>

                            </table>

                        </div>

                    </td>

                @endforeach

                @if ($row->count() === 1)

                    <td class="slip-cell"><div class="slip empty-slip">&nbsp;</div></td>

                @endif

            </tr>

        @endforeach

    </table>

@endforeach

</body>

</html>
