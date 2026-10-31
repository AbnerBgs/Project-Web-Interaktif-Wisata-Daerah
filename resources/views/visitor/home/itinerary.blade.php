@extends('visitor.layouts.app')
@section('title', __('itinerary.title') . ' — WisataDaerah')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="text-center mb-10">
        <span class="inline-flex items-center gap-2 bg-sky-50 text-sky-600 text-sm font-semibold px-4 py-2 rounded-full mb-4">
            <i class="fa-solid fa-route"></i> {{ __('itinerary.badge') }}
        </span>
        <h1 class="text-3xl font-extrabold text-gray-900">{{ __('itinerary.title') }}</h1>
        <p class="text-gray-500 mt-2 max-w-xl mx-auto">{{ __('itinerary.subtitle') }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('itinerary.generate') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Wilayah --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-map-pin text-sky-500 mr-1"></i>{{ __('itinerary.region_label') }}
                    <span class="text-red-500">*</span>
                </label>
                <select name="region" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">-- {{ __('itinerary.choose_region') }} --</option>
                    @foreach($regions as $region)
                        <option value="{{ $region }}" {{ old('region') === $region ? 'selected' : '' }}>
                            {{ $region }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kategori Wisata --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-filter text-sky-500 mr-1"></i>{{ __('itinerary.category_label') }}
                    <span class="text-gray-400 text-xs font-normal">({{ __('itinerary.optional') }})</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($categories as $cat)
                    <label class="flex items-center gap-2 border border-gray-200 rounded-xl p-3 cursor-pointer hover:border-sky-400 hover:bg-sky-50 transition-all">
                        <input type="checkbox" name="categories[]" value="{{ $cat->id }}"
                               {{ in_array($cat->id, old('categories', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-sky-600 rounded">
                        <i class="fa-solid {{ $cat->icon ?? 'fa-star' }} text-sky-500 text-sm"></i>
                        <span class="text-sm text-gray-700">{{ $cat->localized_name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-sky-500 hover:bg-sky-600 text-white font-bold py-3.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                {{ __('itinerary.generate_btn') }}
            </button>
        </form>
    </div>
</div>
@endsection
