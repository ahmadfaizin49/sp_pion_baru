<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key'   => 'email_organisasi',
                'label' => 'Email SP PION (Kop PDF)',
                'value' => 'sppion18@gmail.com',
            ],
            [
                'key'   => 'dasar_hukum',
                'label' => 'Dasar Hukum (PDF Surat Kuasa)',
                'value' => json_encode([
                    'UU No. 21 Tahun 2000 Ttg SP/SB Jo. Kepmennakertrans RI No. 187/MEN/2004 Ttg Iuran Anggota SP/SB.',
                    'Bab IX Pasal 26 AD & Bab V Pasal 12 dan 13 ART. SP PION hasil Musyawarah Tahun 2018 Tentang Keuangan Organisasi SP PION.',
                ]),
            ],
            [
                'key'   => 'kuasa_teks',
                'label' => 'Teks Kuasa Pemotongan Upah (PDF Surat Kuasa)',
                'value' => 'Dengan ini saya memberikan kuasa khusus kepada Pengurus SERIKAT PEKERJA PUNGKOOK INDONESIA GROBOGAN untuk memotong upah kami masing-masing sebesar 1 % dari UMK yang berlaku pada tahun berjalan sebagai Uang pangkal (pasal 12 ART) sebanyak 1 x diawal, dan iuran bulanan (pasal 13 ART) sebesar Rp 5.000,00 (Lima Ribu Rupiah) dibulan berikutnya sampai berakhirnya Status keanggotaan melalui bagian keuangan perusahaan PT. Pungkook Indonesia Grobogan yang ditransfer ke rekening organisasi.',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
