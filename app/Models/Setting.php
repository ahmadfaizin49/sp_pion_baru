<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // =========================================================
    // Daftar KEY yang tersedia — tambahkan di sini kalau ada key baru
    // =========================================================
    const IURAN_BULANAN_NOMINAL     = 'iuran_bulanan_nominal';
    const IURAN_BULANAN_TERBILANG   = 'iuran_bulanan_terbilang';
    const EMAIL_ORGANISASI          = 'email_organisasi';
    const DASAR_HUKUM               = 'dasar_hukum';
    const KUASA_TEKS                = 'kuasa_teks';
    // =========================================================

    protected $fillable = ['key', 'label', 'value'];

    /**
     * Helper: ambil value berdasarkan key, dengan default jika tidak ada.
     */
    public static function get(string $key, string $default = ''): string
    {
        return static::where('key', $key)->value('value') ?? $default;
    }
}
