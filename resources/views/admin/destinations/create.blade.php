@extends('admin.layouts.app')
@section('title', 'Tambah Destinasi')
@section('page-title', 'Tambah Destinasi Baru')

@push('styles')
<style>
    /* Pin location map di form */
    #pinMap { height: 380px; border-radius: 12px; }
</style>
@endpush

@section('content')
<div class="mt-4 max-w-4xl">
    <form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- ── Info Dasar ───────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="font-semibold text-gray-700 border-b pb-3">Informasi Dasar</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Destinasi (Indonesia) <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                           required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama (English)</label>
                    <input type="text" name="name_en" value="{{ old('name_en') }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah/Kabupaten <span class="text-red-500">*</span></label>
                    <input type="text" name="region" value="{{ old('region') }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                           required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                    <input type="text" name="province" value="{{ old('province', 'DI Yogyakarta') }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Indonesia) <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                          class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (English)</label>
                <textarea name="description_en" rows="4"
                          class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('description_en') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Buka</label>
                    <input type="text" name="open_hours" value="{{ old('open_hours') }}" placeholder="08:00 - 17:00"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Tiket (ID)</label>
                    <input type="text" name="ticket_price" value="{{ old('ticket_price') }}" placeholder="Rp 25.000"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Tiket (EN)</label>
                    <input type="text" name="ticket_price_en" value="{{ old('ticket_price_en') }}" placeholder="IDR 25,000"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}
                           class="w-4 h-4 text-sky-600 rounded">
                    <span class="text-sm text-gray-700">Tampilkan di Highlight Beranda</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked
                           class="w-4 h-4 text-sky-600 rounded">
                    <span class="text-sm text-gray-700">Aktif (tampil di website)</span>
                </label>
            </div>
        </div>

        {{-- ── Koordinat GPS — Pin Location di Peta ─────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="font-semibold text-gray-700 border-b pb-3">
                <i class="fa-solid fa-location-pin text-red-500 mr-1"></i>
                Pin Lokasi di Peta
                <span class="text-xs font-normal text-gray-400 ml-2">Klik pada peta untuk menentukan koordinat</span>
            </h2>

            {{-- Peta Leaflet untuk pin lokasi --}}
            <div id="pinMap" class="w-full border border-gray-200"></div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude <span class="text-red-500">*</span></label>
                    <input type="number" name="latitude" id="latInput" value="{{ old('latitude') }}"
                           step="0.0000001" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                           placeholder="-7.7956">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude <span class="text-red-500">*</span></label>
                    <input type="number" name="longitude" id="lngInput" value="{{ old('longitude') }}"
                           step="0.0000001" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                           placeholder="110.3695">
                </div>
            </div>
        </div>

        {{-- ── Foto ────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="font-semibold text-gray-700 border-b pb-3">Foto</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail Utama</label>
                    <input type="file" name="thumbnail" accept="image/*"
                           class="w-full text-sm text-gray-500 border border-gray-300 rounded-xl px-3 py-2 file:mr-3 file:py-1 file:px-3 file:border-0 file:rounded-lg file:bg-sky-50 file:text-sky-700">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Galeri Foto (bisa multiple)</label>
                    <input type="file" name="gallery[]" accept="image/*" multiple
                           class="w-full text-sm text-gray-500 border border-gray-300 rounded-xl px-3 py-2 file:mr-3 file:py-1 file:px-3 file:border-0 file:rounded-lg file:bg-sky-50 file:text-sky-700">
                </div>
            </div>
        </div>

        {{-- ── Fasilitas ───────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-700 border-b pb-3 mb-4">Fasilitas Tersedia</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($facilities as $facility)
                <label class="flex items-center gap-2 cursor-pointer p-3 border border-gray-200 rounded-xl hover:border-sky-400 hover:bg-sky-50 transition-all">
                    <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                           {{ in_array($facility->id, old('facilities', [])) ? 'checked' : '' }}
                           class="w-4 h-4 text-sky-600 rounded">
                    <i class="fa-solid {{ $facility->icon ?? 'fa-check' }} text-sky-500 text-sm"></i>
                    <span class="text-sm text-gray-700">{{ $facility->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex gap-3">
            <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors">
                <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan Destinasi
            </button>
            <a href="{{ route('admin.destinations.index') }}"
               class="border border-gray-300 text-gray-600 hover:bg-gray-50 font-semibold px-6 py-2.5 rounded-xl transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
/**
 * Inisialisasi Leaflet Map untuk fitur "Pin Location".
 * Admin klik pada peta → koordinat otomatis terisi di input lat/lng.
 */
(function initPinLocationMap() {
    // Default center: Yogyakarta
    const defaultLat = {{ old('latitude', -7.7956) }};
    const defaultLng = {{ old('longitude', 110.3695) }};

    // Buat peta Leaflet
    const map = L.map('pinMap').setView([defaultLat, defaultLng], 12);

    // Tile layer OpenStreetMap (gratis, tanpa API key)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    // Marker awal (jika lat/lng sudah ada dari old input)
    let marker = null;
    if (defaultLat && defaultLng) {
        marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
        bindMarkerDrag(marker);
    }

    // Klik peta → pindahkan atau buat marker baru
    map.on('click', function (e) {
        const { lat, lng } = e.latlng;
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            bindMarkerDrag(marker);
        }
        setCoordinates(lat, lng);
    });

    // Drag marker → update input
    function bindMarkerDrag(m) {
        m.on('dragend', function () {
            const pos = m.getLatLng();
            setCoordinates(pos.lat, pos.lng);
        });
    }

    // Update nilai input latitude & longitude
    function setCoordinates(lat, lng) {
        document.getElementById('latInput').value = lat.toFixed(7);
        document.getElementById('lngInput').value = lng.toFixed(7);
    }

    // Sinkronisasi: jika admin ketik manual di input, update marker di peta
    ['latInput', 'lngInput'].forEach(id => {
        document.getElementById(id).addEventListener('change', function () {
            const lat = parseFloat(document.getElementById('latInput').value);
            const lng = parseFloat(document.getElementById('lngInput').value);
            if (!isNaN(lat) && !isNaN(lng)) {
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                    bindMarkerDrag(marker);
                }
                map.setView([lat, lng], 14);
            }
        });
    });
})();
</script>
@endpush
