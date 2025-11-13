document.addEventListener('DOMContentLoaded', function() {
    
    const mapElement = document.getElementById('map');
    const rawData = mapElement.dataset.usulanGeojson; 
    const laporanSlug = mapElement.dataset.slug; 
    // HAPUS: const markerIconUrl = mapElement.dataset.markerIconUrl;

    const map = L.map('map', {
        renderer: L.canvas() // Paksa Canvas Renderer (PENTING)
    }).setView([-3.69, 128.17], 9);
    
    let usulLayer, dropdownLayer, hasilLayer;
    let usulanData, dropdownData;
    
    let usulanMarkers = L.featureGroup();
    let analysisChart = null; 
    const chartContainer = document.getElementById('chart-container');
    const nextStepsDiv = document.getElementById('nextSteps'); 
    
    let activeStyleFunction = null; 
    let currentResults = []; 
    let currentTotalArea = 0;

    function invalidateMapSize() {
        setTimeout(() => map.invalidateSize(), 150);
    }

    function extractCoordinates(geometry) {
        if (!geometry || !geometry.coordinates) return [];
        if (geometry.type === 'Polygon') return geometry.coordinates[0];
        if (geometry.type === 'MultiPolygon') return geometry.coordinates[0][0];
        return [];
    }

    // Setup Basemap
    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
        attribution: '&copy; OpenStreetMap',
        print: false // WAJIB untuk leaflet-image
    }).addTo(map);
    
    const satellite = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { 
            attribution: 'Tiles &copy; Esri', 
            print: false // WAJIB untuk leaflet-image
        });

    const layerControl = L.control.layers({ "Peta Jalan": osm, "Satelit": satellite }).addTo(map);

    // Setup Layer Awal
    hasilLayer = L.geoJSON(null, { style: { color: "red", fillOpacity: 0.5 } }).addTo(map);
    layerControl.addOverlay(hasilLayer, "Hasil Analisis");

    if (rawData && rawData.trim() !== "") {
        try {
            usulanData = JSON.parse(rawData); 
            usulLayer = L.geoJSON(usulanData, {
                style: { color: "#3388ff", weight: 3 }
            }).addTo(map);
            layerControl.addOverlay(usulLayer, "Poligon Usulan");
            map.fitBounds(usulLayer.getBounds());
            
            const coordinates = extractCoordinates(usulanData.geometry);
            if (coordinates.length > 0) {
                usulanMarkers.clearLayers(); 
                coordinates.forEach((coord, index) => {
                    if (index < coordinates.length - 1) { 
                        
                        // ======================================================
                        //      PERUBAHAN: Gunakan Marker Standar Leaflet
                        // ======================================================
                        // Tidak perlu L.divIcon, cukup L.marker standar
                        const marker = L.marker([coord[1], coord[0]]); 
                        // ======================================================
                        
                        marker.bindPopup(`Titik ${index + 1}<br>Lat: ${coord[1].toFixed(6)}<br>Lon: ${coord[0].toFixed(6)}`);
                        usulanMarkers.addLayer(marker);
                    }
                });
                usulanMarkers.addTo(map); 
                layerControl.addOverlay(usulanMarkers, "Titik Usulan"); 
            }
        } catch (e) { console.error("GeoJSON usulan tidak valid:", e); }
    }
    
  
    
    invalidateMapSize();

    // Event Listener Dropdown
    document.getElementById('geojsonDropdown').addEventListener('change', function(e) {
        // ... (Logika dropdown Anda tetap sama) ...
        const option = e.target.options[e.target.selectedIndex];
        const url = option.value;
        const styleFuncName = option.dataset.style;
        if (!url) {
            if (dropdownLayer) map.removeLayer(dropdownLayer);
            dropdownLayer = null; dropdownData = null; activeStyleFunction = null;
            return;
        }
        if (styleFuncName && typeof window[styleFuncName] === 'function') {
            activeStyleFunction = window[styleFuncName];
        } else {
            activeStyleFunction = null;
        }
        let layerStyle = activeStyleFunction ? activeStyleFunction : { color: "green", weight: 2 }; 
        fetch(url).then(res => res.json()).then(data => {
            dropdownData = data;
            if (dropdownLayer) {
                map.removeLayer(dropdownLayer);
                layerControl.removeLayer(dropdownLayer); 
            }
            dropdownLayer = L.geoJSON(data, {
                style: layerStyle, 
                onEachFeature: function(feature, layer) {
                    let popupContent = '';
                    if (feature.properties.FUNGSIKWS) popupContent = `<b>Fungsi Kawasan:</b><br>${feature.properties.FUNGSIKWS}`;
                    else if (feature.properties.PL2023_ID) popupContent = `<b>Penutupan Lahan:</b><br>${feature.properties.PL2023_ID}`;
                    else popupContent = '<pre>' + JSON.stringify(feature.properties, null, 2) + '</pre>';
                    layer.bindPopup(popupContent);
                }
            }).addTo(map);
            layerControl.addOverlay(dropdownLayer, option.text);
            // if (dropdownLayer.getBounds().isValid()) map.fitBounds(dropdownLayer.getBounds());
        });
    });

    // Event Listener Tombol Analisis
    document.getElementById('btnAnalisis').addEventListener('click', function() {
        // ... (Logika analisis Anda tetap sama) ...
        if (!usulanData || !dropdownData) {
            alert("Data usulan dan data dasar (dari dropdown) harus dipilih!");
            return;
        }
        hasilLayer.clearLayers();
        if (analysisChart) {
            analysisChart.destroy();
            analysisChart = null;
        }
        chartContainer.style.display = 'none';
        nextStepsDiv.classList.add('hidden'); 
        let totalArea = 0, groupedData = {};
        const resultDiv = document.getElementById("analysisResult");
        resultDiv.innerHTML = 'Sedang menganalisis...';
        try {
            (usulanData.features || [usulanData]).forEach(u => {
                (dropdownData.features || [dropdownData]).forEach(d => {
                    const intersection = turf.intersect(u, d);
                    if (intersection) {
                        hasilLayer.addData(intersection);
                        const area = turf.area(intersection);
                        totalArea += area;
                        let key = d.properties.FUNGSIKWS || d.properties.PL2023_ID || 'Properti Tidak Dikenali';
                        if (!groupedData[key]) groupedData[key] = { properties: d.properties, totalArea: 0 };
                        groupedData[key].totalArea += area;
                    }
                });
            });
        } catch (err) {
            resultDiv.innerHTML = `<span class="text-red-600">Error saat analisis: ${err.message}</span>`;
            return;
        }
        const resultsArray = Object.values(groupedData);
        if (resultsArray.length > 0) {
            let html = `<strong>Hasil Analisis:</strong>`;
            html += `<p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Total Luas Irisan: <strong>${totalArea.toFixed(2)} m²</strong> (<strong>${(totalArea/10000).toFixed(4)} ha</strong>)</p>`;
            const firstProps = resultsArray[0].properties;
            const keys = Object.keys(firstProps);
            html += `<table><thead><tr>`;
            keys.forEach(key => { html += `<th>${key}</th>`; });
            html += `<th>Luas (m²)</th><th>Luas (ha)</th></tr></thead><tbody>`;
            resultsArray.forEach(res => {
                html += `<tr>`;
                keys.forEach(key => {
                    html += `<td>${res.properties[key] ?? 'N/A'}</td>`;
                });
                html += `<td style="text-align:right;">${res.totalArea.toFixed(2)}</td><td style="text-align:right;">${(res.totalArea/10000).toFixed(4)}</td></tr>`;
            });
            html += `</tbody></table>`;
            html += `<p class="mt-2 text-xs text-gray-600 dark:text-gray-400 italic">Luas yang ditampilkan adalah estimasi luas geodesik.</p>`;
            html += `<div class="mt-4 p-3 bg-yellow-100 dark:bg-yellow-800 border-l-4 border-yellow-500 text-yellow-800 dark:text-yellow-200 text-sm">
                        <strong>Catatan Penting:</strong><br>
                        Hasil ini merupakan analisis cepat dan tidak dapat dijadikan pegangan/rujukan resmi. Untuk hasil resmi, silakan ajukan permohonan.
                     </div>`;
            resultDiv.innerHTML = html;
            map.fitBounds(usulLayer.getBounds());
            currentResults = resultsArray;
            currentTotalArea = totalArea;
            renderAnalysisChart(resultsArray, activeStyleFunction);
            invalidateMapSize();
            nextStepsDiv.classList.remove('hidden'); 
        } else {
            resultDiv.innerHTML = `<span class="text-red-600">Tidak ada hasil irisan (intersection).</span>`;
            nextStepsDiv.classList.add('hidden'); 
        }
    });

    // Fungsi Chart
    function renderAnalysisChart(resultsArray, styleFunction) {
        // ... (Logika chart Anda tetap sama) ...
        chartContainer.style.display = 'block'; 
        const ctx = document.getElementById('analysisChartCanvas').getContext('2d');
        const labels = []; const dataPoints = []; const backgroundColors = [];
        const defaultColor = 'rgba(54, 162, 235, 0.7)'; 
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#FFF' : '#333';
        resultsArray.forEach(res => {
            labels.push(res.properties.FUNGSIKWS || res.properties.PL2023_ID || 'Data');
            dataPoints.push(res.totalArea.toFixed(2));
            let color = defaultColor;
            if (styleFunction) {
                try {
                    let dummyFeature = { properties: res.properties };
                    let styleObject = styleFunction(dummyFeature);
                    color = styleObject.fillColor || styleObject.color || defaultColor;
                } catch (e) { color = defaultColor; }
            }
            backgroundColors.push(color);
        });
        analysisChart = new Chart(ctx, {
            type: 'bar', 
            data: { labels: labels, datasets: [{ label: 'Luas (m²)', data: dataPoints, backgroundColor: backgroundColors, borderWidth: 1 }] },
            options: {
                indexAxis: 'y', responsive: true,
                scales: { x: { ticks: { color: textColor } }, y: { ticks: { color: textColor } } },
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Grafik Batang Hasil Analisis', color: textColor },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = parseFloat(context.raw) || 0;
                                let ha = (value / 10000).toFixed(4);
                                return `${label}: ${value.toFixed(2)} m² (${ha} ha)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // ======================================================
    //      PERBAIKAN: Hapus logika 'removeLayer(usulanMarkers)'
    // ======================================================
    document.getElementById('btnDownloadPdf').addEventListener('click', function() {
        if (currentResults.length === 0 || !analysisChart || !usulanData) {
            alert("Silakan jalankan analisis terlebih dahulu sebelum mengunduh PDF.");
            return;
        }

        const pdfButton = this;
        const originalButtonText = pdfButton.innerHTML;
        pdfButton.disabled = true;
        pdfButton.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Membuat PDF...`;

        // 1. HAPUS 'removeLayer' dari sini
        // if (map.hasLayer(usulanMarkers)) {
        //     map.removeLayer(usulanMarkers);
        // }

        leafletImage(map, function(err, canvas) {
            
            // 2. HAPUS 'addLayer' dari sini
            // if (usulanMarkers) {
            //     map.addLayer(usulanMarkers);
            // }
            
            if (err) {
                alert('Gagal mengambil gambar peta. Pastikan "print: false" ada di layer basemap.');
                console.error(err);
                pdfButton.disabled = false;
                pdfButton.innerHTML = originalButtonText;
                return;
            }

            // 3. Dapatkan gambar peta dari canvas
            const mapImgData = canvas.toDataURL('image/png', 0.95); 

            // ... (Sisa logika jsPDF Anda sudah benar) ...
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4'); 
            let currentY = 15;
            const pageMargin = 15;
            const pageWidth = doc.internal.pageSize.getWidth();
            const contentWidth = pageWidth - (pageMargin * 2);

            doc.setFontSize(18);
            doc.text("Hasil Analisis Cepat SI-GALIMA", pageMargin, currentY);
            currentY += 8;
            doc.setFontSize(10);
            doc.text("Laporan ini dibuat pada: " + new Date().toLocaleString('id-ID'), pageMargin, currentY);
            currentY += 8;
            doc.setFont("helvetica", "italic");
            doc.setTextColor(255, 0, 0); 
            doc.text("Catatan: Ini adalah analisis cepat dan tidak dapat dijadikan rujukan resmi.", pageMargin, currentY);
            doc.setTextColor(0, 0, 0); 
            doc.setFont("helvetica", "normal");
            currentY += 10;
            doc.setFontSize(14);
            doc.text("Peta Lokasi Analisis", pageMargin, currentY);
            currentY += 5;
            const customWidth = 100; // Lebar tetap 100mm
            const mapImgHeight = 100; // Tinggi tetap 100mm
            const mapImgProps = doc.getImageProperties(mapImgData);
            const mapImgRatio = mapImgProps.height / mapImgProps.width;
            // const mapImgHeight = contentWidth * mapImgRatio;
            doc.addImage(mapImgData, 'PNG', pageMargin, currentY, customWidth, mapImgHeight); // <-- Gunakan customWidth
        currentY += mapImgHeight + 10;
            if (currentY > 260) { doc.addPage(); currentY = 15; }
            doc.setFontSize(14);
            doc.text("Data Koordinat Usulan", 15, currentY);
            currentY += 5;
            const coords = extractCoordinates(usulanData.geometry);
            const coordTableHead = [["#", "Latitude", "Longitude"]];
            const coordTableBody = coords.map((p, index) => {
                if (index < coords.length - 1) return [index + 1, p[1].toFixed(6), p[0].toFixed(6)];
                return null;
            }).filter(row => row !== null);
            doc.autoTable({
                head: coordTableHead,
                body: coordTableBody,
                startY: currentY,
                theme: 'striped',
                headStyles: { fillColor: [100, 100, 100] } 
            });
            currentY = doc.autoTable.previous.finalY + 10; 
            if (currentY > 180) { doc.addPage(); currentY = 15; }
            const chartImgData = analysisChart.toBase64Image('image/png', 1.0);
            doc.addImage(chartImgData, 'PNG', pageMargin, currentY, contentWidth, contentWidth * 0.5); 
            currentY += (contentWidth * 0.5) + 10;
            if (currentY > 260) { doc.addPage(); currentY = 15; }
            const firstProps = currentResults[0].properties;
            const keys = Object.keys(firstProps);
            const tableHead = [ ...keys, "Luas (m²)", "Luas (ha)" ];
            const tableBody = currentResults.map(res => {
                let row = [];
                keys.forEach(key => row.push(res.properties[key] ?? 'N/A'));
                row.push(res.totalArea.toFixed(2));
                row.push((res.totalArea / 10000).toFixed(4));
                return row;
            });
            doc.autoTable({
                head: [tableHead],
                body: tableBody,
                startY: currentY, 
                theme: 'grid',
                headStyles: { fillColor: [54, 162, 235] }
            });
            currentY = doc.autoTable.previous.finalY;
            doc.setFontSize(12);
            doc.setFont("helvetica", "bold");
            let totalHa = (currentTotalArea / 10000).toFixed(4);
            doc.text(`Total Luas Irisan: ${currentTotalArea.toFixed(2)} m² (${totalHa} ha)`, 15, currentY + 10);
            doc.setFontSize(10);
            doc.setFont("helvetica", "italic");
            doc.text("Luas yang ditampilkan adalah estimasi luas geodesik.", 15, currentY + 15);
            
            doc.save(`Hasil-Analisis-SI-GALIMA-${laporanSlug}.pdf`);

            // Kembalikan tombol ke normal
            pdfButton.disabled = false;
            pdfButton.innerHTML = originalButtonText;

        }); // Akhir dari callback leafletImage
    });

});