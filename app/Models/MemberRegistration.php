<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberRegistration extends Model
{
    protected $fillable = [
        'referrer_id',
        'name',
        'nik_ktp',
        'nik_karyawan',
        'department',
        'birth_place',
        'birth_date',
        'address',
        'gender',
        'religion',
        'education',
        'phone',
        'status',
        'admin_note'
    ];

    protected $casts = [
        'referrer_id' => 'integer',
        'birth_date' => 'date',
    ];

    /**
     * Relasi: Mendapatkan data User yang mendaftarkan calon anggota ini
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }
}
