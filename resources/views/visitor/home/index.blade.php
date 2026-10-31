@extends('visitor.layouts.app')
@section('title', __('home.hero_title') . ' — WisataDaerah')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     HERO SECTION — Banner dengan search bar
═══════════════════════════════════════════════════════════════ --}}
<section class="relative bg-gradient-to-br from-sky-600 via-sky-500 to-cyan-500 overflow-hidden">
    {{-- Background pattern --}}
    <div class="absolute inset-0 opacity-10"
         style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")">
    </div>

    <div class="relative max-w-5xl mx-auto px-4 py-24 text-center">
        <div class="inline-flex items-center gap-2 bg-white/20 text-white text-sm px-4 py-2 rounded-full mb-6 backdrop-blur-sm">
            <i class="fa-solid fa-map-pin animate-pulse"></i>
            {{ $stats['destinations'] }} {{ __('home.destinations_in') }} {{ $stats['regions'] }} {{ __('home.regions') }}
        </div>

        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 leading-tight">
            {!! __('home.hero_title') !!}
        </h1>
        <p class="text-sky-100 text-lg mb-10 max-w-2xl mx-auto">
            {{ __('home.hero_subtitle') }}
        </p>

        {{-- Search bar --}}
        <div class="max-w-2xl mx-auto relative">
            <div class="flex bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="flex-1 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="heroSearch" placeholder="{{ __('home.search_placeholder') }}"
                           class="w-full pl-11 pr-4 py-4 text-gray-800 focus:outline-none text-sm">
                </div>
                <button onclick="doHeroSearch()"
                        class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-6 transition-colors text-sm">
                    {{ __('home.search_btn') }}
                </button>
            </div>

            {{-- Autocomplete dropdown --}}
            <div id="searchDropdown"
                 class="absolute top-full left-0 right-0 bg-white rounded-2xl shadow-xl border border-gray-100 mt-2 overflow-hidden z-50 hidden">
            </div>
        </div>

        {{-- Quick category buttons --}}
        <div class="flex flex-wrap justify-center gap-2 mt-8">
            @foreach($categories as $cat)
            <a href="{{ route('destinations.index', ['category' => $cat->slug]) }}"
               class="flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white text-sm px-4 py-2 rounded-full backdrop-blur-sm transition-all duration-200 border border-white/30">
                <i class="fa-solid {{ $cat->icon ?? 'fa-star' }} text-xs"></i>
                {{ $cat->localized_name }}
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     STATISTIK SINGKAT
═══════════════════════════════════════════════════════════════ --}}
<section class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 py-8 grid grid-cols-3 gap-6 text-center">
        <div>
            <p class="text-3xl font-extrabold text-sky-600">{{ $stats['destinations'] }}+</p>
            <p class="text-sm text-gray-500 mt-1">{{ __('home.stat_destinations') }}</p>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-sky-600">{{ $stats['regions'] }}+</p>
            <p class="text-sm text-gray-500 mt-1">{{ __('home.stat_regions') }}</p>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-sky-600">{{ $stats['categories'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ __('home.stat_categories') }}</p>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     DESTINASI POPULER
═══════════════════════════════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('home.popular_title') }}</h2>
            <p class="text-gray-500 mt-1">{{ __('home.popular_subtitle') }}</p>
        </div>
        <a href="{{ route('destinations.index') }}"
           class="text-sky-600 hover:text-sky-700 text-sm font-semibold flex items-center gap-1 transition-colors">
            {{ __('home.see_all') }} <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($popularDestinations as $dest)
        <a href="{{ route('destinations.show', $dest->slug) }}"
           class="card-hover bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 group block">
            {{-- Thumbnail --}}
            <div class="relative h-48 overflow-hidden bg-gray-100">
                @if($dest->thumbnail)
                    <img src="{{ asset('storage/'.$dest->thumbnail) }}" alt="{{ $dest->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <i class="fa-solid fa-image text-5xl"></i>
                    </div>
                @endif
                {{-- Category badge --}}
                <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-semibold text-white"
                      style="background-color: {{ $dest->category?->color ?? '#3b82f6' }}">
                    {{ $dest->category?->localized_name }}
                </span>
            </div>
            {{-- Info --}}
            <div class="p-4">
                <h3 class="font-semibold text-gray-800 group-hover:text-sky-600 transition-colors">{{ $dest->localized_name }}</h3>
                <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-location-dot text-sky-400 text-xs"></i>
                    {{ $dest->region }}
                </p>
                <div class="flex items-center justify-between mt-3">
                    <div class="flex items-center gap-1 text-amber-500 text-sm">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-{{ $i <= $dest->rating_avg ? 'solid' : 'regular' }} fa-star text-xs"></i>
                        @endfor
                        <span class="text-gray-500 text-xs ml-1">({{ $dest->review_count }})</span>
                    </div>
                    <span class="text-sm font-semibold text-sky-600">{{ $dest->ticket_price ?? __('home.free') }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     PETA INTERAKTIF CTA
═══════════════════════════════════════════════════════════════ --}}
<section class="bg-gradient-to-r from-slate-800 to-slate-700 py-16">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <i class="fa-solid fa-map text-sky-400 text-5xl mb-4"></i>
        <h2 class="text-3xl font-bold text-white mb-3">{{ __('home.map_cta_title') }}</h2>
        <p class="text-slate-300 mb-8 max-w-xl mx-auto">{{ __('home.map_cta_desc') }}</p>
        <a href="{{ route('map') }}"
           class="inline-flex items-center gap-3 bg-sky-500 hover:bg-sky-400 text-white font-bold px-8 py-4 rounded-2xl transition-all duration-200 shadow-lg hover:shadow-sky-500/30">
            <i class="fa-solid fa-map-location-dot"></i>
            {{ __('home.map_cta_btn') }}
        </a>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     EVENT & FESTIVAL MENDATANG
═══════════════════════════════════════════════════════════════ --}}
@if($upcomingEvents->count() > 0)
<section class="max-w-7xl mx-auto px-4 py-16">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ __('home.events_title') }}</h2>
    <p class="text-gray-500 mb-8">{{ __('home.events_subtitle') }}</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach($upcomingEvents as $event)
        <a href="{{ route('events.show', $event->slug) }}"
           class="card-hover bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm block group">
            @if($event->thumbnail)
                <img src="{{ asset('storage/'.$event->thumbnail) }}" alt="{{ $event->title }}"
                     class="w-full h-36 object-cover group-hover:scale-105 transition-transform duration-300">
            @endif
            <div class="p-4">
                <div class="flex items-center gap-2 text-sky-600 text-xs font-semibold mb-2">
                    <i class="fa-solid fa-calendar-days"></i>
                    {{ $event->start_date->format('d M Y') }}
                    @if($event->start_date != $event->end_date)
                        — {{ $event->end_date->format('d M Y') }}
                    @endif
                </div>
                <h3 class="font-semibold text-gray-800 text-sm group-hover:text-sky-600 transition-colors line-clamp-2">
                    {{ $event->localized_title }}
                </h3>
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-location-dot"></i> {{ $event->location }}
                </p>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
/**
 * Hero search: autocomplete dari API saat user mengetik.
 */
(function initHeroSearch() {
    const input    = document.getElementById('heroSearch');
    const dropdown = document.getElementById('searchDropdown');
    let debounceTimer;

    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const q = this.value.trim();
        if (q.length < 2) { dropdown.classList.add('hidden'); return; }

        // Debounce 300ms agar tidak spam request
        debounceTimer = setTimeout(() => {
            fetch(`/api/destinations/search?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    if (!data.length) { dropdown.classList.add('hidden'); return; }
                    dropdown.innerHTML = data.map(d => `
                        <a href="${d.url}"
                           class="flex items-center gap-3 px-4 py-3 hover:bg-sky-50 transition-colors border-b border-gray-50 last:border-0">
                            <i class="fa-solid fa-location-dot text-sky-400 text-sm"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-800">${d.name}</p>
                                <p class="text-xs text-gray-400">${d.region}</p>
                            </div>
                        </a>`).join('');
                    dropdown.classList.remove('hidden');
                });
        }, 300);
    });

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', e => {
        if (!e.target.closest('#heroSearch') && !e.target.closest('#searchDropdown')) {
            dropdown.classList.add('hidden');
        }
    });
})();

function doHeroSearch() {
    const q = document.getElementById('heroSearch').value.trim();
    if (q) window.location.href = `/destinasi?search=${encodeURIComponent(q)}`;
}

document.getElementById('heroSearch')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') doHeroSearch();
});
</script>
@endpush
