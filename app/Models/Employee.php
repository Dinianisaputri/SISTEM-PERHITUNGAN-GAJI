<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'fingerprint_pin',
        'no_induk_karyawan',
        'name',
        'bagian',
        'jabatan',
        'penempatan',
        'jenis_karyawan',
        'pendidikan',
        'status_pernikahan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_domisili',
        'no_ktp',
        'no_rekening',
        'pas_foto',
        'mulai_kerja',
        'tanggal_kontrak',
        'selesai_kontrak',
        'kontrak_ke',
        'foto_kk',
        'foto_ktp',
        'foto_biodata',
        'surat_kontrak',
        'jumlah_sp',
        'is_active',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
        'rt',
        'rw',
        'detail_alamat',
        'npwp',
        'bpjs_tk_terdaftar',
        'no_bpjs_tk',
        'program_bpjs_tk',
        'bpjs_kesehatan_terdaftar',
        'no_bpjs_kesehatan',
        'kelas_bpjs_kesehatan',
        'tarif_bpjs',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'mulai_kerja' => 'date',
        'tanggal_kontrak' => 'date',
        'selesai_kontrak' => 'date',
        'kontrak_ke' => 'integer',
        'jumlah_sp' => 'integer',
        'is_active' => 'boolean',
        'bpjs_tk_terdaftar' => 'boolean',
        'bpjs_kesehatan_terdaftar' => 'boolean',
    ];

    public function getBpjsTkTerdaftarAttribute(): bool
    {
        return filled($this->attributes['program_bpjs_tk'] ?? null)
            && filled($this->attributes['no_bpjs_tk'] ?? null);
    }

    public function getBpjsKesehatanTerdaftarAttribute(): bool
    {
        return filled($this->attributes['kelas_bpjs_kesehatan'] ?? null)
            && filled($this->attributes['no_bpjs_kesehatan'] ?? null);
    }

    public function warningLetters(): HasMany
    {
        return $this->hasMany(WarningLetter::class);
    }
        public function warningNotices(): HasMany
    {
        return $this->hasMany(WarningNotice::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'provinsi_id');
    }

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'kabupaten_id');
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'kecamatan_id');
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'desa_id');
    }

    public function getAlamatLengkapAttribute(): string
    {
        $detail = trim((string) ($this->detail_alamat ?? ''));

        if ($detail === '') {
            $detail = trim((string) ($this->alamat_domisili ?? ''));
        }

        $detail = preg_replace('/\s+/u', ' ', $detail) ?? $detail;

        $normalisasi = static function (?string $teks): string {
            $teks = mb_strtolower(trim($teks ?? ''), 'UTF-8');
            $teks = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $teks) ?? $teks;

            return trim(preg_replace('/\s+/u', ' ', $teks) ?? $teks);
        };

        $wilayahList = [];

        foreach ([
            $this->desa?->nama,
            $this->kecamatan?->nama,
            $this->kabupaten?->nama,
            $this->provinsi?->nama,
        ] as $wilayah) {
            $wilayah = trim((string) $wilayah);

            if ($wilayah !== '') {
                $wilayahList[] = $wilayah;
            }
        }

        foreach (['rt' => 'RT', 'rw' => 'RW'] as $kolom => $label) {
            $nilai = trim((string) ($this->{$kolom} ?? ''));

            if ($nilai === '') {
                continue;
            }

            $detail = preg_replace(
                '/\b' . $label . '\.?\s*0*' . preg_quote($nilai, '/') . '\b/iu',
                ' ',
                $detail
            ) ?? $detail;
        }

        usort(
            $wilayahList,
            fn ($a, $b) => mb_strlen($b, 'UTF-8') <=> mb_strlen($a, 'UTF-8')
        );

        foreach ($wilayahList as $wilayah) {
            $detail = preg_replace(
                '/(?<![\p{L}\p{N}])'
                    . preg_quote($wilayah, '/')
                    . '(?![\p{L}\p{N}])/iu',
                ' ',
                $detail
            ) ?? $detail;
        }

        $detail = preg_replace('/\s+/u', ' ', $detail) ?? $detail;
        $detail = trim($detail, " \t\n\r\0\x0B,;.-");

        $bagian = [];

        if ($detail !== '') {
            $bagian[] = $detail;
        }

        $rt = trim((string) ($this->rt ?? ''));
        $rw = trim((string) ($this->rw ?? ''));

        if ($rt !== '') {
            $bagian[] = 'RT ' . $rt;
        }

        if ($rw !== '') {
            $bagian[] = 'RW ' . $rw;
        }

        foreach ([
            $this->desa?->nama,
            $this->kecamatan?->nama,
            $this->kabupaten?->nama,
            $this->provinsi?->nama,
        ] as $wilayah) {
            $wilayah = trim((string) $wilayah);

            if ($wilayah !== '') {
                $bagian[] = $wilayah;
            }
        }

        return implode(', ', array_values(array_unique($bagian))) ?: '-';
    }
}
