<!DOCTYPE html>
<html>

<head>
    <title>Berita Acara</title>
    {{-- Atur lokal Carbon ke Bahasa Indonesia --}}
    @php
        \Carbon\Carbon::setLocale('id');
    @endphp
    <style>
        /* CSS Sederhana untuk PDF */
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        /* === TAMBAHKAN BLOK INI UNTUK MARGIN === */
        @page {
            /* Atur margin halaman: atas, kanan, bawah, kiri */
            margin: 1.5cm 2cm 1.5cm 2cm;
        }

        /* ======================================= */

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            border-bottom: 4px solid black;
        }

        .header-table td {
            vertical-align: top;
        }

        .detail-table th,
        .detail-table td {
            border: 1px solid black;
            padding: 8px;
        }

        .detail-table thead tr {
            background-color: #f2f2f2;
            text-align: center;
        }

        .signature-table {
            margin-top: 24px;
            text-align: center;
        }

        li {
            text-align: justify;
        }

        p {
            text-align: justify;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td style="width: 15%; text-align: center;">
                {{-- GANTI URL_LOGO DENGAN LOGO PUBLIK ATAU BASE64 --}}
                <img src="{{ public_path('src/images/logo/logo_kemenhut.png') }}" alt="Logo"
                    style="width: 80px; height: auto;" />
            </td>
            <td style="width: 85%; text-align: center; line-height: 1.2;">
                <h4 style="margin: 0; font-weight: bold;">KEMENTERIAN KEHUTANAN</h4>
                <h4 style="margin: 0; font-weight: bold;">DIREKTORAT JENDERAL PLANOLOGI KEHUTANAN</h4>
                <h3 style="margin: 0; font-weight: bold;">BALAI PEMANTAPAN KAWASAN HUTAN WILAYAH IX</h3>
                <p style="margin: 0; font-size: 10pt; text-align: center;">Alamat: Jalan Kebun Cengkeh Ambon, Kode Pos
                    97128, Telphone/Fax:
                    (0911) 342632 Kotak Pos 1125</p>
            </td>
        </tr>
    </table>
    <div style="text-align: center;margin-top:12px">
        <h4 style="margin: 0px; font-weight: bold; line-height: 1.2;">BERITA ACARA</h4>
        <h4 style="margin: 0; font-weight: bold; text-decoration: underline;">SERAH TERIMA DATA DAN INFORMASI GEOSPASIAL
        </h4>
        {{-- Ganti Nomor BA jika perlu --}}
        <p style="margin: 0px; line-height: 1.2; text-align: center;">NOMOR:
            BA/{{ $permohonan->id }}/{{ \Carbon\Carbon::now()->format('Y/m/d') }}</p>
    </div>
    <p style="margin-top: 12px; margin-bottom: 6px; text-align: justify;">Pada hari ini, {{ $tanggal_ba }}, di
        Kantor Balai Pemantapan Kawasan Hutan Wilayah IX Ambon, yang bertanda tangan di bawah ini:</p>

    {{-- PIHAK PERTAMA (Staf/Kantor) --}}
    <table style="width: 100%; border-collapse: collapse; margin-left: 30px;">
        <tbody>
            <tr>
                <td style="width: 120px; vertical-align: top;">Nama</td>
                <td style="width: 10px; vertical-align: top;">:</td>
                <td style="vertical-align: top; font-weight: bold;">{{ $pihakPertama['nama'] }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">NIP</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $pihakPertama['nip'] }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Jabatan</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $pihakPertama['jabatan'] }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Instansi</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">Balai Pemantapan Kawasan Hutan Wilayah IX Ambon</td>
            </tr>
            <tr>
                <td style="padding-top: 5px; padding-bottom: 15px;" colspan="3">Bertindak untuk dan atas nama Unit
                    Kliring, selanjutnya disebut <strong>PIHAK PERTAMA</strong></td>
            </tr>

            {{-- PIHAK KEDUA (Pemohon) --}}
            <tr>
                <td style="vertical-align: top;">Nama</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top; font-weight: bold;">{{ $permohonan->nama_pemohon }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">NIP</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $permohonan->nip }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Jabatan</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $permohonan->jabatan }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Instansi</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $permohonan->instansi }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Alamat Email</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $permohonan->email }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">No. Telepon</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $permohonan->no_hp }}</td>
            </tr>
            <tr>
                <td style="padding-top: 5px; padding-bottom: 15px;" colspan="3">Bertindak untuk dan atas nama
                    {{ $permohonan->instansi }}, selanjutnya disebut <strong>PIHAK KEDUA</strong></td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 0px; margin-bottom: 0px;">telah melakukan serah terima data dengan rincian sebagai berikut:
    </p>

    {{-- Loop Data yang Diminta --}}
    <table class="detail-table">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="border: 1px solid black; padding: 8px;">No.</th>
                <th style="border: 1px solid black; padding: 8px;">Jenis Data IGT</th>
                <th style="border: 1px solid black; padding: 8px;">Cakupan</th>
                <th style="border: 1px solid black; padding: 8px;">Keterangan</th>
                <th style="border: 1px solid black; padding: 8px;">Format</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permohonan->detailPermohonan as $index => $detail)
                <tr style="text-align: left;">
                    <td style="border: 1px solid black; padding: 8px; text-align: center;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid black; padding: 8px;">{{ $detail->dataIgt->jenis_data ?? 'N/A' }}</td>
                    <td style="border: 1px solid black; padding: 8px;">{{ $detail->cakupan_wilayah }}</td>
                    <td style="border: 1px solid black; padding: 8px;">{{ $detail->keterangan ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 8px;">{{ $detail->dataIgt->format_data ?? 'N/A' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 4px; margin-bottom: 0px; text-align: justify;"><strong>PIHAK KEDUA</strong> menerima data yang
        diberikan oleh <strong>PIHAK PERTAMA</strong> dengan penuh tanggung jawab serta berusaha memenuhi ketentuan
        sebagai berikut:</p>
    <ol style="list-style-type: lower-alpha; padding-left: 20px; margin-top: 0px; margin-bottom: 0px;">
        <li style="text-align: justify;">Menggunakan data dan informasi geospasial yang diberikan oleh <strong>PIHAK
                PERTAMA</strong> hanya untuk kepentingan dan kegiatan <strong>PIHAK KEDUA</strong>;</li>
        <li style="text-align: justify;">Menjaga agar data dan informasi geospasial sebagaimana tersebut dalam rincian
            di atas tidak dimanfaatkan oleh pihak lain tanpa sepengetahuan dan persetujuan tertulis dari <strong>PIHAK
                PERTAMA</strong>;</li>
        <li style="text-align: justify;">Melaporkan hasil analisis dan kesimpulan dari hasil kegiatan (sebagaimana
            disebut pada butir 1) kepada Kepala Balai Pemantapan Kawasan Hutan Wilayah IX Ambon selaku Sub Unit Kliring;
        </li>
        <li style="text-align: justify;">Tidak membuat salinan, perubahan dan penyebarluasan data dan informasi
            geospasial kepada pihak lain;</li>
        <li style="text-align: justify;">Wajib mencantumkan sumber data dalam produk hasil analisis.</li>
    </ol>
    <p style="text-align: justify; margin-top: 4px; margin-bottom: 4px;">Pelanggaran terhadap butir-butir kesepakatan di
        atas oleh <strong>PIHAK KEDUA</strong> atau pihak manapun yang berafiliasi dengan <strong>PIHAK KEDUA</strong>,
        maka <strong>PIHAK KEDUA</strong> bersedia untuk dikenakan sanksi sesuai dengan peraturan perundang-undangan
        yang berlaku.</p>

    {{-- Tanda Tangan --}}
    {{-- Tanda Tangan --}}
    <table class="signature-table" style="text-align: center;">
        <tbody>
            <tr>
                <td style="width: 50%;">
                    {{-- TAMBAHKAN 'text-align: center;' PADA SETIAP <p> --}}
                    <p style="text-align: center;">PIHAK PERTAMA,</p>
                    <br><br><br><br>
                    <p style="font-weight: bold; text-decoration: underline; margin-bottom: 0px; text-align: center;">
                        {{ $pihakPertama['nama'] }}
                    </p>
                    <p style="margin-top: 0px; text-align: center;">NIP. {{ $pihakPertama['nip'] }}</p>
                </td>
                <td style="width: 50%;">
                    {{-- TAMBAHKAN 'text-align: center;' PADA SETIAP <p> --}}
                    <p style="text-align: center;">PIHAK KEDUA,</p>
                    <br><br><br><br>
                    <p style="font-weight: bold; text-decoration: underline; margin-bottom: 0px; text-align: center;">
                        {{ $permohonan->nama_pemohon }}
                    </p>
                    <p style="margin-top: 0px; text-align: center;">NIP. {{ $permohonan->nip }}</p>
                </td>
            </tr>
        </tbody>
    </table>
    </div>
</body>

</html>
