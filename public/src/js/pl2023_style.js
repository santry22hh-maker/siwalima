// public/js/pl2023_style.js

function stylePL2023(feature) {
    // Ambil nilai PL2023 dari properti fitur
    const pl_code = Number(feature.properties.PL2023_ID);

    // Warna default jika kode tidak ditemukan
    let fillColor = '#808080'; // Abu-abu

    switch (pl_code) {
        case 2001:
            fillColor = 'rgb(96, 230, 99)';
            break;
        case 2002:
            fillColor = 'rgb(114, 255, 0)';
            break;
        case 2004:
            fillColor = 'rgb(142, 167, 4)';
            break;
        case 2005:
            fillColor = 'rgb(255, 211, 127)';
            break;
        case 2007:
            fillColor = 'rgb(235, 192, 167)';
            break;
        case 2010:
            fillColor = 'rgb(211, 229, 152)';
            break;
        case 2012:
            fillColor = 'rgb(0, 0, 0)'; // Hitam
            break;
        case 2014:
        case 20121: // Kode 2014 dan 20121 memiliki warna yang sama
            fillColor = 'rgb(214, 0, 115)';
            break;
        case 3000:
            fillColor = 'rgb(221, 255, 0)';
            break;
        case 5001:
            fillColor = 'rgb(235, 253, 255)';
            break;
        case 20041:
            fillColor = 'rgb(93, 167, 0)';
            break;
        case 20091:
            fillColor = 'rgb(246, 254, 167)';
            break;
        case 20092:
            fillColor = 'rgb(237, 245, 0)';
            break;
        case 20093:
            fillColor = 'rgb(168, 214, 255)';
            break;
        case 20094:
            fillColor = 'rgb(98, 237, 88)';
            break;
        case 20122:
            fillColor = 'rgb(114, 142, 167)';
            break;
        case 20141:
            fillColor = 'rgb(0, 167, 4)';
            break;
    }

    // Kembalikan objek style untuk Leaflet
    return {
        fillColor: fillColor,
        fillOpacity: 0.75,
        weight: 0.5,
        color: 'green'
    };
}