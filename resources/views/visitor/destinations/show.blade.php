@extends('visitor.layouts.app')
@section('title', $destination->localized_name . ' — WisataDaerah')

@push('styles')
<style>
    #detailMap { height: 280px; border-radius: 16px; }
    .gallery-item { cursor: pointer; transition: transform .2s, opacity .2s; }
    .gallery-item:hover { transform: scale(1.03); opacity: .9; }
    /* Lightbox overlay */
    #lightbox { backdrop-filter: blur(8px); }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">

    {{-- ── Breadcrumb ──────────────────────────────────────────── --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-sky-500 transition-colors">{{ __('nav.home') }}</a>
        <i class="fa-solid fa-chevron-right text-xs"></i>
        <a href="{{ route('destinations.index') }}" class="hover:text-sky-500 transition-colors">{{ __('nav.destinations') }}</a>
        <i class="fa-solid fa-chevron-right text-xs"></i>
        <span class="text-gray-600 font-medium">{{ $destination->localized_name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── LEFT: Galeri & Detail ─────────────────────────── --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Gambar utama --}}
            <div class="relative rounded-2xl overflow-hidden bg-gray-100 h-72 md:h-96">
                @if($destination->thumbnail)
                    <img id="mainImage" src="{{ asset('storage/'.$destination->thumbnail) }}"
                         alt="{{ $destination->name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <i class="fa-solid fa-image text-6xl"></i>
                    </div>
                @endif
                {{-- Category badge --}}
                <span class="absolute top-4 left-4 px-3 py-1.5 rounded-full text-sm font-semibold text-white shadow"
                      style="background-color: {{ $destination->category?->color ?? '#3b82f6' }}">
                    <i class="fa-solid {{ $destination->category?->icon ?? 'fa-star' }} mr-1"></i>
                    {{ $destination->category?->localized_name }}
                </span>
            </div>

            {{-- Galeri foto thumbnail --}}
            @if($destination->images->count() > 0)
            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                @foreach($destination->images as $img)
                <div class="gallery-item rounded-xl overflow-hidden h-16 bg-gray-100"
                     onclick="openLightbox('{{ asset('storage/'.$img->image_path) }}')">
                    <img src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $img->caption }}"
                         class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
            @endif

            {{-- Judul & rating --}}
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">{{ $destination->localized_name }}</h1>
                <div class="flex items-center gap-3 mt-2 flex-wrap">
                    <div class="flex items-center gap-1 text-amber-500">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-{{ $i <= $destination->rating_avg ? 'solid' : 'regular' }} fa-star"></i>
                        @endfor
                        <span class="text-gray-600 text-sm ml-1 font-semibold">
                            {{ $destination->rating_avg }}/5 ({{ $destination->review_count }} {{ __('dest.reviews') }})
                        </span>
                    </div>
                    <span class="text-gray-300">|</span>
                    <span class="text-sm text-gray-500 flex items-center gap-1">
                        <i class="fa-solid fa-eye text-gray-400"></i>
                        {{ number_format($destination->view_count) }} {{ __('dest.views') }}
                    </span>
                </div>
                <p class="text-gray-500 text-sm flex items-center gap-1 mt-2">
                    <i class="fa-solid fa-location-dot text-sky-500"></i>
                    {{ $destination->address }}, {{ $destination->region }}
                </p>
            </div>

            {{-- Deskripsi --}}
            <div class="prose prose-gray max-w-none">
                <p class="text-gray-700 leading-relaxed">{{ $destination->localized_description }}</p>
            </div>

            {{-- Fasilitas --}}
            @if($destination->facilities->count() > 0)
            <div class="bg-gray-50 rounded-2xl p-5">
                <h3 class="font-semibold text-gray-700 mb-3">
                    <i class="fa-solid fa-circle-check text-green-500 mr-2"></i>{{ __('dest.facilities') }}
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($destination->facilities as $fac)
                    <span class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-full px-3 py-1.5 text-sm text-gray-700">
                        <i class="fa-solid {{ $fac->icon ?? 'fa-check' }} text-sky-500 text-xs"></i>
                        {{ $fac->localized_name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── PETA LOKASI + Rute ke Google Maps ────────── --}}
            <div>
                <h3 class="font-semibold text-gray-700 mb-3">
                    <i class="fa-solid fa-map-location-dot text-sky-500 mr-2"></i>{{ __('dest.location_map') }}
                </h3>
                <div id="detailMap" class="w-full border border-gray-200"></div>
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $destination->latitude }},{{ $destination->longitude }}"
                   target="_blank" rel="noopener"
                   class="mt-3 inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2.5 rounded-xl transition-colors text-sm">
                    <i class="fa-brands fa-google"></i>
                    {{ __('dest.route_btn') }}
                </a>
            </div>

            {{-- ── ULASAN PENGUNJUNG ────────────────────────── --}}
            <div id="reviews-section">
                <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-comments text-sky-500"></i>
                    {{ __('dest.reviews_title') }}
                    <span class="bg-sky-100 text-sky-700 text-xs font-bold px-2.5 py-0.5 rounded-full">
                        {{ $destination->review_count }}
                    </span>
                </h3>

                {{-- Rating distribution bar --}}
                @if($destination->review_count > 0)
                <div class="bg-gray-50 rounded-2xl p-5 mb-5">
                    <div class="flex items-center gap-5">
                        <div class="text-center">
                            <p class="text-4xl font-extrabold text-gray-800">{{ $destination->rating_avg }}</p>
                            <div class="text-amber-500 text-xs mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-{{ $i <= $destination->rating_avg ? 'solid' : 'regular' }} fa-star"></i>
                                @endfor
                            </div>
                        </div>
                        <div class="flex-1 space-y-1.5">
                            @for($star = 5; $star >= 1; $star--)
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-gray-500 w-8">{{ $star }} ★</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-amber-400 h-2 rounded-full"
                                         style="width: {{ $destination->review_count > 0 ? ($ratingDistribution[$star] / $destination->review_count) * 100 : 0 }}%">
                                    </div>
                                </div>
                                <span class="text-gray-400 w-5">{{ $ratingDistribution[$star] }}</span>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
                @endif

                {{-- Review cards --}}
                <div class="space-y-4">
                    @forelse($destination->approvedReviews->take(5) as $review)
                    <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 bg-sky-100 rounded-full flex items-center justify-center text-sky-600 font-bold flex-shrink-0">
                                {{ substr($review->reviewer_name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-sm text-gray-800">{{ $review->reviewer_name }}</span>
                                    <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-amber-500 text-xs mt-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                </div>
                                @if($review->title)
                                    <p class="font-medium text-gray-700 mt-1.5 text-sm">{{ $review->title }}</p>
                                @endif
                                <p class="text-gray-600 text-sm mt-1 leading-relaxed">{{ $review->comment }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-400 text-sm text-center py-8">{{ __('dest.no_reviews') }}</p>
                    @endforelse
                </div>

                {{-- Form Tulis Ulasan --}}
                <div class="mt-6 bg-sky-50 border border-sky-100 rounded-2xl p-5">
                    <h4 class="font-semibold text-gray-700 mb-4">
                        <i class="fa-solid fa-pen-to-square text-sky-500 mr-2"></i>{{ __('dest.write_review') }}
                    </h4>
                    <form action="{{ route('reviews.store', $destination->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <input type="text" name="reviewer_name" placeholder="{{ __('dest.your_name') }}" required
                                       value="{{ old('reviewer_name', auth()->user()?->name) }}"
                                       class="w-full border border-gray-200 bg-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                            </div>
                            <div>
                                <input type="email" name="reviewer_email" placeholder="{{ __('dest.your_email') }}"
                                       value="{{ old('reviewer_email', auth()->user()?->email) }}"
                                       class="w-full border border-gray-200 bg-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                            </div>
                        </div>

                        {{-- Star rating input --}}
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600">{{ __('dest.rating') }}:</span>
                            <div class="flex gap-1" id="starRating">
                                @for($i = 1; $i <= 5; $i++)
                                <button type="button" data-val="{{ $i }}"
                                        class="star-btn text-2xl text-gray-300 hover:text-amber-400 transition-colors">★</button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 5) }}" required>
                        </div>

                        <input type="text" name="title" placeholder="{{ __('dest.review_title') }}"
                               value="{{ old('title') }}"
                               class="w-full border border-gray-200 bg-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">

                        <textarea name="comment" rows="3" required placeholder="{{ __('dest.review_comment') }}"
                                  class="w-full border border-gray-200 bg-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">{{ old('comment') }}</textarea>

                        <button type="submit"
                                class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors text-sm">
                            <i class="fa-solid fa-paper-plane mr-2"></i>{{ __('dest.submit_review') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Info Sidebar ──────────────────────────── --}}
        <div class="space-y-4">
            {{-- Info card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-3">
                <h3 class="font-semibold text-gray-700 border-b pb-3">{{ __('dest.info_title') }}</h3>
                <div class="flex items-start gap-3 text-sm">
                    <i class="fa-solid fa-clock text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-gray-700">{{ __('dest.open_hours') }}</p>
                        <p class="text-gray-500">{{ $destination->open_hours ?? __('dest.not_available') }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 text-sm">
                    <i class="fa-solid fa-ticket text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-gray-700">{{ __('dest.ticket') }}</p>
                        <p class="text-gray-500">
                            {{ app()->getLocale() === 'en' && $destination->ticket_price_en
                                ? $destination->ticket_price_en
                                : ($destination->ticket_price ?? __('home.free')) }}
                        </p>
                    </div>
                </div>
                @if($destination->phone)
                <div class="flex items-start gap-3 text-sm">
                    <i class="fa-solid fa-phone text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-gray-700">{{ __('dest.phone') }}</p>
                        <a href="tel:{{ $destination->phone }}" class="text-sky-500 hover:underline">{{ $destination->phone }}</a>
                    </div>
                </div>
                @endif
                @if($destination->website)
                <div class="flex items-start gap-3 text-sm">
                    <i class="fa-solid fa-globe text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-gray-700">{{ __('dest.website') }}</p>
                        <a href="{{ $destination->website }}" target="_blank" rel="noopener"
                           class="text-sky-500 hover:underline break-all text-xs">{{ $destination->website }}</a>
                    </div>
                </div>
                @endif
            </div>

            {{-- Destinasi Terkait --}}
            @if($related->count() > 0)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-700 mb-4">{{ __('dest.related') }}</h3>
                <div class="space-y-3">
                    @foreach($related as $rel)
                    <a href="{{ route('destinations.show', $rel->slug) }}"
                       class="flex items-center gap-3 hover:bg-gray-50 rounded-xl p-2 transition-colors">
                        @if($rel->thumbnail)
                            <img src="{{ asset('storage/'.$rel->thumbnail) }}" alt=""
                                 class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-300 flex-shrink-0">
                                <i class="fa-solid fa-image text-xs"></i>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-800 leading-tight">{{ $rel->localized_name }}</p>
                            <p class="text-xs text-gray-400">{{ $rel->region }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Lightbox Modal --}}
<div id="lightbox" class="fixed inset-0 bg-black/80 z-[9999] hidden flex items-center justify-center p-4"
     onclick="closeLightbox()">
    <img id="lightboxImg" src="" alt="" class="max-w-full max-h-full rounded-xl shadow-2xl">
</div>

@endsection

@push('scripts')
<script>
/**
 * Inisialisasi peta lokasi destinasi (read-only, bukan pin editor).
 * Menampilkan marker tunggal di koordinat destinasi.
 */
(function initDetailMap() {
    const lat = {{ $destination->latitude }};
    const lng = {{ $destination->longitude }};

    const map = L.map('detailMap').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    // Marker destinasi
    L.marker([lat, lng])
        .addTo(map)
        .bindPopup('<b>{{ addslashes($destination->name) }}</b><br>{{ addslashes($destination->address) }}')
        .openPopup();
})();

// ── Star rating input ─────────────────────────────────────────────
(function initStarRating() {
    const stars  = document.querySelectorAll('.star-btn');
    const input  = document.getElementById('ratingInput');
    let current  = parseInt(input.value) || 5;

    function updateStars(val) {
        stars.forEach(s => {
            s.classList.toggle('text-amber-400', parseInt(s.dataset.val) <= val);
            s.classList.toggle('text-gray-300',  parseInt(s.dataset.val) >  val);
        });
    }

    updateStars(current);

    stars.forEach(s => {
        s.addEventListener('click', () => {
            current = parseInt(s.dataset.val);
            input.value = current;
            updateStars(current);
        });
        s.addEventListener('mouseenter', () => updateStars(parseInt(s.dataset.val)));
        s.addEventListener('mouseleave', () => updateStars(current));
    });
})();

// ── Lightbox ──────────────────────────────────────────────────────
function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.remove('hidden');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
}
</script>
@endpush
