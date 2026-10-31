@extends('admin.layouts.app')
@section('title', 'Moderasi Ulasan')
@section('page-title', 'Moderasi Ulasan Pengunjung')

@section('content')
<div class="mt-4 space-y-4">

    {{-- Filter --}}
    <form class="flex flex-wrap gap-3 bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <select name="status" class="border border-gray-300 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <button class="bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
            Filter
        </button>
    </form>

    {{-- Review list --}}
    <div class="space-y-3">
        @forelse($reviews as $review)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-sky-100 rounded-full flex items-center justify-center text-sky-600 font-bold text-sm flex-shrink-0">
                    {{ substr($review->reviewer_name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-semibold text-gray-800">{{ $review->reviewer_name }}</span>
                        <span class="text-xs text-gray-400">→ {{ $review->destination?->name }}</span>
                        <span class="text-amber-500 text-sm">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </span>
                    </div>
                    @if($review->title)
                        <p class="font-medium text-gray-700 mt-1">{{ $review->title }}</p>
                    @endif
                    <p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex-shrink-0 flex flex-col items-end gap-2">
                    {{-- Status badge --}}
                    @if($review->status === 'pending')
                        <span class="px-3 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full">Pending</span>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1.5 rounded-lg transition-colors">
                                    <i class="fa-solid fa-check mr-1"></i>Setujui
                                </button>
                            </form>
                            <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg transition-colors">
                                    <i class="fa-solid fa-xmark mr-1"></i>Tolak
                                </button>
                            </form>
                        </div>
                    @elseif($review->status === 'approved')
                        <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full">✓ Disetujui</span>
                    @else
                        <span class="px-3 py-1 bg-red-50 text-red-700 text-xs font-semibold rounded-full">✗ Ditolak</span>
                    @endif

                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                          onsubmit="return confirm('Hapus ulasan ini?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-gray-400 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-16 text-center text-gray-400">
            <i class="fa-regular fa-star text-4xl mb-3 block opacity-30"></i>
            Belum ada ulasan.
        </div>
        @endforelse
    </div>

    {{ $reviews->links() }}
</div>
@endsection
