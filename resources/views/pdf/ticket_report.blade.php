<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pesan</title>
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

        .ticket-header {
            margin-bottom: 10px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #eee;
        }

        .ticket-header table {
            width: 100%;
            border: none;
        }

        .ticket-header td {
            font-size: 13px;
            vertical-align: top;
            padding: 3px 0;
        }

        .description-section {
            padding: 15px;
            margin-bottom: 30px;
            border-bottom: 1px dashed #ccc;
        }

        .description-title {
            font-size: 12px;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }

        .description-text {
            font-size: 14px;
            color: #000;
            text-align: justify;
        }

        .chat-section {
            margin-top: 10px;
        }

        /* Bubble Chat Utama */
        .chat-bubble {
            margin-bottom: 12px;
            padding: 10px 15px;
            border-radius: 10px;
            page-break-inside: avoid;
            /* Mencegah chat terpotong ganti halaman */
            position: relative;
            width: 85%;
            /* Agar tidak terlalu lebar ke samping */
        }

        /* Gaya Khusus Admin (Rata Kiri) */
        .admin-bg {
            background-color: #f1f3f4;
            border: 1px solid #d1d3d4;
            border-left: 6px solid #1a73e8;
            /* Aksen Biru Google */
            margin-right: auto;
        }

        /* Gaya Khusus User/Member (Rata Kanan) */
        .user-bg {
            background-color: #e6f4ea;
            border: 1px solid #ceead6;
            border-right: 6px solid #34a853;
            /* Aksen Hijau */
            margin-left: auto;
            text-align: left;
            /* Teks tetap rata kiri di dalam bubble */
        }

        .sender-info {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 4px;
            display: block;
        }

        /* Warna nama pengirim */
        .admin-bg .sender-info {
            color: #1a73e8;
        }

        .user-bg .sender-info {
            color: #188038;
        }

        .message {
            font-size: 13px;
            color: #202124;
            line-height: 1.4;
        }

        .timestamp {
            font-size: 9px;
            color: #70757a;
            margin-top: 6px;
            font-style: italic;
            display: block;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding-top: 3px;
        }

        /* Styling Judul Riwayat */
        .riwayat-title {
            border-bottom: 2px solid #1a73e8;
            padding-bottom: 5px;
            color: #1a73e8;
            font-size: 13px;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .attachment-wrapper {
            margin-top: 10px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            display: inline-block;
        }

        .img-attachment {
            max-width: 250px;
            height: auto;
            display: block;
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

    <div class="ticket-header">
        <table>
            <tr>
                <td style="width: 150px;"><strong>Nama</strong></td>
                <td style="width: 10px;">:</td>
                <td>{{ $ticket->user->name }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Buat</strong></td>
                <td>:</td>
                <td>
                    {{ \Carbon\Carbon::parse($ticket->created_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') }}
                </td>
            </tr>
            <tr>
                <td><strong>Tipe</strong></td>
                <td>:</td>
                <td>
                    @if ($ticket->type == 'report')
                        Laporan
                    @elseif($ticket->type == 'question')
                        Pertanyaan
                    @elseif($ticket->type == 'suggestion')
                        Saran
                    @else
                        {{ strtoupper($ticket->type) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Judul</strong></td>
                <td>:</td>
                <td>{{ $ticket->title ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Detail</strong></td>
                <td>:</td>
                <td>{{ $ticket->description ?? '-' }}</td>
            </tr>

            {{-- Cek apakah ada attachment DAN apakah user tidak sedang meminta untuk menyembunyikannya --}}
            @if ($ticket->attachment && request('hide_attachment') != 1)
                <tr>
                    <td><strong>Lampiran</strong></td>
                    <td>:</td>
                    <td>
                        @php
                            $extension = pathinfo($ticket->attachment, PATHINFO_EXTENSION);
                            $fullUrl = url('storage/' . $ticket->attachment);
                        @endphp

                        <div class="attachment-wrapper" style="display: table; width: auto; margin-top: 5px;">
                            @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                                <a href="{{ $fullUrl }}" target="_blank" style="text-decoration:none;">
                                    <img src="{{ public_path('storage/' . $ticket->attachment) }}"
                                        class="img-attachment" style="display:block;">
                                </a>
                            @else
                                <a href="{{ $fullUrl }}" target="_blank"
                                    style="text-decoration: none; color: inherit; display:inline-block; cursor:pointer;">
                                    <table style="width:auto; border:0; border-collapse:collapse;">
                                        <tr>
                                            <td style="border:0; padding:5px; vertical-align:middle;">
                                                <a href="{{ $fullUrl }}" target="_blank"
                                                    style="text-decoration:none;">
                                                    <span style="font-size:12px; color:#1a73e8; font-weight:bold;">
                                                        Dokumen Lampiran (PDF)
                                                    </span><br>

                                                    <span style="font-size:10px; color:#666;">
                                                        {{ basename($ticket->attachment) }}
                                                    </span>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endif
        </table>
    </div>

    @if ($ticket->replies->isNotEmpty())
        <div class="chat-section">
            <div class="riwayat-title">RIWAYAT BALASAN / CHAT</div>

            @foreach ($ticket->replies as $reply)
                <div class="chat-bubble {{ $reply->user->role == 'admin' ? 'admin-bg' : 'user-bg' }}">
                    <div class="sender-info">
                        {{ $reply->user->name }} ({{ strtoupper($reply->user->role) }})
                    </div>
                    <div class="message">{{ $reply->message }}</div>
                    <div class="timestamp">{{ $reply->created_at->format('d/m/Y H:i') }}</div>
                </div>
            @endforeach
        </div>
    @endif
</body>

</html>
