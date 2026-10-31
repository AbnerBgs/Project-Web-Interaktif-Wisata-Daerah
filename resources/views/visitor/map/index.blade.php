@extends('visitor.layouts.app')
@section('title', __('map.title') . ' — WisataDaerah')

@push('styles')
<style>
    /* Map container — full height minus navbar */
    #mainMap { height: calc(100vh - 64px); }
    /* Sidebar panel */
    #mapSidebar { width: 320px; min-width: 280px; }
    /* Custom marker popup */
    .popup-card { width: 220px; }
    .popup-card img { width: 100%; height: 120px; object-fit: cover; border-radius: 8px 8px 0 0; }
    .popup-card-body { padding: 10px 12px 12px; }
    .popup-card-title { font-weight: 600; font-size: 14px; color: #1f2937; margin-bottom: 4px; }
    .popup-card-meta  { font-size: 12px; color: #6b7280; margin-bottom: 6px; }
    .popup-card-stars { color: #f59e0b; font-size: 11px; }
    .popup-card-btn {
        display: block; margin-top: 8px; text-align: center;
        background: #0ea5e9; color: white; border-radius: 8px;
        padding: 6px; font-size: 12px; font-weight: 600; text-decoration: none;
    }
    .popup-card-btn:hover { background: #0284c7; }
</style>
@endpush

@section('content')
<div class="flex h-[calc(100vh-64px)] overflow-hidden">

    {{-- ── Left Sidebar: Filter & Nearby ──────────────────────── --}}
    <div id="mapSidebar" class="bg-white border-r border-gray-100 flex flex-col overflow-hidden shadow-sm z-10">
        <div class="p-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-sky-500"></i>
                {{ __('map.filter_title') }}
            </h2>
        </div>

        <div class="p-4 space-y-3 overflow-y-auto flex-1">
            {{-- Filter Kategori --}}
            <div>
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">
                    {{ __('map.category') }}
                </label>
                <select id="filterCategory"
                        class="w-full border border-gray-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">{{ __('map.all_categories') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->localized_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Wilayah --}}
            <div>
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">
                    {{ __('map.region') }}
                </label>
                <select id="filterRegion"
                        class="w-full border border-gray-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">{{ __('map.all_regions') }}</option>
                    @foreach($regions as $region)
                        <option value="{{ $region }}">{{ $region }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol filter --}}
            <button id="applyFilter"
                    class="w-full bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors">
                <i class="fa-solid fa-filter mr-2"></i>{{ __('map.apply_filter') }}
            </button>

            <div class="border-t border-gray-100 pt-3">
                {{-- Wisata Terdekat (Geolocation) --}}
                <button id="nearbyBtn"
                        class="w-full border-2 border-sky-500 text-sky-600 hover:bg-sky-500 hover:text-white text-sm font-semibold py-2.5 rounded-xl transition-all duration-200">
                    <i class="fa-solid fa-location-crosshairs mr-2"></i>{{ __('map.nearby_btn') }}
                </button>
                <p id="nearbyStatus" class="text-xs text-gray-400 text-center mt-2 hidden"></p>
            </div>
        </div>

        {{-- Hasil nearby / jumlah marker --}}
        <div class="p-4 border-t border-gray-100">
            <p id="markerCount" class="text-xs text-gray-500 text-center">
                <i class="fa-solid fa-circle-info text-sky-400 mr-1"></i>
                {{ __('map.loading') }}
            </p>
        </div>
    </div>

    {{-- ── Peta Leaflet ──────────────────────────────────────── --}}
    <div class="flex-1 relative">
        <div id="mainMap" class="w-full h-full"></div>

        {{-- Tombol toggle sidebar (mobile) --}}
        <button id="toggleSidebar"
                class="absolute top-3 right-3 z-[1000] bg-white shadow-md rounded-xl px-3 py-2 text-sm font-semibold text-gray-600 flex items-center gap-2 md:hidden">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
/**
 * ══════════════════════════════════════════════════════════════════
 * LEAFLET MAP — Peta Interaktif Utama
 * Fitur: Marker Clustering, Dynamic Filter (AJAX), Geolocation Nearby
 * ══════════════════════════════════════════════════════════════════
 */

// ── 1. Inisialisasi Map ──────────────────────────────────────────
// Center default: Yogyakarta
const map = L.map('mainMap').setView([-7.7956, 110.3695], 9);

// Tile layer OpenStreetMap (bebas biaya API)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 19,
}).addTo(map);

// ── 2. Marker Cluster Group ──────────────────────────────────────
// Agar tampilan rapi saat ada banyak marker (menggunakan plugin MarkerCluster)
const clusterGroup = L.markerClusterGroup({
    maxClusterRadius: 60,
    spiderfyOnMaxZoom: true,
    showCoverageOnHover: false,
    iconCreateFunction: function (cluster) {
        const count = cluster.getChildCount();
        const size  = count < 10 ? 36 : count < 50 ? 44 : 52;
        return L.divIcon({
            html: `<div style="
                width:${size}px; height:${size}px; border-radius:50%;
                background:#0ea5e9; color:white; font-weight:700;
                display:flex; align-items:center; justify-content:center;
                font-size:${size < 44 ? 13 : 15}px;
                box-shadow: 0 4px 12px rgba(14,165,233,0.4);
                border: 3px solid white;">
                ${count}
            </div>`,
            className: '',
            iconSize: [size, size],
        });
    },
});
map.addLayer(clusterGroup);

// ── 3. Buat Custom Marker Icon ────────────────────────────────────
function createMarkerIcon(color = '#3b82f6') {
    return L.divIcon({
        html: `<div style="
            width:32px; height:32px; border-radius:50% 50% 50% 0;
            transform: rotate(-45deg); background:${color};
            box-shadow: 0 4px 10px rgba(0,0,0,.25);
            border: 3px solid white; cursor: pointer;">
        </div>`,
        className: '',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -34],
    });
}

// ── 4. Load & Render Markers dari API ────────────────────────────
function loadMarkers(params = {}) {
    clusterGroup.clearLayers(); // Hapus marker lama

    const qs = new URLSearchParams(params).toString();
    document.getElementById('markerCount').innerHTML =
        '<i class="fa-solid fa-spinner fa-spin text-sky-400 mr-1"></i> Memuat data...';

    fetch(`/api/map/markers?${qs}`)
        .then(r => r.json())
        .then(geojson => {
            const count = geojson.features.length;

            geojson.features.forEach(feature => {
                const { geometry, properties } = feature;
                const [lng, lat] = geometry.coordinates;
                const stars = '★'.repeat(properties.rating) + '☆'.repeat(5 - properties.rating);

                // Buat marker dengan icon custom berwarna sesuai kategori
                const marker = L.marker([lat, lng], {
                    icon: createMarkerIcon(properties.category_color),
                });

                // Popup berisi: foto, nama, rating, tombol detail
                const popupHtml = `
                    <div class="popup-card">
                        <img src="${properties.thumbnail}" alt="${properties.name}"
                             onerror="this.src='/images/placeholder.jpg'">
                        <div class="popup-card-body">
                            <div class="popup-card-title">${properties.name}</div>
                            <div class="popup-card-meta">
                                <i class="fa-solid fa-location-dot" style="color:#0ea5e9;"></i>
                                ${properties.region}
                            </div>
                            <div class="popup-card-stars">
                                ${stars}
                                <span style="color:#6b7280;font-size:11px;">(${properties.reviews})</span>
                            </div>
                            <a href="${properties.detail_url}" class="popup-card-btn">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>`;

                marker.bindPopup(popupHtml, {
                    maxWidth: 240,
                    minWidth: 220,
                    className: 'leaflet-popup-clean',
                });

                clusterGroup.addLayer(marker);
            });

            document.getElementById('markerCount').textContent =
                `${count} destinasi ditampilkan`;
        })
        .catch(() => {
            document.getElementById('markerCount').textContent = 'Gagal memuat data.';
        });
}

// ── 5. Filter Dinamis ─────────────────────────────────────────────
document.getElementById('applyFilter').addEventListener('click', function () {
    const params = {};
    const cat    = document.getElementById('filterCategory').value;
    const region = document.getElementById('filterRegion').value;
    if (cat)    params.category_id = cat;
    if (region) params.region      = region;
    loadMarkers(params);
});

// ── 6. Wisata Terdekat — Geolocation API ─────────────────────────
document.getElementById('nearbyBtn').addEventListener('click', function () {
    const status = document.getElementById('nearbyStatus');

    if (!navigator.geolocation) {
        status.textContent = 'Browser tidak mendukung Geolocation.';
        status.classList.remove('hidden');
        return;
    }

    status.textContent = 'Mendeteksi lokasi Anda...';
    status.classList.remove('hidden');
    this.disabled = true;

    navigator.geolocation.getCurrentPosition(
        function (pos) {
            const { latitude: lat, longitude: lng } = pos.coords;

            // Tandai posisi user di peta
            L.circleMarker([lat, lng], {
                radius: 10, color: '#0ea5e9', fillColor: '#bae6fd',
                fillOpacity: 0.7, weight: 2,
            }).addTo(map).bindPopup('<b>Lokasi Anda</b>').openPopup();

            // Zoom ke lokasi user
            map.setView([lat, lng], 13);

            // Ambil destinasi terdekat (radius 20 km)
            fetch(`/api/destinations/nearby?lat=${lat}&lng=${lng}&radius=20&limit=8`)
                .then(r => r.json())
                .then(({ data }) => {
                    clusterGroup.clearLayers();

                    data.forEach(d => {
                        const marker = L.marker([d.lat, d.lng], {
                            icon: createMarkerIcon('#0ea5e9'),
                        });
                        marker.bindPopup(`
                            <div class="popup-card-body" style="min-width:180px;">
                                <div class="popup-card-title">${d.name}</div>
                                <div class="popup-card-meta" style="color:#0ea5e9;font-weight:600;">
                                    <i class="fa-solid fa-route"></i> ${d.distance}
                                </div>
                                <a href="${d.url}" class="popup-card-btn">Lihat Detail →</a>
                            </div>`);
                        clusterGroup.addLayer(marker);
                    });

                    status.textContent = `${data.length} destinasi terdekat ditemukan dalam radius 20 km.`;
                    document.getElementById('nearbyBtn').disabled = false;
                });
        },
        function (err) {
            status.textContent = 'Gagal mendapatkan lokasi: ' + err.message;
            document.getElementById('nearbyBtn').disabled = false;
        },
        { timeout: 8000 }
    );
});

// ── 7. Mobile: Toggle Sidebar ─────────────────────────────────────
document.getElementById('toggleSidebar')?.addEventListener('click', function () {
    document.getElementById('mapSidebar').classList.toggle('hidden');
});

// ── Init: Load semua marker saat halaman pertama kali dibuka ──────
loadMarkers();
</script>
@endpush
