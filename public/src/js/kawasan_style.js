// public/js/kawasan_style.js

function styleKawasanHutan(feature) {
    // Ambil nilai FUNGSIKWS dari properti fitur.
    const fungsi = Number(feature.properties.FUNGSIKWS);

    // Siapkan variabel warna, dengan warna default abu-abu jika kode tidak ditemukan
    let fillColor = '#808080';

    // Gunakan switch untuk menentukan warna berdasarkan nilai 'fungsi'
    switch (fungsi) {
        case 100000:
        case 100210:
        case 100220:
        case 100240:
        case 100250:
        case 100251:
            fillColor = 'rgb(173, 63, 255)'; // Ungu
            break;
        case 100100:
            fillColor = 'rgb(2, 173, 0)'; // Hijau
            break;
        case 100300:
            fillColor = 'rgb(255, 255, 0)'; // Kuning
            break;
        case 100400:
            fillColor = 'rgb(138, 242, 0)'; // Hijau Limau
            break;
        case 100500:
            fillColor = 'rgb(255, 94, 255)'; // Merah Muda
            break;
        case 100700:
            fillColor = 'rgb(255, 255, 255)'; // Putih
            break;
        case 500100:
        case 500300:
            fillColor = 'rgb(235, 253, 255)'; // Biru Sangat Muda
            break;
    }

    // Kembalikan objek style yang akan digunakan oleh Leaflet
    return {
        fillColor: fillColor,
        fillOpacity: 0.7, // Tingkat transparansi isian
        weight: 0.5,        // Ketebalan garis batas
        color: 'green'    // Warna garis batas
    };
}