<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KTA {{ $user->name }}</title>
    <style>
        /*
         * Ukuran kartu standar ISO 7810 ID-1: 85.6mm x 53.98mm
         * Tinggi halaman = (53.98 * 2) + 4mm gap = 111.96mm
         */
        @page {
            margin: 0;
            padding: 0;
            size: 85.6mm 110mm; /* Pas 2 kartu atas dan bawah (109.96mm) */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-weight: bold;
            width: 85.6mm;
        }

        /* ===== WRAPPER ATAS BAWAH ===== */
        .kta-container {
            position: relative;
            width: 85.6mm;
            height: 110mm;
            overflow: hidden;
            display: block;
        }

        /* ===== CARD WRAPPER ===== */
        .card {
            position: absolute;
            left: 0;
            width: 85.6mm;
            height: 53.98mm;
            overflow: hidden;
        }

        .card.front {
            top: 0;
        }

        .card.back {
            top: 55.98mm; /* 53.98mm + 2mm margin tengah */
        }

        .card-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* ===== OVERLAY DATA (DEPAN) ===== */
        .card-data {
            position: absolute;
            top: 18mm;
            left: 2mm;
            right: 2mm;
        }

        /* Setiap baris = float layout agar lebih stabil di dompdf */
        .data-row {
            overflow: hidden;
            margin-bottom: 0.2mm; /* Dikurangi untuk merapatkan baris data */
        }

        .data-row:after {
            content: "";
            display: table;
            clear: both;
        }

        .col-label { float: left; width: 19mm; }
        .col-sep   { float: left; width: 3mm; }
        .col-value { float: left; width: 57mm; }

        /* ===== OUTLINED TEXT (Duplikasi seperti Flutter) ===== */
        /*
         * Teknik: buat wrapper "position: relative", lalu taruh dua span:
         * 1. .stroke  => teks warna PUTIH, posisi absolute, offset 4 arah (±0.8px)
         * 2. .fill    => teks warna HITAM, posisi relative (di atas stroke)
         * Hasilnya = outline putih di sekeliling teks hitam, 100% bekerja di dompdf
         */
        .ot {
            position: relative;
            display: block;
            font-size: 7pt;
            font-weight: bold;
            font-family: Arial, sans-serif;
            line-height: 1.25;
        }

        /* Layer stroke (putih) — duplikasi 4 arah */
        .ot .s1,
        .ot .s2,
        .ot .s3,
        .ot .s4 {
            position: absolute;
            top: 0;
            left: 0;
            color: #ffffff;
            font-size: 7pt;
            font-weight: bold;
            font-family: Arial, sans-serif;
            white-space: normal;
        }

        .ot .s1 { margin-top:  -0.6px; margin-left: -0.6px; }
        .ot .s2 { margin-top:  -0.6px; margin-left:  0.6px; }
        .ot .s3 { margin-top:   0.6px; margin-left: -0.6px; }
        .ot .s4 { margin-top:   0.6px; margin-left:  0.6px; }

        /* Layer fill (hitam) — di atas stroke */
        .ot .f {
            position: relative;
            color: #000000;
            font-size: 7pt;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }

        /* Versi font lebih kecil untuk barcode number (Tanpa Stroke) */
        .barcode-text {
            display: block;
            margin-top: 0.2mm; /* Beri jarak dengan barcode */
            font-size: 6pt;
            font-family: 'Times New Roman', Times, serif;
            color: #000000;
            text-align: center;
            line-height: 1.2;
        }

        /* Versi font lebih besar untuk SEKRETARIAT */
        .ot-lg {
            position: relative;
            display: block;
            font-size: 7.5pt;
            font-weight: bold;
            font-family: Arial, sans-serif;
            line-height: 1.2;
            text-align: center;
        }

        .ot-lg .s1,
        .ot-lg .s2,
        .ot-lg .s3,
        .ot-lg .s4 {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            text-align: center;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }

        .ot-lg .s1 { margin-top:  -0.7px; margin-left: -0.7px; }
        .ot-lg .s2 { margin-top:  -0.7px; margin-left:  0.7px; }
        .ot-lg .s3 { margin-top:   0.7px; margin-left: -0.7px; }
        .ot-lg .s4 { margin-top:   0.7px; margin-left:  0.7px; }

        .ot-lg .f {
            position: relative;
            display: block;
            text-align: center;
            color: #000000;
            font-size: 7.5pt;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }

        /* ===== BARCODE ===== */
        .card-barcode {
            position: absolute;
            bottom: 2mm;
            left: 6mm;
            width: 32mm; /* Lebar wadah diperbesar agar barcode direntangkan lebih lebar */
            text-align: center;
        }

        .card-barcode img {
            display: block;
            width: 100%; /* Selalu menyebar mengikuti lebar wadah */
            height: 6mm; /* Tingginya 6mm (sesuai editanmu) */
        }

        /* ===== SEKRETARIAT ===== */
        .card-sekretariat {
            position: absolute;
            bottom: 2.5mm;
            right: 2.5mm;
            text-align: center;
        }
    </style>
</head>
<body>

{{--
    Macro Blade: oteks($text) → outlined text (stroke putih + fill hitam)
    Render 4 lapisan warna putih sedikit bergeser, lalu teks hitam di atas
--}}

    <div class="kta-container">

        {{-- ===== KARTU DEPAN ===== --}}
        <div class="card front">
        {{-- Background KTA Depan --}}
        <img class="card-bg"
             src="{{ public_path('assets/images/pion/kta_front.png') }}"
             alt="KTA Depan">

        {{-- Data Anggota --}}
        <div class="card-data">

            {{-- Nomor KTA --}}
            <div class="data-row">
                <div class="col-label">
                    <div class="ot"><span class="s1">Nomor KTA</span><span class="s2">Nomor KTA</span><span class="s3">Nomor KTA</span><span class="s4">Nomor KTA</span><span class="f">Nomor KTA</span></div>
                </div>
                <div class="col-sep">
                    <div class="ot"><span class="s1">:</span><span class="s2">:</span><span class="s3">:</span><span class="s4">:</span><span class="f">:</span></div>
                </div>
                <div class="col-value">
                    @php $v = $user->kta_number ?? '-'; @endphp
                    <div class="ot"><span class="s1">{{ $v }}</span><span class="s2">{{ $v }}</span><span class="s3">{{ $v }}</span><span class="s4">{{ $v }}</span><span class="f">{{ $v }}</span></div>
                </div>
            </div>

            {{-- Nama --}}
            <div class="data-row">
                <div class="col-label">
                    <div class="ot"><span class="s1">Nama</span><span class="s2">Nama</span><span class="s3">Nama</span><span class="s4">Nama</span><span class="f">Nama</span></div>
                </div>
                <div class="col-sep">
                    <div class="ot"><span class="s1">:</span><span class="s2">:</span><span class="s3">:</span><span class="s4">:</span><span class="f">:</span></div>
                </div>
                <div class="col-value">
                    @php $v = strtoupper($user->name); @endphp
                    <div class="ot"><span class="s1">{{ $v }}</span><span class="s2">{{ $v }}</span><span class="s3">{{ $v }}</span><span class="s4">{{ $v }}</span><span class="f">{{ $v }}</span></div>
                </div>
            </div>

            {{-- Tanggal Lahir --}}
            <div class="data-row">
                <div class="col-label">
                    <div class="ot"><span class="s1">Tanggal Lahir</span><span class="s2">Tanggal Lahir</span><span class="s3">Tanggal Lahir</span><span class="s4">Tanggal Lahir</span><span class="f">Tanggal Lahir</span></div>
                </div>
                <div class="col-sep">
                    <div class="ot"><span class="s1">:</span><span class="s2">:</span><span class="s3">:</span><span class="s4">:</span><span class="f">:</span></div>
                </div>
                <div class="col-value">
                    @php $v = $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d-m-Y') : '-'; @endphp
                    <div class="ot"><span class="s1">{{ $v }}</span><span class="s2">{{ $v }}</span><span class="s3">{{ $v }}</span><span class="s4">{{ $v }}</span><span class="f">{{ $v }}</span></div>
                </div>
            </div>

            {{-- Jenis Kelamin --}}
            <div class="data-row">
                <div class="col-label">
                    <div class="ot"><span class="s1">Jenis Kelamin</span><span class="s2">Jenis Kelamin</span><span class="s3">Jenis Kelamin</span><span class="s4">Jenis Kelamin</span><span class="f">Jenis Kelamin</span></div>
                </div>
                <div class="col-sep">
                    <div class="ot"><span class="s1">:</span><span class="s2">:</span><span class="s3">:</span><span class="s4">:</span><span class="f">:</span></div>
                </div>
                <div class="col-value">
                    @php $v = $user->gender === 'male' ? 'LAKI-LAKI' : ($user->gender === 'female' ? 'PEREMPUAN' : '-'); @endphp
                    <div class="ot"><span class="s1">{{ $v }}</span><span class="s2">{{ $v }}</span><span class="s3">{{ $v }}</span><span class="s4">{{ $v }}</span><span class="f">{{ $v }}</span></div>
                </div>
            </div>

            {{-- Alamat Perusahaan --}}
            <div class="data-row">
                <div class="col-label">
                    @php $label = 'Alamat Perusahaan'; @endphp
                    <div class="ot"><span class="s1">{{ $label }}</span><span class="s2">{{ $label }}</span><span class="s3">{{ $label }}</span><span class="s4">{{ $label }}</span><span class="f">{{ $label }}</span></div>
                </div>
                <div class="col-sep">
                    <div class="ot"><span class="s1">:</span><span class="s2">:</span><span class="s3">:</span><span class="s4">:</span><span class="f">:</span></div>
                </div>
                <div class="col-value">
                    @php $v = 'Jl. Raya Purwodadi-Blora KM 18 RT 001 RW 002 Tanjungrejo Wirosari Grobogan Jawa Tengah'; @endphp
                    <div class="ot"><span class="s1">{{ $v }}</span><span class="s2">{{ $v }}</span><span class="s3">{{ $v }}</span><span class="s4">{{ $v }}</span><span class="f">{{ $v }}</span></div>
                </div>
            </div>

        </div><!-- /.card-data -->

        {{-- Barcode --}}
        @if($user->barcode_number)
        <div class="card-barcode">
            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($user->barcode_number, 'C128', 1, 22, [0,0,0], false) }}"
                 alt="barcode">
            <div class="barcode-text">{{ $user->barcode_number }}</div>
        </div>
        @endif

        {{-- Sekretariat --}}
        <div class="card-sekretariat">
            <div class="ot-lg">
                <span class="s1">SEKRETARIAT</span><span class="s2">SEKRETARIAT</span>
                <span class="s3">SEKRETARIAT</span><span class="s4">SEKRETARIAT</span>
                <span class="f">SEKRETARIAT</span>
            </div>
            <div class="ot-lg">
                <span class="s1">SP PION</span><span class="s2">SP PION</span>
                <span class="s3">SP PION</span><span class="s4">SP PION</span>
                <span class="f">SP PION</span>
            </div>
        </div>
        </div><!-- /.card.front -->

        {{-- ===== KARTU BELAKANG ===== --}}
        <div class="card back">
        <img class="card-bg"
             src="{{ public_path('assets/images/pion/kta_back.png') }}"
             alt="KTA Belakang">
    </div>

    </div><!-- /.kta-container -->

</body>
</html>
