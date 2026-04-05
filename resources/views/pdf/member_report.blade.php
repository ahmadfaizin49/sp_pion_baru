<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Registrasi Member</title>
    <style>
        @page {
            margin: 30px;
        }

        body {
            font-family: sans-serif;
            line-height: 1.1;
            color: #000;
            font-size: 12px;
            margin: 0;
            padding: 0;
            border: 2px solid #000;
        }

        .page-wrapper {
            padding: 15px;
        }

        header {
            margin-bottom: 2px;
            border-bottom: 4px double #000;
            padding-bottom: 2px;
        }

        .kop-table {
            width: 100%;
            border: none;
        }

        .logo-kop {
            width: 80px;
        }

        .text-kop {
            text-align: center;
        }

        .text-kop h1 {
            font-size: 12px;
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
        }

        .text-kop p {
            font-size: 11px;
            margin: 2px 0 0 0;
            line-height: 1.2;
            font-weight: normal;
        }

        .section {
            margin-bottom: 5px;
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
            padding: 1px 0;
        }

        .dotted-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            min-width: 150px;
        }

        .checkbox {
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            line-height: 12px;
            font-size: 10px;
            font-weight: bold;
            font-family: sans-serif;
        }

        .check-img {
            width: 8px;
            height: auto;
            margin-top: 2px;
        }

        .title-bold {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        .content-text {
            text-align: justify;
            margin-top: 2px;
            text-indent: 40px;
        }

        .no-indent {
            text-indent: 0 !important;
        }

        .signature-wrapper {
            margin-top: 5px;
            width: 100%;
        }

        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <header>
            <table class="kop-table">
                <tr>
                    <td width="15%" style="text-align: center; vertical-align: middle;">
                        <img src="{{ public_path('assets/images/pion/logo_sp_pion.png') }}" class="logo-kop">
                    </td>
                    <td width="85%" class="text-kop">
                        <h1>FORMULIR PENDAFTARAN ANGGOTA SERIKAT PEKERJA PUNGKOOK INDONESIA GROBOGAN <br> (SP PION)</h1>
                        <p>
                            JL. Raya Purwodadi-Blora Km.18 RT.001 RW.002, Desa Tanjungrejo Kecamatan Wirosari, Kabupaten<br>
                            Grobogan, Jawa Tengah-Indonesia 58192<br>
                            <strong>Email : {{ $emailOrganisasi }}</strong>
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
                    <td>{{ $member->nik_karyawan }}</td>
                </tr>
                <tr>
                    <td>3. Bag / Dept</td>
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
                Grobogan, .............................20....<br><br><br><br><br>
                ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <div class="section" style="margin-top: 50px;">
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
                <td>{{ $member->nik_ktp }}</td>
            </tr>
            <tr>
                <td>3. Bagian</td>
                <td>:</td>
                <td>{{ $member->department }}</td>
            </tr>
            <tr>
                <td>4. Alamat</td>
                <td>:</td>
                <td>{{ $member->address }}</td>
            </tr>
            <tr>
                <td>5. Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $member->birth_place }},
                    {{ \Carbon\Carbon::parse($member->birth_date)->format('d-m-Y') }}</td>
            </tr>
        </table>

        <p class="no-indent">Adalah anggota SP PION PT. PUNGKOOK INDONESIA GROBOGAN Berdasarkan :</p>
        <table style="margin-left: 20px;">
            @foreach($dasarHukum as $i => $poin)
                <tr>
                    <td width="20">{{ $i + 1 }}.</td>
                    <td>{{ $poin }}</td>
                </tr>
            @endforeach
        </table>

        <div class="content-text" style="margin-bottom: 5px;">
            {{ $kuasaTeks }}
        </div>

        <p style="text-indent: 40px; text-align: justify; margin-top: 5px;">
            Demikianlah surat kuasa ini saya buat dan ditanda tangani dalam keadaan sadar dan tanpa paksaan dari pihak
            manapun.
        </p>

        <div class="signature-wrapper">
            <div class="signature-box">
                Grobogan, .............................20....<br>
                yang membuat pernyataan,<br><br><br><br><br>
                ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</body>

</html>
