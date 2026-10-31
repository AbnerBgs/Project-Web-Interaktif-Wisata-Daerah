@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Statistik')

@section('content')
<div class="space-y-6 mt-4">

    {{-- ── Stat Cards ─────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach([
            ['label' => 'Total Destinasi',  'value' => $stats['total_destinations'],  'icon' => 'fa-mountain-sun',  'color' => 'sky'],
            ['label' => 'Destinasi Aktif',  'value' => $stats['active_destinations'], 'icon' => 'fa-circle-check', 'color' => 'green'],
            ['label' => 'Review Pending',   'value' => $stats['pending_reviews'],     'icon' => 'fa-hourglass',    'color' => 'amber'],
            ['label' => 'Total Ulasan',     'value' => $stats['total_reviews'],       'icon' => 'fa-star',         'color' => 'yellow'],
            ['label' => 'Pengguna',         'value' => $stats['total_users'],         'icon' => 'fa-users',        'color' => 'violet'],
            ['label' => 'Event',            'value' => $stats['total_events'],        'icon' => 'fa-calendar',     'color' => 'rose'],
        ] as $stat)
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500">{{ $stat['label'] }}</span>
                <i class="fa-solid {{ $stat['icon'] }} text-{{ $stat['color'] }}-500 text-sm"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($stat['value']) }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ── Destinasi Terpopuler ─────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-fire text-orange-500"></i> Destinasi Terpopuler
            </h2>
            <div class="space-y-3">
                @foreach($popularDestinations as $i => $dest)
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 bg-gray-100 rounded-full flex items-center justify-center text-xs font-bold text-gray-600">
                        {{ $i + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $dest->name }}</p>
                        <p class="text-xs text-gray-400">{{ $dest->category?->name }} · {{ $dest->region }}</p>
                    </div>
                    <div class="text-right text-xs">
                        <p class="font-semibold text-gray-700">{{ number_format($dest->view_count) }}</p>
                        <p class="text-gray-400">views</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ── Review Pending ───────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clock text-amber-500"></i> Ulasan Menunggu Moderasi
            </h2>
            @forelse($pendingReviews as $review)
            <div class="flex items-start gap-3 py-2.5 border-b border-gray-50 last:border-0">
                <div class="w-8 h-8 bg-sky-100 rounded-full flex items-center justify-center text-sky-600 text-xs font-bold flex-shrink-0">
                    {{ substr($review->reviewer_name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">{{ $review->reviewer_name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $review->destination?->name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $review->comment }}</p>
                </div>
                <div class="flex gap-1 flex-shrink-0">
                    <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="text-xs bg-green-500 hover:bg-green-600 text-white px-2.5 py-1 rounded-lg transition-colors">
                            ✓
                        </button>
                    </form>
                    <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="text-xs bg-red-500 hover:bg-red-600 text-white px-2.5 py-1 rounded-lg transition-colors">
                            ✗
                        </button>
                    </form>
                </div>
            </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-8">Tidak ada ulasan pending.</p>
            @endforelse
        </div>
    </div>

    {{-- ── Distribusi Kategori (chart bar sederhana) ──────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-semibold text-gray-700 mb-5 flex items-center gap-2">
            <i class="fa-solid fa-chart-bar text-sky-500"></i> Distribusi Destinasi per Kategori
        </h2>
        <div class="space-y-3">
            @php $maxVal = $categoryDistribution->max() ?: 1; @endphp
            @foreach($categoryDistribution as $cat => $count)
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600 w-28 flex-shrink-0">{{ $cat }}</span>
                <div class="flex-1 bg-gray-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-sky-500 h-3 rounded-full transition-all duration-700"
                         style="width: {{ ($count / $maxVal) * 100 }}%"></div>
                </div>
                <span class="text-sm font-semibold text-gray-700 w-8 text-right">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
