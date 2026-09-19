@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')

<div style="
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
">

    <div>
        <h1 class="page-title">
            Data Karyawan
        </h1>

        <p class="page-description" style="margin-bottom: 0;">
            Data karyawan borongan yang digunakan sebagai patokan saat import fingerprint.
        </p>
    </div>

    <a
        href="{{ route('employees.create') }}"
        class="btn btn-primary"
    >
        + Tambah Karyawan
    </a>

</div>


@if($employees->count())

<div class="card">

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>No. ID</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Bagian</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($employees as $employee)

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        <strong>
                            {{ $employee->no_id }}
                        </strong>
                    </td>

                    <td>
                        {{ $employee->nik ?? '-' }}
                    </td>

                    <td style="text-align: left;">
                        {{ $employee->name }}
                    </td>

                    <td>
                        {{ $employee->bagian ?? '-' }}
                    </td>

                    <td>

                        <div class="actions">

                            <a
                                href="{{ route('employees.edit', $employee) }}"
                                class="btn btn-warning"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('employees.destroy', $employee) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus data karyawan ini?')"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger"
                                >
                                    Hapus
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@else

<div class="card">

    <div style="
        text-align: center;
        padding: 40px;
        color: #6b7280;
    ">

        <div style="
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        ">
            Belum ada data karyawan
        </div>

        <div style="margin-bottom: 20px;">
            Tambahkan karyawan borongan untuk digunakan sebagai patokan data fingerprint.
        </div>

        <a
            href="{{ route('employees.create') }}"
            class="btn btn-primary"
        >
            + Tambah Karyawan
        </a>

    </div>

</div>

@endif

@endsection