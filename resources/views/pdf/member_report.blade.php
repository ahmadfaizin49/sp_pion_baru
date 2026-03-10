<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Registrasi Member</title>
    <style>
        @page {
            margin: 160px 50px 50px 50px;
        }

        body {
            font-family: sans-serif;
            line-height: 1.1;
            color: #000;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        header {
            position: fixed;
            top: -120px;
            left: 0;
            right: 0;
            border-bottom: 3px double #000;
        }

        .kop-table {
            width: 100%;
            border: none;
        }

        .logo-kop {
            width: 100px;
        }

        .text-kop {
            text-align: center;
        }

        .text-kop h1 {
            font-size: 18px;
            margin: 0;
            text-transform: uppercase;
        }

        .text-kop h2 {
            font-size: 18px;
            color: red;
            margin: 0;
        }

        .text-kop p {
            font-size: 14px;
            margin: 5px 0 0 0;
            line-height: 1.3;
            font-weight: normal;
        }

        .section {
            margin-bottom: 20px;
        }

        .section p {
            text-indent: 40px;
            text-align: justify;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            padding: 2px 0;
        }

        .dotted-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            min-width: 150px;
        }

        .checkbox {
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            line-height: 14px;
            font-size: 11px;
            font-weight: bold;
            font-family: sans-serif;
        }

        .check-img {
            width: 10px;
            height: auto;
            margin-top: 2px;
        }

        .title-bold {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }

        .content-text {
            text-align: justify;
            margin-top: 10px;
            text-indent: 40px;
        }

        .no-indent {
            text-indent: 0 !important;
        }

        .signature-wrapper {
            margin-top: 20px;
            width: 100%;
        }

        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }

        .spacer {
            height: 60px;
        }
    </style>
</head>

<body>
    <header>
        <table class="kop-table">
            <tr>
                <td width="18%" style="text-align: center; vertical-align: middle;">
                    <img src="{{ public_path('assets/images/pion/logo_sp_pion.png') }}" class="logo-kop">
                </td>
                <td width="82%" class="text-kop">
                    <h1>Serikat Pekerja Pungkook Indonesia Grobogan</h1>
                    <h2>(SP PION)</h2>
                    <p>
                        Jl. Raya Purwodadi - Blora Km.18 RT.001 RW.002, Desa Tanjungrejo<br>
                        Kecamatan Wirosari, Kabupaten Grobogan, Jawa Tengah - Indonesia 58192
                    </p>
                </td>
            </tr>
        </table>
    </header>

    <div class="section">
        <table>
            <tr>
                <td width="150">1. Nama</td>
                <td width="10">:</td>
                <td>{{ $member->name }}</td>
            </tr>
            <tr>
                <td>2. NIK Karyawan</td>
                <td>:</td>
                <td>{{ $member->nik }}</td>
            </tr>
            <tr>
                <td>3. Departemen</td>
                <td>:</td>
                <td>{{ $member->department }}</td>
            </tr>
            <tr>
                <td>4. Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $member->birth_place }},
                    {{ \Carbon\Carbon::parse($member->birth_date)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td>5. Alamat</td>
                <td>:</td>
                <td>{{ $member->address }}
                </td>
            </tr>
            <tr>
                <td>6. Jenis Kelamin</td>
                <td>:</td>
                <td>
                    <div class="checkbox">
                        @if ($member->gender == 'male')
                            <img src="{{ public_path('assets/images/pion/icon_check.png') }}" class="check-img">
                        @endif
                    </div> Laki - Laki

                    <div class="checkbox" style="margin-left:20px">
                        @if ($member->gender == 'female')
                            <img src="{{ public_path('assets/images/pion/icon_check.png') }}" class="check-img">
                        @endif
                    </div> Perempuan
                </td>
            </tr>
            <tr>
                <td>7. Agama</td>
                <td>:</td>
                <td>{{ $member->religion }}</td>
            </tr>
            <tr>
                <td>8. Pendidikan</td>
                <td>:</td>
                <td>{{ $member->education }}</td>
            </tr>
            <tr>
                <td>9. No. Telp (WA)</td>
                <td>:</td>
                <td>{{ $member->phone }}</td>
            </tr>
        </table>

        <div class="content-text">
            Dengan ini saya menyatakan bergabung menjadi anggota (SP PION) dan bersedia patuh pada AD/ART (SP PION)
            maupun peraturan (SP PION).
        </div>

        <div class="signature-wrapper">
            <div class="signature-box">
                Grobogan, .............................20....<br><br><br><br>
                ( {{ $member->name }} )
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <div class="section">
        <div class="title-bold">
            SURAT KUASA<br>
            PEMOTONGAN UPAH UNTUK IURAN ANGGOTA<br>
            SERIKAT PEKERJA PUNGKOOK INDONESIA GROBOGAN (SP PION)<br>
            PT. PUNGKOOK INDONESIA GROBOGAN
        </div>

        <p class="no-indent">Yang bertanda tangan dibawah ini :</p>
        <table style="margin-left: 20px;">
            <tr>
                <td width="130">1. Nama</td>
                <td width="10">:</td>
                <td>{{ $member->name }}</td>
            </tr>
            <tr>
                <td>2. NIK KTP</td>
                <td>:</td>
                <td>{{ $member->nik }}</td>
            </tr>
            <tr>
                <td>3. Departemen</td>
                <td>:</td>
                <td>{{ $member->department }}</td>
            </tr>
            <tr>
                <td>4. Alamat</td>
                <td>:</td>
                <td>{{ $member->address }}</td>
            </tr>
            <tr>
                <td>4. Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $member->birth_place }},
                    {{ \Carbon\Carbon::parse($member->birth_date)->format('d-m-Y') }}</td>
            </tr>
        </table>

        <p class="no-indent">Adalah anggota SP PION PT. PUNGKOOK INDONESIA GROBOGAN Berdasarkan :</p>
        <table style="margin-left: 20px;">
            <tr>
                <td width="20">1.</td>
                <td>UU No. 21 Tahun 2000 Ttg SP/SB Jo. Kepmennakertrans RI No. 187/MEN/2004 Ttg Iuran Anggota SP/SB.
                </td>
            </tr>
            <tr>
                <td>2.</td>
                <td>Bab IX Pasal 26 AD & Bab V Pasal 12 dan 13 ART. SP PION hasil Musyawarah Tahun 2018 Tentang Keuangan
                    Organisasi SP PION.</td>
            </tr>
        </table>

        <div class="content-text" margin-bottom: 5px;">
            Dengan ini saya memberikan kuasa khusus kepada Pengurus SERIKAT PEKERJA PUNGKOOK INDONESIA GROBOGAN untuk
            memotong upah kami masing-masing sebesar 1 % dari UMK yang berlaku pada tahun berjalan sebagai Uang pangkal
            (pasal 12 ART) sebanyak 1 x diawal, dan iuran bulanan (pasal 13 ART) sebesar Rp 5.000,00 (Lima Ribu Rupiah)
            dibulan berikutnya sampai berakhirnya Status keanggotaan melalui bagian keuangan perusahaan PT. Pungkook
            Indonesia Grobogan yang ditransfer ke rekening organisasi.
        </div>

        <p style="text-indent: 40px; text-align: justify; margin-top: 5px;">
            Demikianlah surat kuasa ini saya buat dan ditanda tangani dalam keadaan sadar dan tanpa paksaan dari pihak
            manapun.
        </p>

        <div class="signature-wrapper">
            <div class="signature-box">
                Grobogan, .............................20....<br>
                yang membuat pernyataan,<br><br><br><br>
                ( {{ $member->name }} )
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</body>

</html>
