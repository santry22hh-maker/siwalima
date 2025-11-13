// Objek untuk menyimpan data style Kawasan Hutan
const kawasanHutanStyles = {
    // Anda bisa mengganti "Label untuk..." dengan deskripsi yang sebenarnya
    '100000': { color: 'rgb(173, 63, 255)', label: 'Kawasan Hutan (100000)' },
    '100210': { color: 'rgb(173, 63, 255)', label: 'Cagar Alam (100210)' },
    '100220': { color: 'rgb(173, 63, 255)', label: 'Suaka Margasatwa (100220)' },
    '100240': { color: 'rgb(173, 63, 255)', label: 'Taman Nasional (100240)' },
    '100250': { color: 'rgb(173, 63, 255)', label: 'Hutan Wisata (100250)' },
    '100251': { color: 'rgb(173, 63, 255)', label: 'Hutan Wisata Laut (100251)' },
    '100100': { color: 'rgb(2, 173, 0)', label: 'Hutan Lindung (100100)' },
    '100300': { color: 'rgb(255, 255, 0)', label: 'Hutan Produksi Tetap (100300)' },
    '100400': { color: 'rgb(138, 242, 0)', label: 'Hutan Produksi Terbatas (100400)' },
    '100500': { color: 'rgb(255, 94, 255)', label: 'Hutan Produksi yang dapat Dikonversi (100500)' },
    '100700': { color: 'rgb(255, 255, 255)', label: 'Areal Penggunaan Lain (100700)' },
    '500100': { color: 'rgb(235, 253, 255)', label: 'Tubuh Air (500100)' },
    '500300': { color: 'rgb(235, 253, 255)', label: 'Tubuh Air (500300)' },
    // ...Lanjutkan untuk semua kode FUNGSIKWS lainnya...
};

const PPTPKHStyles = {
    // Anda bisa mengganti "Label untuk..." dengan deskripsi yang sebenarnya
    'Alokasi 20% untuk kebun Masyarakat': { color: 'rgb(110, 206, 235)', label: 'Alokasi 20% untuk kebun Masyarakat' },
    'Hutan Produksi yang dapat Dikonversi (HPK) tidak produktif': { color: 'rgb(217, 154, 143)', label: 'Hutan Produksi yang dapat Dikonversi (HPK) tidak produktif' },
    'Lahan Garapan pertanian, perkebunan dan tambak': { color: 'rgb(111, 89, 240)', label: 'Lahan Garapan pertanian, perkebunan dan tambak' },
    'Permukiman transmigrasi beserta fasilitas sosial dan fasilitas umum yang sudah memperoleh persetujuan prinsip Pelepasan Kawasan Hutan untuk transmigrasi': { color: 'rgb(207, 111, 109)', label: 'Permukiman transmigrasi beserta fasilitas sosial dan fasilitas umum yang sudah memperoleh persetujuan prinsip Pelepasan Kawasan Hutan untuk transmigrasi' },
    'Permukiman, fasilitas sosial dan fasilitas umum': { color: 'rgb(191, 185, 69)', label: 'Permukiman, fasilitas sosial dan fasilitas umum' },
};

// Objek untuk menyimpan data style PL2023
const pl2023Styles = {
    '2001': { color: 'rgb(96, 230, 99)', label: 'Hutan Lahan Kering Primer (2001)' },
    '2002': { color: 'rgb(114, 255, 0)', label: 'Hutan Lahan Kering Sekunder (2002)' },
    '2004': { color: 'rgb(142, 167, 4)', label: 'Hutan Mangrove Primer (2004)' },
    '2012': { color: 'rgb(0, 0, 0)', label: 'Permukiman (2012)' },
    '2005': { color: 'rgb(255, 211, 127)', label: 'Hutan Rawa Primer (2005)' },
    '2007': { color: 'rgb(235, 192, 167)', label: 'Semak Belukar (2007)' },
    '2010': { color: 'rgb(211, 229, 152)', label: 'Hutan Tanaman (2010)' },
    '2014': { color: 'rgb(214, 0, 115)', label: 'Tanah Terbuka (2014)' },
    '20121': { color: 'rgb(214, 0, 115)', label: 'Bandar (20121)' },
    '3000': { color: 'rgb(221, 255, 0)', label: 'Savana (3000)' },
    '5001': { color: 'rgb(235, 253, 255)', label: 'Tubuh Air (5001)' },
    '20041': { color: 'rgb(93, 167, 0)', label: 'Hutan Mangrove Sekunder (20041)' },
    '20091': { color: 'rgb(246, 254, 167)', label: 'Pertanian Lahan Kering (20091)' },
    '20092': { color: 'rgb(237, 245, 0)', label: 'Pertanian Lahan Kering Campur Semak(20092)' },
    '20093': { color: 'rgb(168, 214, 255)', label: 'Sawah (20093)' },
    '20094': { color: 'rgb(98, 237, 88)', label: 'Tambak (20094)' },
    '20122': { color: 'rgb(114, 142, 167)', label: 'Transmigrasi (20122)' },
    '20141': { color: 'rgb(0, 167, 4)', label: 'Pertambangan (20141)' },

    // ...Lanjutkan untuk semua kode PL2023 lainnya...
};

// Fungsi styling yang sekarang mengambil warna dari objek di atas
function styleKawasanHutan(feature) {
    const code = feature.properties.FUNGSIKWS;
    const styleData = kawasanHutanStyles[code] || { color: '#808080' }; // Default abu-abu
    return {
        fillColor: styleData.color,
        fillOpacity: 0.7,
        weight: 1,
        color: 'green'
    };
}
// Fungsi styling yang sekarang mengambil warna dari objek di atas
function stylePPTPKH(feature) {
    const code = feature.properties.KRITERIA;
    const styleData = PPTPKHStyles[code] || { color: '#808080' }; // Default abu-abu
    return {
        fillColor: styleData.color,
        fillOpacity: 0.7,
        weight: 0,
        // color: 'green'
    };
}

function stylePL2023(feature) {
    const code = feature.properties.PL2023_ID;
    const styleData = pl2023Styles[code] || { color: '#808080' }; // Default abu-abu
    return {
        fillColor: styleData.color,
        fillOpacity: 0.75,
        weight: 0       // Tidak ada garis (ketebalan 0)
    };
}