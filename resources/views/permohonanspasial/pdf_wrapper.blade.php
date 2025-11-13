<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /* Tentukan Font & Margin Halaman */
        @page {
            /* Atas, Kanan, Bawah, Kiri */
            /* Beri ruang 3cm di Bawah untuk footer */
            margin: 1.5cm 2cm 3cm 2cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        /* Tentukan Footer */
        footer {
            position: fixed;
            bottom: -80px;
            /* Tarik ke bawah di luar margin (sesuaikan -80px jika perlu) */
            left: 0px;
            right: 0px;
            height: 100px;
            text-align: right;
        }

        /* Style lain dari template Anda */
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

        .signature-table p {
            text-align: center;
        }

        li,
        p {
            text-align: justify;
        }
    </style>
</head>

<body>
    <header>
        <table class="header-table">
            <tr>
                <td style="width: 15%; text-align: center;">
                    <img src="{{ $logoKemenhutBase64 }}" alt="Logo Kemenhut" style="width: 80px; height: auto;" />
                </td>
                <td style="width: 85%; text-align: center; line-height: 1.2;">
                    <h4 style="margin: 0; font-weight: bold;">KEMENTERIAN KEHUTANAN</h4>
                    <h4 style="margin: 0; font-weight: bold;">DIREKTORAT JENDERAL PLANOLOGI KEHUTANAN</h4>
                    <h3 style="margin: 0; font-weight: bold;">BALAI PEMANTAPAN KAWASAN HUTAN WILAYAH IX</h3>
                    <p style="margin: 0; font-size: 10pt; text-align: center;">Alamat: Jalan Kebun Cengkeh Ambon, Kode
                        Pos 97128, Telphone/Fax: (0911) 342632 Kotak Pos 1125</p>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <img src="{{ $logoIsoBase64 }}" alt="Logo ISO 9001:2015"
            style="height: 100px; width: auto; display: block; margin-left: auto;">
    </footer>

    <main>
        {!! $editorContent !!}
    </main>
</body>

</html>
