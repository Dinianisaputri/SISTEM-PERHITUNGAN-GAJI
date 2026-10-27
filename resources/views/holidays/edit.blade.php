@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="mb-4">
        <a href="{{ route('holidays.index') }}"
           class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Data Hari Libur
        </a>

        <h4 class="fw-bold mt-3 mb-1">
            Edit Hari Libur
        </h4>

        <p class="text-muted mb-0">
            Perbarui informasi hari libur.
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('holidays.update', $holiday) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- Tanggal --}}
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label fw-semibold">
                            Tanggal <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="tanggal"
                               id="tanggal"
                               class="form-control @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal', $holiday->tanggal->format('Y-m-d')) }}"
                               required>

                        @error('tanggal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Nama Libur --}}
                    <div class="col-md-6">
                        <label for="nama_libur" class="form-label fw-semibold">
                            Nama Libur <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="nama_libur"
                               id="nama_libur"
                               class="form-control @error('nama_libur') is-invalid @enderror"
                               value="{{ old('nama_libur', $holiday->nama_libur) }}"
                               required>

                        @error('nama_libur')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-12">
                        <label for="keterangan" class="form-label fw-semibold">
                            Keterangan
                        </label>

                        <textarea name="keterangan"
                                  id="keterangan"
                                  rows="4"
                                  class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $holiday->keterangan) }}</textarea>

                        @error('keterangan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Jenis --}}
                    <div class="col-12">
                        <div class="form-check">

                            <input type="hidden"
                                   name="is_national"
                                   value="0">

                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_national"
                                   id="is_national"
                                   value="1"
                                   {{ old('is_national', $holiday->is_national) ? 'checked' : '' }}>

                            <label class="form-check-label" for="is_national">
                                Hari libur nasional
                            </label>

                        </div>

                        <small class="text-muted">
                            Matikan pilihan ini jika hari libur hanya berlaku untuk perusahaan.
                        </small>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">

                    <a href="{{ route('holidays.index') }}"
                       class="btn btn-light">
                        Batal
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection