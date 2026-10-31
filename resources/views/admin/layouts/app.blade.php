<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — WisataDaerah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- Leaflet untuk Pin Location map di form destinasi --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        body { font-family: -apple-system, 'Segoe UI', system-ui, sans-serif; }
        .sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-700 hover:text-white transition-all duration-200; }
        .sidebar-link.active { @apply bg-sky-600 text-white; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100">

<div class="flex h-screen overflow-hidden">

    {{-- ── Sidebar ──────────────────────────────────────────────── --}}
    <aside class="w-64 bg-slate-800 flex flex-col flex-shrink-0">
        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-slate-700">
            <a href="{{ route('admin.dashboard') }}" class="text-white font-bold text-lg flex items-center gap-2">
                <i class="fa-solid fa-map-location-dot text-sky-400"></i>
                WisataDaerah
            </a>
            <p class="text-slate-400 text-xs mt-1">Admin Panel</p>
        </div>

        {{-- Nav links --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high w-5"></i> Dashboard
            </a>
            <div class="pt-3 pb-1 px-4 text-xs text-slate-500 uppercase tracking-wider">Konten</div>
            <a href="{{ route('admin.destinations.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.destinations.*') ? 'active' : '' }}">
                <i class="fa-solid fa-mountain-sun w-5"></i> Destinasi
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fa-solid fa-tags w-5"></i> Kategori
            </a>
            <a href="{{ route('admin.events.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-star w-5"></i> Event
            </a>
            <div class="pt-3 pb-1 px-4 text-xs text-slate-500 uppercase tracking-wider">Moderasi</div>
            <a href="{{ route('admin.reviews.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="fa-solid fa-star w-5"></i> Ulasan
                @php $pending = \App\Models\Review::pending()->count(); @endphp
                @if($pending > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pending }}</span>
                @endif
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users w-5"></i> Pengguna
            </a>
            <div class="pt-3 pb-1 px-4 text-xs text-slate-500 uppercase tracking-wider">Aksi</div>
            <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                <i class="fa-solid fa-arrow-up-right-from-square w-5"></i> Lihat Website
            </a>
        </nav>

        {{-- User info + logout --}}
        <div class="px-4 py-4 border-t border-slate-700 flex items-center gap-3">
            <div class="w-9 h-9 bg-sky-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-slate-400 text-xs">Administrator</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-red-400 transition-colors">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main Content ─────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Top bar --}}
        <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between flex-shrink-0">
            <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
            <div class="text-sm text-slate-500">
                <i class="fa-regular fa-clock mr-1"></i>
                {{ now()->format('d M Y, H:i') }}
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto px-6 pb-6">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@stack('scripts')
</body>
</html>
