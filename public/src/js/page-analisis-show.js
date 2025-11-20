// page-analisis-combined.js
document.addEventListener('DOMContentLoaded', function () {
    // --------------------------
    // Helper: safe get element
    // --------------------------
    const $ = id => document.getElementById(id);

    // --------------------------
    // Map element & dataset
    // --------------------------
    const mapElement = $('map');
    if (!mapElement) {
        console.error('Element #map tidak ditemukan. Pastikan blade Anda punya <div id="map">.');
        return;
    }
    const rawData = mapElement.dataset.usulanGeojson || '';
    const laporanSlug = mapElement.dataset.slug || 'laporan';

    // --------------------------
    // Inisialisasi Map + Layers
    // --------------------------
    const map = L.map('map', { renderer: L.canvas() }).setView([-3.69, 128.17], 9);

    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
        print: false // penting untuk leaflet-image
    }).addTo(map);

    const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri',
        print: false
    });

    const topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { attribution: 'Topography' });

    const baseMaps = { "Peta Jalan": osm, "Citra Satelit": satellite, "Topografi": topo };

    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const layerControl = L.control.layers(baseMaps, { "Area Usulan": drawnItems }, { position: 'topright' }).addTo(map);

    // overlay awal untuk hasil analisis
    const hasilLayer = L.geoJSON(null, { style: { color: "red", fillOpacity: 0.5 } }).addTo(map);
    layerControl.addOverlay(hasilLayer, "Hasil Analisis");

    // Layers Data Dasar (opsional, akan dimuat dari asset jika tersedia)
    let kawasanHutanLayer = null, pl2023Layer = null;
    fetchIfExists = (url, styleFn, label) => {
        if (!url) return;
        fetch(url).then(r => r.json()).then(data => {
            const lyr = L.geoJSON(data, {
                style: styleFn || { color: "#666", weight: 1 },
                onEachFeature: (f, ly) => {
                    if (f.properties) {
                        const popup = Object.keys(f.properties).slice(0,5).map(k => `<b>${k}:</b> ${f.properties[k]}`).join('<br>');
                        ly.bindPopup(popup);
                    }
                }
            });
            layerControl.addOverlay(lyr, label);
            // store by label
            if (label && label.toLowerCase().includes('kawasan')) kawasanHutanLayer = lyr;
            if (label && label.toLowerCase().includes('2023')) pl2023Layer = lyr;
        }).catch(e => {/* no-op */});
    };
    // contoh fetch file jika path Anda sama seperti sebelumnya:
    fetchIfExists('/DataDasar/KwsHutan_Maluku250.geojson', window.styleKawasanHutan, "Kawasan Hutan Maluku");
    fetchIfExists('/DataDasar/Pl2023_Maluku250.geojson', window.stylePL2023, "Tutupan Lahan 2023");

    // --------------------------
    // Controls: Legend (dinamis)
    // --------------------------
    function updateLegend() {
        const legendContentDiv = document.querySelector('.legend-content');
        if (!legendContentDiv) return;
        let content = '<h4 style="cursor:pointer" title="Sembunyikan Legenda">Legenda &#9660;</h4>';
        let has = false;
        if (kawasanHutanLayer && map.hasLayer(kawasanHutanLayer) && window.kawasanHutanStyles) {
            content += '<b>Kawasan Hutan</b><br>';
            for (const k in window.kawasanHutanStyles) {
                content += `<i style="background:${window.kawasanHutanStyles[k].color}"></i> ${window.kawasanHutanStyles[k].label}<br>`;
            }
            has = true;
        }
        if (pl2023Layer && map.hasLayer(pl2023Layer) && window.pl2023Styles) {
            content += '<br><b>Tutupan Lahan</b><br>';
            for (const k in window.pl2023Styles) {
                content += `<i style="background:${window.pl2023Styles[k].color}"></i> ${window.pl2023Styles[k].label}<br>`;
            }
            has = true;
        }
        legendContentDiv.innerHTML = content;
        if (has) {
            L.DomEvent.on(legendContentDiv.querySelector('h4'), 'click', e => L.DomEvent.stop(e) && L.DomUtil.removeClass(legendContentDiv.parentElement, 'active'));
        }
    }

    const legend = L.control({ position: 'bottomright' });
    legend.onAdd = function (map) {
        const container = L.DomUtil.create('div', 'leaflet-control leaflet-bar legend-control-container');
        const button = L.DomUtil.create('a', 'legend-toggle-button', container);
        button.innerHTML = 'i';
        button.href = '#';
        button.title = 'Tampilkan Legenda';
        L.DomUtil.create('div', 'legend-content', container);
        L.DomEvent.on(button, 'click', e => {
            L.DomEvent.stop(e);
            L.DomUtil.addClass(container, 'active');
            updateLegend();
        });
        L.DomEvent.disableClickPropagation(container);
        return container;
    };
    legend.addTo(map);
    map.on('overlayadd overlayremove', updateLegend);

    // --------------------------
    // Utility: show/hide error
    // --------------------------
    const jsErrorBox = $('js-error-box'), jsErrorMessage = $('js-error-message');
    function showError(msg) { if (jsErrorMessage) jsErrorMessage.textContent = msg; if (jsErrorBox) jsErrorBox.classList.remove('hidden'); }
    function hideError() { if (jsErrorMessage) jsErrorMessage.textContent = ''; if (jsErrorBox) jsErrorBox.classList.add('hidden'); }

    // --------------------------
    // DOM elements used
    // --------------------------
    const shapefileUpload = $('shapefile-upload');
    const photoUpload = $('photo-upload');
    const manualCoords = $('manual-coords');
    const btnPreviewManual = $('btn-preview-manual');
    const geojsonField = $('geojson_data');
    const luasInfo = $('luas-info');
    const btnClearMap = $('btn-clear-map');
    const geojsonDropdown = $('geojsonDropdown');
    const btnAnalisis = $('btnAnalisis');
    const analysisResult = $('analysisResult');
    const chartContainer = $('chart-container');
    const nextStepsDiv = $('nextSteps');
    const btnDownloadPdf = $('btnDownloadPdf');
    const btnSubmit = $('btn-submit');
    const btnSubmitText = $('btn-submit-text');

    let usulanData = null, dropdownData = null, usulLayer = null, dropdownLayer = null;
    let usulanMarkers = L.featureGroup();
    let analysisChart = null;
    let activeStyleFunction = null;
    let currentResults = [], currentTotalArea = 0;

    // small helper to invalidate size
    function invalidateMapSize() { setTimeout(() => map.invalidateSize(), 150); }

    // --------------------------
    // Draw control (leaflet.draw)
    // --------------------------
    const drawControl = new L.Control.Draw({
        edit: { featureGroup: drawnItems },
        draw: {
            polygon: { allowIntersection: false, showArea: true },
            polyline: false, rectangle: true, circle: false, marker: false
        }
    });
    map.addControl(drawControl);

    map.on(L.Draw.Event.CREATED, e => {
        hideError();
        drawnItems.clearLayers();
        drawnItems.addLayer(e.layer);
        const geo = e.layer.toGeoJSON();
        if (geojsonField) geojsonField.value = JSON.stringify(geo.geometry);
        calculateAndDisplayArea(geo);
        if (btnSubmit) btnSubmit.disabled = false;
    });
    map.on(L.Draw.Event.EDITED, e => e.layers.eachLayer(l => {
        if (geojsonField) geojsonField.value = JSON.stringify(l.toGeoJSON().geometry);
        calculateAndDisplayArea(l.toGeoJSON());
    }));
    map.on(L.Draw.Event.DELETED, () => {
        if (drawnItems.getLayers().length === 0) {
            if (geojsonField) geojsonField.value = '';
            if (btnSubmit) btnSubmit.disabled = true;
        }
    });

    // --------------------------
    // Pemrosesan area (turf)
    // --------------------------
    function calculateAndDisplayArea(geojsonFeature) {
        if (!geojsonFeature) {
            if (luasInfo) luasInfo.innerHTML = '-';
            return;
        }
        try {
            const areaInMeters = turf.area(geojsonFeature);
            const areaInHectares = areaInMeters / 10000;
            const formattedMeters = areaInMeters.toLocaleString('id-ID', { maximumFractionDigits: 2 });
            const formattedHectares = areaInHectares.toLocaleString('id-ID', { maximumFractionDigits: 4 });
            if (luasInfo) luasInfo.innerHTML = `<strong>${formattedHectares} ha</strong> (${formattedMeters} m²)`;
        } catch (e) {
            if (luasInfo) luasInfo.innerHTML = '<span class="text-red-500">Gagal menghitung luas.</span>';
        }
    }

    // --------------------------
    // EXIF DMS -> decimal
    // --------------------------
    function convertDMSToDD(dms, ref) {
        if (!dms || !dms.length) return null;
        const dd = dms[0] + dms[1]/60 + dms[2]/3600;
        return (ref === 'S' || ref === 'W') ? -dd : dd;
    }

    // --------------------------
    // Foto geotag -> polygon preview
    // --------------------------
    if (photoUpload) {
        photoUpload.addEventListener('change', async function (e) {
            hideError();
            drawnItems.clearLayers();
            if (!e.target.files || e.target.files.length === 0) {
                clearPreview();
                return;
            }
            const files = Array.from(e.target.files);
            if (files.length < 3) {
                showError('Silakan pilih minimal 3 foto.');
                e.target.value = '';
                return;
            }
            try {
                const coords = [];
                // EXIF.getData is async/callback-based; wrap per file
                for (const file of files) {
                    await new Promise((resolve) => {
                        EXIF.getData(file, function () {
                            const lat = EXIF.getTag(this, "GPSLatitude");
                            const lon = EXIF.getTag(this, "GPSLongitude");
                            const latRef = EXIF.getTag(this, "GPSLatitudeRef") || 'N';
                            const lonRef = EXIF.getTag(this, "GPSLongitudeRef") || 'E';
                            if (lat && lon) {
                                const latDD = convertDMSToDD(lat, latRef);
                                const lonDD = convertDMSToDD(lon, lonRef);
                                // Leaflet polygon expects [lat, lon]
                                coords.push([latDD, lonDD]);
                            }
                            resolve();
                        });
                    });
                }
                if (coords.length < 3) {
                    showError('Tidak cukup foto ber-geotag yang valid untuk membuat poligon.');
                    clearPreview();
                    return;
                }
                // close polygon
                // L.polygon will auto close; but ensure first==last for GeoJSON creation if needed
                const polygon = L.polygon(coords, { color: '#22c55e', weight: 3, fillOpacity: 0.5 }).addTo(drawnItems);
                map.fitBounds(polygon.getBounds());
                const geojson = polygon.toGeoJSON();
                if (geojsonField) geojsonField.value = JSON.stringify(geojson.geometry);
                calculateAndDisplayArea(geojson);
                if (btnSubmit) btnSubmit.disabled = false;
            } catch (err) {
                console.error(err);
                showError('Gagal memproses foto: ' + (err.message || err));
                clearPreview();
            } finally {
                invalidateMapSize();
            }
        });
    }

    // --------------------------
    // Shapefile upload -> preview
    // --------------------------
    if (shapefileUpload) {
        shapefileUpload.addEventListener('change', async function (e) {
            hideError();
            drawnItems.clearLayers();
            const f = e.target.files && e.target.files[0];
            if (!f) {
                clearPreview();
                return;
            }
            try {
                const buffer = await f.arrayBuffer();
                const gj = await shp(buffer); // shp.js
                // shp() may return FeatureCollection or Feature
                const layer = L.geoJSON(gj, { style: { color: '#2563EB', weight: 2 } }).addTo(drawnItems);
                map.fitBounds(layer.getBounds());
                // store combined geometry (if FeatureCollection take first polygon/multipolygon)
                // We'll convert entire geojson where possible
                if (geojsonField) geojsonField.value = JSON.stringify(gj.type === 'FeatureCollection' ? gj.features[0].geometry : (gj.geometry || gj));
                calculateAndDisplayArea(layer.toGeoJSON());
                if (btnSubmit) btnSubmit.disabled = false;
            } catch (err) {
                console.error(err);
                showError('Gagal memproses shapefile: ' + (err.message || err));
                clearPreview();
            } finally {
                invalidateMapSize();
            }
        });
    }

    // --------------------------
    // Manual coords -> preview
    // --------------------------
    if (btnPreviewManual) {
        btnPreviewManual.addEventListener('click', function () {
            hideError();
            drawnItems.clearLayers();
            const text = (manualCoords && manualCoords.value) ? manualCoords.value.trim() : '';
            if (!text) {
                showError('Koordinat kosong.');
                return;
            }
            try {
                const coords = text.split('\n').map(line => {
                    const parts = line.split(',').map(p => parseFloat(p.trim()));
                    if (parts.length !== 2 || isNaN(parts[0]) || isNaN(parts[1])) throw new Error('Format salah: ' + line);
                    // assume input format is "lat, lon" (blade placeholder suggests lat,long)
                    return [parts[0], parts[1]]; // [lat, lon]
                }).filter(c => c && c.length === 2);
                if (coords.length < 3) {
                    showError('Minimal 3 titik koordinat.');
                    return;
                }
                const polygon = L.polygon(coords, { color: '#1e40af', weight: 3, fillOpacity: 0.4 }).addTo(drawnItems);
                map.fitBounds(polygon.getBounds());
                const geojson = polygon.toGeoJSON();
                if (geojsonField) geojsonField.value = JSON.stringify(geojson.geometry);
                calculateAndDisplayArea(geojson);
                if (btnSubmit) btnSubmit.disabled = false;
            } catch (err) {
                console.error(err);
                showError('Gagal memproses input: ' + (err.message || err));
                clearPreview();
            }
            invalidateMapSize();
        });
    }

    // --------------------------
    // Clear preview helper
    // --------------------------
    function clearPreview() {
        hideError();
        drawnItems.clearLayers();
        if (geojsonField) geojsonField.value = '';
        if (btnSubmit) btnSubmit.disabled = true;
        if (shapefileUpload) shapefileUpload.value = '';
        if (photoUpload) photoUpload.value = '';
        if (manualCoords) manualCoords.value = '';
        if ( $('file-info-content') ) {
            $('file-info-content').textContent = 'Belum ada file';
            $('file-info-content').classList.add('text-gray-500', 'italic');
        }
        if (luasInfo) luasInfo.innerHTML = '-';
        // if there are inputs with ids below in your form, clear them (from original blade)
        ['lokasi','kabupaten','keterangan','userid','groupid'].forEach(id => {
            if ($(id)) $(id).value = '';
        });
        invalidateMapSize();
    }

    if (btnClearMap) btnClearMap.addEventListener('click', clearPreview);

    // --------------------------
    // Load existing usulan (rawData) if ada
    // --------------------------
    function extractCoordinates(geometry) {
        if (!geometry || !geometry.coordinates) return [];
        if (geometry.type === 'Polygon') return geometry.coordinates[0];
        if (geometry.type === 'MultiPolygon') return geometry.coordinates[0][0];
        return [];
    }

    if (rawData && rawData.trim() !== '') {
        try {
            usulanData = JSON.parse(rawData);
            usulLayer = L.geoJSON(usulanData, { style: { color: "#3388ff", weight: 3 } }).addTo(map);
            layerControl.addOverlay(usulLayer, "Poligon Usulan");
            if (usulLayer && usulLayer.getBounds && usulLayer.getBounds().isValid()) map.fitBounds(usulLayer.getBounds());
            const coords = extractCoordinates(usulanData.geometry);
            if (coords && coords.length > 0) {
                usulanMarkers.clearLayers();
                coords.forEach((c, i) => {
                    if (i < coords.length - 1) {
                        const marker = L.marker([c[1], c[0]]);
                        marker.bindPopup(`Titik ${i+1}<br>Lat: ${c[1].toFixed(6)}<br>Lon: ${c[0].toFixed(6)}`);
                        usulanMarkers.addLayer(marker);
                    }
                });
                usulanMarkers.addTo(map);
                layerControl.addOverlay(usulanMarkers, "Titik Usulan");
            }
        } catch (err) { console.error('Error parse usulan GeoJSON:', err); }
    }

    invalidateMapSize();

    // --------------------------
    // Dropdown geojson selection
    // --------------------------
    if (geojsonDropdown) {
        geojsonDropdown.addEventListener('change', function (e) {
            const option = e.target.options[e.target.selectedIndex];
            const url = option.value;
            const styleFuncName = option.dataset.style;
            if (!url) {
                if (dropdownLayer) { map.removeLayer(dropdownLayer); layerControl.removeLayer(dropdownLayer); dropdownLayer = null; dropdownData = null; activeStyleFunction = null; }
                return;
            }
            if (styleFuncName && typeof window[styleFuncName] === 'function') activeStyleFunction = window[styleFuncName];
            else activeStyleFunction = null;
            const layerStyle = activeStyleFunction ? activeStyleFunction : { color: "green", weight: 2 };
            fetch(url).then(r => r.json()).then(data => {
                dropdownData = data;
                if (dropdownLayer) { map.removeLayer(dropdownLayer); layerControl.removeLayer(dropdownLayer); }
                dropdownLayer = L.geoJSON(data, {
                    style: typeof layerStyle === 'function' ? layerStyle : () => layerStyle,
                    onEachFeature: (feature, layer) => {
                        let popup = '';
                        if (feature.properties?.FUNGSIKWS) popup = `<b>Fungsi Kawasan:</b><br>${feature.properties.FUNGSIKWS}`;
                        else if (feature.properties?.PL2023_ID) popup = `<b>Penutupan Lahan:</b><br>${feature.properties.PL2023_ID}`;
                        else popup = '<pre>' + JSON.stringify(feature.properties, null, 2) + '</pre>';
                        layer.bindPopup(popup);
                    }
                }).addTo(map);
                layerControl.addOverlay(dropdownLayer, option.text);
            }).catch(err => {
                console.error('Gagal memuat dropdown geojson:', err);
                showError('Gagal memuat data dasar dari pilihan.');
            });
        });
    }

    // --------------------------
    // Analysis (intersection) + chart
    // --------------------------
    if (btnAnalisis) {
        btnAnalisis.addEventListener('click', function () {
            if (!usulanData || !dropdownData) {
                alert('Data usulan dan data dasar (dari dropdown) harus dipilih!');
                return;
            }
            hasilLayer.clearLayers();
            if (analysisChart) { analysisChart.destroy(); analysisChart = null; }
            if (chartContainer) chartContainer.style.display = 'none';
            if (nextStepsDiv) nextStepsDiv.classList.add('hidden');
            let totalArea = 0, grouped = {};
            if (!analysisResult) return;
            analysisResult.innerHTML = 'Sedang menganalisis...';
            try {
                const usulanFeatures = usulanData.features || (usulanData.type ? [usulanData] : []);
                const dasarFeatures = dropdownData.features || (dropdownData.type ? [dropdownData] : []);
                usulanFeatures.forEach(u => {
                    dasarFeatures.forEach(d => {
                        const inter = turf.intersect(u, d);
                        if (inter) {
                            hasilLayer.addData(inter);
                            const area = turf.area(inter);
                            totalArea += area;
                            const key = d.properties?.FUNGSIKWS || d.properties?.PL2023_ID || 'Properti Tidak Dikenali';
                            if (!grouped[key]) grouped[key] = { properties: d.properties, totalArea: 0 };
                            grouped[key].totalArea += area;
                        }
                    });
                });
            } catch (err) {
                analysisResult.innerHTML = `<span class="text-red-600">Error saat analisis: ${err.message}</span>`;
                return;
            }

            const resultsArray = Object.values(grouped);
            if (resultsArray.length > 0) {
                let html = `<strong>Hasil Analisis:</strong>`;
                html += `<p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Total Luas Irisan: <strong>${totalArea.toFixed(2)} m²</strong> (<strong>${(totalArea/10000).toFixed(4)} ha</strong>)</p>`;
                const firstProps = resultsArray[0].properties || {};
                const keys = Object.keys(firstProps);
                html += `<table><thead><tr>`;
                keys.forEach(key => html += `<th>${key}</th>`);
                html += `<th>Luas (m²)</th><th>Luas (ha)</th></tr></thead><tbody>`;
                resultsArray.forEach(res => {
                    html += `<tr>`;
                    keys.forEach(k => html += `<td>${res.properties[k] ?? 'N/A'}</td>`);
                    html += `<td style="text-align:right;">${res.totalArea.toFixed(2)}</td><td style="text-align:right;">${(res.totalArea/10000).toFixed(4)}</td></tr>`;
                });
                html += `</tbody></table>`;
                html += `<p class="mt-2 text-xs text-gray-600 dark:text-gray-400 italic">Luas yang ditampilkan adalah estimasi luas geodesik.</p>`;
                html += `<div class="mt-4 p-3 bg-yellow-100 dark:bg-yellow-800 border-l-4 border-yellow-500 text-yellow-800 dark:text-yellow-200 text-sm">
                            <strong>Catatan Penting:</strong><br>
                            Hasil ini merupakan analisis cepat dan tidak dapat dijadikan pegangan/rujukan resmi. Untuk hasil resmi, silakan ajukan permohonan.
                         </div>`;
                analysisResult.innerHTML = html;
                if (usulLayer && usulLayer.getBounds && usulLayer.getBounds().isValid()) map.fitBounds(usulLayer.getBounds());
                currentResults = resultsArray;
                currentTotalArea = totalArea;
                renderAnalysisChart(resultsArray, activeStyleFunction);
                invalidateMapSize();
                if (nextStepsDiv) nextStepsDiv.classList.remove('hidden');
            } else {
                analysisResult.innerHTML = `<span class="text-red-600">Tidak ada hasil irisan (intersection).</span>`;
                if (nextStepsDiv) nextStepsDiv.classList.add('hidden');
            }
        });
    }

    function renderAnalysisChart(resultsArray, styleFunction) {
        if (!chartContainer) return;
        chartContainer.style.display = 'block';
        const canvas = $('analysisChartCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const labels = [], dataPoints = [], backgroundColors = [];
        const defaultColor = 'rgba(54, 162, 235, 0.7)';
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#FFF' : '#333';
        resultsArray.forEach(res => {
            labels.push(res.properties?.FUNGSIKWS || res.properties?.PL2023_ID || 'Data');
            dataPoints.push(parseFloat(res.totalArea.toFixed(2)));
            let color = defaultColor;
            if (styleFunction) {
                try {
                    const styleObj = styleFunction({ properties: res.properties });
                    color = styleObj.fillColor || styleObj.color || defaultColor;
                } catch (e) { color = defaultColor; }
            }
            backgroundColors.push(color);
        });
        if (analysisChart) analysisChart.destroy();
        analysisChart = new Chart(ctx, {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Luas (m²)', data: dataPoints, backgroundColor: backgroundColors, borderWidth: 1 }] },
            options: {
                indexAxis: 'y', responsive: true,
                scales: { x: { ticks: { color: textColor } }, y: { ticks: { color: textColor } } },
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Grafik Batang Hasil Analisis', color: textColor },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const value = parseFloat(context.raw) || 0;
                                const ha = (value / 10000).toFixed(4);
                                return `${context.label}: ${value.toFixed(2)} m² (${ha} ha)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // --------------------------
    // PDF export (leafletImage + jsPDF)
    // --------------------------
    if (btnDownloadPdf) {
        btnDownloadPdf.addEventListener('click', function () {
            if (currentResults.length === 0 || !analysisChart || !usulanData) {
                alert('Silakan jalankan analisis terlebih dahulu sebelum mengunduh PDF.');
                return;
            }
            const pdfButton = this;
            const original = pdfButton.innerHTML;
            pdfButton.disabled = true;
            pdfButton.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Membuat PDF...`;
            leafletImage(map, function (err, canvas) {
                if (err) {
                    alert('Gagal mengambil gambar peta. Pastikan layer basemap memiliki option print:false.');
                    console.error(err);
                    pdfButton.disabled = false; pdfButton.innerHTML = original;
                    return;
                }
                const mapImgData = canvas.toDataURL('image/png', 0.95);
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('p', 'mm', 'a4');
                let y = 15; const margin = 15;
                const pageW = doc.internal.pageSize.getWidth();
                const contentW = pageW - margin*2;

                doc.setFontSize(18); doc.text("Hasil Analisis Cepat SI-GALIMA", margin, y); y += 8;
                doc.setFontSize(10); doc.text("Laporan ini dibuat pada: " + new Date().toLocaleString('id-ID'), margin, y); y += 8;
                doc.setFont("helvetica","italic"); doc.setTextColor(255,0,0);
                doc.text("Catatan: Ini adalah analisis cepat dan tidak dapat dijadikan rujukan resmi.", margin, y);
                doc.setTextColor(0,0,0); doc.setFont("helvetica","normal"); y += 10;
                doc.setFontSize(14); doc.text("Peta Lokasi Analisis", margin, y); y += 5;
                const imgW = 100, imgH = 100;
                doc.addImage(mapImgData, 'PNG', margin, y, imgW, imgH); y += imgH + 10;
                if (y > 260) { doc.addPage(); y = 15; }
                doc.setFontSize(14); doc.text("Data Koordinat Usulan", margin, y); y += 5;
                const coords = extractCoordinates(usulanData.geometry);
                const coordHead = [["#", "Latitude", "Longitude"]];
                const coordBody = coords.map((p, idx) => idx < coords.length - 1 ? [idx+1, p[1].toFixed(6), p[0].toFixed(6)] : null).filter(r => r);
                doc.autoTable({ head: coordHead, body: coordBody, startY: y, theme: 'striped', headStyles: { fillColor: [100,100,100] } });
                y = doc.autoTable.previous.finalY + 10;
                if (y > 180) { doc.addPage(); y = 15; }
                const chartImg = analysisChart.toBase64Image('image/png', 1.0);
                doc.addImage(chartImg, 'PNG', margin, y, contentW, contentW * 0.5); y += (contentW*0.5) + 10;
                if (y > 260) { doc.addPage(); y = 15; }
                const firstProps = currentResults[0].properties || {};
                const keys = Object.keys(firstProps);
                const tableHead = [...keys, "Luas (m²)", "Luas (ha)"];
                const tableBody = currentResults.map(res => {
                    const row = [];
                    keys.forEach(k => row.push(res.properties[k] ?? 'N/A'));
                    row.push(res.totalArea.toFixed(2));
                    row.push((res.totalArea/10000).toFixed(4));
                    return row;
                });
                doc.autoTable({ head: [tableHead], body: tableBody, startY: y, theme: 'grid', headStyles: { fillColor: [54,162,235] } });
                y = doc.autoTable.previous.finalY;
                doc.setFontSize(12); doc.setFont("helvetica","bold");
                let totalHa = (currentTotalArea/10000).toFixed(4);
                doc.text(`Total Luas Irisan: ${currentTotalArea.toFixed(2)} m² (${totalHa} ha)`, margin, y + 10);
                doc.setFontSize(10); doc.setFont("helvetica","italic");
                doc.text("Luas yang ditampilkan adalah estimasi luas geodesik.", margin, y + 15);
                doc.save(`Hasil-Analisis-SI-GALIMA-${laporanSlug}.pdf`);
                pdfButton.disabled = false; pdfButton.innerHTML = original;
            });
        });
    }

    // Done: ensure map redraw
    invalidateMapSize();
});
