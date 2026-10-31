@extends('admin.layouts.app')
@section('title', 'Daftar Destinasi')
@section('page-title', 'Manajemen Destinasi')

@section('content')
<div class="mt-4 space-y-4">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Total: <strong>{{ $destinations->total() }}</strong> destinasi</p>
        <a href="{{ route('admin.destinations.create') }}"
           class="bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Destinasi
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Destinasi</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Kategori</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Wilayah</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Rating</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($destinations as $dest)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($dest->thumbnail)
                                <img src="{{ asset('storage/'.$dest->thumbnail) }}" alt=""
                                     class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0">
                                    <i class="fa-solid fa-image text-xs"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-800">{{ $dest->name }}</p>
                                <p class="text-xs text-gray-400">{{ $dest->view_count }} views</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-sky-50 text-sky-700">
                            {{ $dest->category?->name }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $dest->region }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-semibold text-amber-500">★ {{ $dest->rating_avg }}</span>
                        <span class="text-xs text-gray-400">({{ $dest->review_count }})</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($dest->is_active)
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">Aktif</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Nonaktif</span>
                        @endif
                        @if($dest->is_popular)
                            <span class="ml-1 px-2 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">Populer</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('destinations.show', $dest->slug) }}" target="_blank"
                               class="text-gray-400 hover:text-sky-500 transition-colors" title="Lihat">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.destinations.edit', $dest) }}"
                               class="text-gray-400 hover:text-amber-500 transition-colors" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.destinations.destroy', $dest) }}" method="POST"
                                  onsubmit="return confirm('Hapus destinasi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-gray-400">
                        <i class="fa-solid fa-mountain-sun text-3xl mb-3 block opacity-30"></i>
                        Belum ada destinasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $destinations->links() }}</div>
</div>
@endsection
