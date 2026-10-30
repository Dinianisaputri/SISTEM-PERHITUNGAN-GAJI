
@extends('layouts.app')

@section('title', 'Panduan Penggunaan')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-800 p-6 text-white shadow-sm sm:p-8">
            <div class="relative flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-200">
                        Pusat bantuan
                    </p>
                    <h1 class="text-2xl font-bold sm:text-3xl">
                        Panduan Sistem HRD
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-emerald-50">
                        Langkah praktis mengelola data karyawan, absensi, jadwal kerja,
                        dan penggajian PT Starfood International.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-2 text-xs font-medium text-emerald-50">
                        <span class="rounded-full bg-white/10 px-3 py-1.5">1. Data karyawan</span>
                        <span class="rounded-full bg-white/10 px-3 py-1.5">2. Absensi & jadwal</span>
                        <span class="rounded-full bg-white/10 px-3 py-1.5">3. Hitung & arsipkan gaji</span>
                    </div>
                </div>

                <div class="hidden h-20 w-20 shrink-0 items-center justify-center rounded-3xl border border-white/15 bg-white/10 text-2xl font-bold sm:flex">
                    ?
                </div>
            </div>
        </div>

        {{-- Search --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <label for="panduan-search" class="mb-2 block text-sm font-semibold text-slate-700">
                Apa yang ingin Anda cari?
            </label>
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle>
                    <path stroke-linecap="round" d="m20 20-4-4"></path>
                </svg>
                <input
                    id="panduan-search"
                    type="search"
                    placeholder="Cari, misalnya: PIN, impor absensi, hitung gaji..."
                    class="w-full rounded-lg border border-slate-300 py-3 pl-10 pr-4 text-sm
                           focus:border-emerald-600 focus:outline-none focus:ring-2
                           focus:ring-emerald-100"
                    aria-describedby="panduan-search-hint"
                >
            </div>
            <p id="panduan-search-hint" class="mt-2 text-xs text-slate-500">
                Ketik kata kunci untuk menampilkan topik yang sesuai.
            </p>
        </div>

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-[260px_minmax(0,1fr)]">

            {{-- Daftar isi --}}
            <aside class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm lg:sticky lg:top-24">
                <h2 class="mb-4 text-base font-bold text-slate-900">Daftar Isi</h2>

                <nav class="space-y-1 text-sm" aria-label="Daftar isi panduan">
                    <a href="#tentang" data-guide-link="tentang"
                       class="block rounded-lg px-3 py-2 text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-800">
                        Mulai di sini
                    </a>
                    <a href="#login" data-guide-link="login"
                       class="block rounded-lg px-3 py-2 text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-800">
                        Login & lupa password
                    </a>
                    <a href="#karyawan" data-guide-link="karyawan"
                       class="block rounded-lg px-3 py-2 text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-800">
                        Data karyawan
                    </a>
                    <a href="#absensi" data-guide-link="absensi"
                       class="block rounded-lg px-3 py-2 text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-800">
                        Absensi & checklog
                    </a>
                    <a href="#jadwal" data-guide-link="jadwal"
                       class="block rounded-lg px-3 py-2 text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-800">
                        Jadwal kerja
                    </a>
                    <a href="#penggajian" data-guide-link="penggajian"
                       class="block rounded-lg px-3 py-2 text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-800">
                        Perhitungan gaji
                    </a>
                    <a href="#arsip" data-guide-link="arsip"
                       class="block rounded-lg px-3 py-2 text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-800">
                        Arsip gaji
                    </a>
                    <a href="#masalah" data-guide-link="masalah"
                       class="block rounded-lg px-3 py-2 text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-800">
                        Pemecahan masalah
                    </a>
                    <a href="#catatan" data-guide-link="catatan"
                       class="block rounded-lg px-3 py-2 text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-800">
                        Keamanan data
                    </a>
                </nav>
            </aside>

            {{-- Isi panduan --}}
            <main id="panduan-content" class="min-w-0 space-y-6">
                <p id="panduan-no-results" hidden class="rounded-xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-900" role="status">
                    Tidak ada topik yang cocok. Coba kata kunci lain.
                </p>

                {{-- 1 --}}
                <section id="tentang" data-searchable
                         class="scroll-mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                        Pengenalan
                    </p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Mulai di sini</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Gunakan aplikasi sesuai hak akses akun Anda. Untuk alur penggajian,
                        pastikan data karyawan, absensi, dan jadwal sudah diperiksa sebelum
                        menghitung gaji. Setelah hasilnya benar, simpan ke arsip.
                    </p>

                    <div class="mt-5 grid gap-3 sm:grid-cols-3">
                        <a href="#karyawan" class="rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-emerald-300 hover:bg-emerald-50">
                            <span class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Langkah 1</span>
                            <span class="mt-1 block font-semibold text-slate-800">Siapkan data</span>
                            <span class="mt-1 block text-xs leading-5 text-slate-500">Data karyawan dan jadwal kerja.</span>
                        </a>
                        <a href="#absensi" class="rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-emerald-300 hover:bg-emerald-50">
                            <span class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Langkah 2</span>
                            <span class="mt-1 block font-semibold text-slate-800">Periksa absensi</span>
                            <span class="mt-1 block text-xs leading-5 text-slate-500">Impor dan tinjau data fingerprint.</span>
                        </a>
                        <a href="#penggajian" class="rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-emerald-300 hover:bg-emerald-50">
                            <span class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Langkah 3</span>
                            <span class="mt-1 block font-semibold text-slate-800">Hitung & arsipkan</span>
                            <span class="mt-1 block text-xs leading-5 text-slate-500">Periksa hasil sebelum disimpan.</span>
                        </a>
                    </div>
                </section>

                {{-- 2 --}}
                <section id="login" data-searchable
                         class="scroll-mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                        Akses Akun
                    </p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Login dan lupa password</h2>

                    <h3 class="mt-5 font-semibold text-slate-800">A. Login</h3>
                    <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm leading-6 text-slate-600">
                        <li>Buka alamat aplikasi melalui browser.</li>
                        <li>Masukkan email yang terdaftar dan password akun.</li>
                        <li>Klik <strong>Masuk</strong> untuk membuka dashboard.</li>
                    </ol>

                    <h3 class="mt-6 font-semibold text-slate-800">B. Lupa Password</h3>
                    <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm leading-6 text-slate-600">
                        <li>Pada halaman login, pilih <strong>Lupa password?</strong>.</li>
                        <li>Masukkan alamat email yang terdaftar pada akun.</li>
                        <li>Klik <strong>Kirim Link Reset</strong>.</li>
                        <li>Buka email dan gunakan tautan reset terbaru. Periksa folder spam jika belum terlihat.</li>
                        <li>Masukkan password baru dan konfirmasinya, kemudian klik <strong>Reset Password</strong>.</li>
                        <li>Coba login kembali menggunakan password baru.</li>
                    </ol>

                    <div class="mt-5 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                        <strong>Email reset tidak terkirim?</strong>
                        Hubungi administrator. Pengiriman perlu dikonfigurasi dengan domain
                        pengirim yang sudah diverifikasi; alamat Gmail sebagai pengirim Resend
                        tidak dapat digunakan.
                    </div>
                </section>

                {{-- 3 --}}
                <section id="karyawan" data-searchable
                         class="scroll-mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                        Data Master
                    </p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Data karyawan</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Gunakan menu <strong>Data Karyawan</strong> untuk melihat dan mengelola
                        informasi karyawan. Dashboard karyawan menampilkan ringkasan, sedangkan
                        halaman data karyawan digunakan untuk mencari dan membuka data.
                    </p>

                    <h3 class="mt-5 font-semibold text-slate-800">Alur kerja</h3>
                    <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm leading-6 text-slate-600">
                        <li>Buka <strong>Karyawan &rarr; Data Karyawan</strong>.</li>
                        <li>Gunakan pencarian atau filter untuk menemukan karyawan.</li>
                        <li>Pilih <strong>Tambah Karyawan</strong> untuk membuat data baru, atau buka data yang ingin diperbarui.</li>
                        <li>Lengkapi atau periksa informasi, lalu simpan perubahan.</li>
                        <li>Untuk daftar PDF, gunakan fitur ekspor dan periksa hasilnya sebelum dibagikan.</li>
                    </ol>

                    <div class="mt-5 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                        Pastikan PIN fingerprint dan data identitas benar agar data absensi
                        dapat dikaitkan dengan karyawan yang tepat.
                    </div>
                </section>

                {{-- 4 --}}
                <section id="absensi" data-searchable
                         class="scroll-mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                        Kehadiran
                    </p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Absensi dan data fingerprint</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Data fingerprint berisi catatan kehadiran dari perangkat. Impor data
                        sebelum proses penggajian, lalu periksa apakah PIN sudah terhubung
                        dengan data karyawan.
                    </p>

                    <h3 class="mt-5 font-semibold text-slate-800">Impor data</h3>
                    <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm leading-6 text-slate-600">
                        <li>Buka <strong>Absensi &rarr; Data Fingerprint</strong>.</li>
                        <li>Pilih impor Excel atau DAT sesuai format file sumber.</li>
                        <li>Pilih file fingerprint dan jalankan impor.</li>
                        <li>Periksa hasil impor dan baca pesan jika ada baris yang gagal.</li>
                        <li>Buka <strong>PIN Belum Terdaftar</strong> untuk meninjau PIN yang belum cocok dengan karyawan.</li>
                    </ol>

                    <div class="mt-5 rounded-lg bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                        Gunakan file ekspor perangkat dengan format yang didukung. Jangan
                        mengimpor ulang file yang sama sebelum memeriksa hasilnya untuk
                        menghindari data ganda.
                    </div>
                </section>

                {{-- 5 --}}
                <section id="jadwal" data-searchable
                         class="scroll-mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                        Pengaturan Jam Kerja
                    </p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Jam kerja dan jadwal harian</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Master Jam Kerja menyimpan pola jam masuk dan pulang. Jadwal Kerja
                        Harian menetapkan pola tersebut ke tanggal dan penempatan tertentu.
                    </p>

                    <h3 class="mt-5 font-semibold text-slate-800">Atur jadwal</h3>
                    <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm leading-6 text-slate-600">
                        <li>Buka <strong>Absensi &rarr; Master Jam Kerja</strong> dan buat atau periksa pola jam kerja.</li>
                        <li>Buka <strong>Jadwal Kerja Harian</strong>, lalu pilih tanggal, penempatan, dan pola jam kerja.</li>
                        <li>Simpan jadwal dan periksa kembali tanggal serta penempatannya.</li>
                        <li>Kelola tanggal libur melalui menu <strong>Hari Libur</strong>.</li>
                    </ol>

                    <div class="mt-5 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                        Periksa jadwal sebelum menghitung gaji. Jadwal harian ditetapkan
                        berdasarkan tanggal dan penempatan.
                    </div>
                </section>

                {{-- 6 --}}
                <section id="penggajian" data-searchable
                         class="scroll-mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                        Proses Penggajian
                    </p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Perhitungan gaji</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Perhitungan gaji menggunakan data absensi untuk rentang tanggal
                        yang dipilih. Tinjau data dan filter sebelum menyimpan hasil sebagai arsip.
                    </p>

                    <h3 class="mt-5 font-semibold text-slate-800">Sebelum menyimpan hasil</h3>
                    <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm leading-6 text-slate-600">
                        <li>Buka <strong>Penggajian &rarr; Gaji Karyawan</strong>.</li>
                        <li>Pilih tanggal mulai dan selesai. Persempit daftar dengan jenis karyawan, bagian, atau pencarian bila diperlukan.</li>
                        <li>Periksa daftar dan buka detail karyawan untuk meninjau rincian perhitungan.</li>
                        <li>Jika hasil sudah benar, klik <strong>Simpan Arsip Gaji</strong>.</li>
                    </ol>

                    <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm leading-6 text-red-900">
                        Arsip menyimpan hasil untuk filter dan periode yang sedang dipilih.
                        Pastikan tanggal, bagian, dan jenis karyawan sudah benar sebelum
                        mengarsipkan.
                    </div>
                </section>

                {{-- 7 --}}
                <section id="arsip" data-searchable
                         class="scroll-mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                        Dokumen dan Riwayat
                    </p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Arsip gaji</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Arsip menyimpan hasil penggajian yang sudah dibuat agar dapat
                        ditinjau kembali berdasarkan nama arsip dan periode.
                    </p>

                    <h3 class="mt-5 font-semibold text-slate-800">Buka arsip</h3>
                    <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm leading-6 text-slate-600">
                        <li>Buka <strong>Penggajian &rarr; Arsip Gaji</strong>.</li>
                        <li>Periksa nama arsip, periode, jumlah karyawan, dan total gaji.</li>
                        <li>Pilih <strong>Detail</strong> untuk meninjau rincian arsip atau mengunduh keluaran yang tersedia.</li>
                        <li>Hapus arsip hanya jika memang diperlukan karena tindakan ini juga menghapus detail terkait.</li>
                    </ol>

                    <div class="mt-5 rounded-lg bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                        Data arsip gaji bersifat sensitif. Bagikan hasilnya hanya kepada
                        pihak yang berwenang.
                    </div>
                </section>

                {{-- Pemecahan masalah --}}
                <section id="masalah" data-searchable
                         class="scroll-mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                        Bantuan
                    </p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Pemecahan masalah</h2>

                    <div class="mt-5 space-y-4">
                        <div class="rounded-lg border border-slate-200 p-4">
                            <h3 class="font-semibold text-slate-800">Tidak bisa login</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Periksa email dan password. Pastikan akun terdaftar dan
                                gunakan fitur Lupa Password jika password tidak diketahui.
                            </p>
                        </div>

                        <div class="rounded-lg border border-slate-200 p-4">
                            <h3 class="font-semibold text-slate-800">Email reset password tidak masuk</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Periksa folder spam dan pastikan alamat email akun benar.
                                Jika muncul pesan domain pengirim belum diverifikasi,
                                administrator perlu memverifikasi domain pengirim di Resend.
                                Alamat Gmail penerima tetap dapat digunakan.
                            </p>
                        </div>

                        <div class="rounded-lg border border-slate-200 p-4">
                            <h3 class="font-semibold text-slate-800">Tautan reset tidak valid</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Minta tautan reset terbaru dan gunakan tautan tersebut.
                                Hindari memakai tautan lama setelah meminta reset kembali.
                            </p>
                        </div>

                        <div class="rounded-lg border border-slate-200 p-4">
                            <h3 class="font-semibold text-slate-800">Muncul pesan untuk menunggu sebelum mencoba lagi</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Tunggu beberapa saat sebelum mengirim permintaan reset
                                password kembali. Jangan menekan tombol berulang kali.
                            </p>
                        </div>

                        <div class="rounded-lg border border-slate-200 p-4">
                            <h3 class="font-semibold text-slate-800">Impor data gagal</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Periksa format file, susunan kolom, dan pesan kesalahan.
                                Jangan mengulang impor berkali-kali sebelum memastikan
                                apakah sebagian data sudah masuk.
                            </p>
                        </div>

                        <div class="rounded-lg border border-slate-200 p-4">
                            <h3 class="font-semibold text-slate-800">Hasil perhitungan gaji tidak sesuai</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Periksa data absensi, jam masuk dan pulang, input istirahat,
                                serta jadwal yang berlaku. Jangan mengubah rumus atau arsip
                                tanpa memeriksa penyebabnya terlebih dahulu.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- Keamanan --}}
                <section id="catatan" data-searchable
                         class="scroll-mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                        Keamanan
                    </p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Keamanan data</h2>

                    <ul class="mt-4 list-disc space-y-3 pl-5 text-sm leading-6 text-slate-600">
                        <li>Gunakan akun sesuai hak akses yang diberikan.</li>
                        <li>Jangan membagikan password, API Key, atau tautan reset password kepada orang lain.</li>
                        <li>Periksa data sebelum menyimpan, mengimpor, menghapus, atau mengekspor.</li>
                        <li>Jaga kerahasiaan data pribadi karyawan dan dokumen penggajian.</li>
                        <li>Jangan mengubah data historis gaji tanpa prosedur dan persetujuan yang berlaku.</li>
                        <li>Jika menemukan kesalahan, catat pesan error dan halaman tempat masalah terjadi untuk membantu proses pemeriksaan.</li>
                    </ul>

                    <div class="mt-6 rounded-xl bg-slate-900 p-5 text-white">
                        <h3 class="font-semibold">Butuh bantuan lebih lanjut?</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-300">
                            Hubungi administrator atau penanggung jawab aplikasi dengan
                            menyertakan nama halaman, waktu kejadian, dan pesan kesalahan.
                            Jangan menyertakan password atau token rahasia.
                        </p>
                    </div>
                </section>

                <footer class="pb-6 text-center text-xs leading-6 text-slate-500">
                    Panduan Penggunaan Sistem HRD dan Penggajian<br>
                    PT Starfood International
                </footer>
            </main>
        </div>
</div>

{{-- Pencarian isi panduan --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('panduan-search');
    const sections = document.querySelectorAll('[data-searchable]');
    const links = document.querySelectorAll('[data-guide-link]');
    const noResults = document.getElementById('panduan-no-results');

    if (!search) return;

    search.addEventListener('input', function () {
        const keyword = this.value.toLocaleLowerCase('id-ID').trim();
        let visibleCount = 0;

        sections.forEach(function (section) {
            const content = section.textContent.toLocaleLowerCase('id-ID');
            const isVisible = keyword === '' || content.includes(keyword);

            section.hidden = !isVisible;
            visibleCount += isVisible ? 1 : 0;
        });

        links.forEach(function (link) {
            const section = document.getElementById(link.dataset.guideLink);
            link.hidden = Boolean(keyword) && (!section || section.hidden);
        });

        noResults.hidden = visibleCount > 0;
    });
});
</script>
@endsection
