@extends('visitor.layouts.app')
@section('title', __('itinerary.result_title') . ' ' . session('region') . ' — WisataDaerah')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('itinerary.index') }}"
           class="text-gray-400 hover:text-sky-500 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">
                {{ __('itinerary.result_title') }} {{ session('region') }}
            </h1>
            <p class="text-gray-500 text-sm mt-0.5">
                {{ $itinerary->count() }} {{ __('itinerary.stops') }} ·
                {{ __('itinerary.total_distance') }}: ~{{ $totalDistance }} km
            </p>
        </div>
    </div>

    {{-- Timeline itinerary --}}
    <div class="relative">
        {{-- Garis vertikal timeline --}}
        <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-sky-100"></div>

        <div class="space-y-6">
            @foreach($itinerary as $item)
            <div class="relative flex gap-5">
                {{-- Bullet --}}
                <div class="relative z-10 flex-shrink-0">
                    <div class="w-12 h-12 bg-sky-500 rounded-2xl flex items-center justify-center text-white font-extrabold shadow-md">
                        {{ $item['order'] }}
                    </div>
                </div>

                {{-- Card --}}
                <div class="flex-1 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="flex">
                        {{-- Foto --}}
                        <div class="w-28 flex-shrink-0 bg-gray-100">
                            @if($item['destination']->thumbnail)
                                <img src="{{ asset('storage/'.$item['destination']->thumbnail) }}"
                                     alt="" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-200 min-h-20">
                                    <i class="fa-solid fa-image text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        {{-- Detail --}}
                        <div class="flex-1 p-4">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-xs font-semibold text-sky-600 flex items-center gap-1">
                                        <i class="fa-solid fa-clock"></i> {{ $item['time_slot'] }}
                                    </p>
                                    <h3 class="font-bold text-gray-800 mt-0.5">{{ $item['destination']->localized_name }}</h3>
                                    <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                        <i class="fa-solid fa-location-dot text-sky-400"></i>
                                        {{ $item['destination']->address }}
                                    </p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold text-white flex-shrink-0"
                                      style="background-color: {{ $item['destination']->category?->color ?? '#3b82f6' }}">
                                    {{ $item['destination']->category?->localized_name }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-xs text-amber-500">
                                    ★ {{ $item['destination']->rating_avg }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $item['destination']->open_hours ?? '-' }}
                                </span>
                                <span class="text-xs font-semibold text-sky-600">
                                    {{ $item['destination']->ticket_price ?? __('home.free') }}
                                </span>
                            </div>
                            <a href="{{ route('destinations.show', $item['destination']->slug) }}"
                               class="mt-2 inline-flex items-center gap-1 text-xs text-sky-500 hover:text-sky-700 font-semibold transition-colors">
                                {{ __('itinerary.detail') }} <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Action buttons --}}
    <div class="flex gap-3 mt-8">
        <a href="{{ route('itinerary.index') }}"
           class="flex-1 border-2 border-sky-500 text-sky-600 hover:bg-sky-500 hover:text-white font-semibold py-3 rounded-xl transition-all text-center text-sm">
            <i class="fa-solid fa-rotate-left mr-2"></i>{{ __('itinerary.try_again') }}
        </a>
        <a href="{{ route('map') }}"
           class="flex-1 bg-sky-500 hover:bg-sky-600 text-white font-semibold py-3 rounded-xl transition-colors text-center text-sm">
            <i class="fa-solid fa-map mr-2"></i>{{ __('itinerary.view_map') }}
        </a>
    </div>
</div>
@endsection
