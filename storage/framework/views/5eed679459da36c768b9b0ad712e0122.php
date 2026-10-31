<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'WisataDaerah — Temukan Keindahan Nusantara'); ?></title>

    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:  { DEFAULT: '#0ea5e9', dark: '#0284c7' },
                        accent:   { DEFAULT: '#f59e0b', dark: '#d97706' },
                    },
                    fontFamily: { sans: ['-apple-system', 'Segoe UI', 'system-ui', 'sans-serif'] },
                }
            }
        }
    </script>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">

    <style>
        /* Smooth transitions & custom utilities */
        * { box-sizing: border-box; }
        body { font-family: -apple-system, 'Segoe UI', system-ui, sans-serif; }
        .card-hover { transition: transform .2s ease, box-shadow .2s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,.12); }
        .btn-primary { @apply bg-sky-500 hover:bg-sky-600 text-white font-semibold px-5 py-2.5 rounded-xl transition-all duration-200; }
        .btn-outline { @apply border-2 border-sky-500 text-sky-600 hover:bg-sky-500 hover:text-white font-semibold px-5 py-2.5 rounded-xl transition-all duration-200; }
        /* Star rating */
        .star-filled { color: #f59e0b; }
        .star-empty  { color: #d1d5db; }
        /* Leaflet popup styling */
        .leaflet-popup-content-wrapper { border-radius: 12px !important; box-shadow: 0 8px 24px rgba(0,0,0,.15) !important; }
        .leaflet-popup-content { margin: 0 !important; padding: 0 !important; }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-50 text-gray-800">


<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2 font-bold text-xl text-sky-600">
                <i class="fa-solid fa-map-location-dot"></i>
                WisataDaerah
            </a>

            
            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="<?php echo e(route('home')); ?>" class="hover:text-sky-600 transition-colors">
                    <?php echo e(__('nav.home')); ?>

                </a>
                <a href="<?php echo e(route('destinations.index')); ?>" class="hover:text-sky-600 transition-colors">
                    <?php echo e(__('nav.destinations')); ?>

                </a>
                <a href="<?php echo e(route('map')); ?>" class="hover:text-sky-600 transition-colors">
                    <?php echo e(__('nav.map')); ?>

                </a>
                <a href="<?php echo e(route('events.index')); ?>" class="hover:text-sky-600 transition-colors">
                    <?php echo e(__('nav.events')); ?>

                </a>
                <a href="<?php echo e(route('itinerary.index')); ?>" class="hover:text-sky-600 transition-colors">
                    <?php echo e(__('nav.itinerary')); ?>

                </a>
            </div>

            
            <div class="flex items-center gap-3">
                
                <div class="flex items-center gap-1 text-xs border border-gray-200 rounded-lg overflow-hidden">
                    <a href="<?php echo e(route('lang.switch', 'id')); ?>"
                       class="px-2.5 py-1.5 <?php echo e(app()->getLocale()==='id' ? 'bg-sky-500 text-white' : 'hover:bg-gray-100'); ?> transition-colors">
                        ID
                    </a>
                    <a href="<?php echo e(route('lang.switch', 'en')); ?>"
                       class="px-2.5 py-1.5 <?php echo e(app()->getLocale()==='en' ? 'bg-sky-500 text-white' : 'hover:bg-gray-100'); ?> transition-colors">
                        EN
                    </a>
                </div>

                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isAdmin()): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn-primary text-sm">
                            <i class="fa-solid fa-gauge-high mr-1"></i> Admin
                        </a>
                    <?php endif; ?>
                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-sm text-gray-500 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn-outline text-sm">
                        <?php echo e(__('nav.login')); ?>

                    </a>
                <?php endif; ?>

                
                <button id="mobileMenuBtn" class="md:hidden text-gray-600">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100 px-4 py-3 space-y-2 text-sm font-medium">
        <a href="<?php echo e(route('home')); ?>"             class="block py-2 hover:text-sky-600"><?php echo e(__('nav.home')); ?></a>
        <a href="<?php echo e(route('destinations.index')); ?>" class="block py-2 hover:text-sky-600"><?php echo e(__('nav.destinations')); ?></a>
        <a href="<?php echo e(route('map')); ?>"              class="block py-2 hover:text-sky-600"><?php echo e(__('nav.map')); ?></a>
        <a href="<?php echo e(route('events.index')); ?>"     class="block py-2 hover:text-sky-600"><?php echo e(__('nav.events')); ?></a>
        <a href="<?php echo e(route('itinerary.index')); ?>"  class="block py-2 hover:text-sky-600"><?php echo e(__('nav.itinerary')); ?></a>
    </div>
</nav>


<?php if(session('success')): ?>
    <div class="max-w-7xl mx-auto mt-4 px-4">
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> <?php echo e(session('success')); ?>

        </div>
    </div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="max-w-7xl mx-auto mt-4 px-4">
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> <?php echo e(session('error')); ?>

        </div>
    </div>
<?php endif; ?>


<main>
    <?php echo $__env->yieldContent('content'); ?>
</main>


<footer class="bg-gray-900 text-gray-400 mt-20">
    <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <h3 class="text-white font-bold text-lg mb-3">
                <i class="fa-solid fa-map-location-dot text-sky-400 mr-2"></i>WisataDaerah
            </h3>
            <p class="text-sm leading-relaxed"><?php echo e(__('footer.tagline')); ?></p>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3"><?php echo e(__('footer.links')); ?></h4>
            <ul class="space-y-2 text-sm">
                <li><a href="<?php echo e(route('destinations.index')); ?>" class="hover:text-sky-400 transition-colors"><?php echo e(__('nav.destinations')); ?></a></li>
                <li><a href="<?php echo e(route('map')); ?>" class="hover:text-sky-400 transition-colors"><?php echo e(__('nav.map')); ?></a></li>
                <li><a href="<?php echo e(route('events.index')); ?>" class="hover:text-sky-400 transition-colors"><?php echo e(__('nav.events')); ?></a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3"><?php echo e(__('footer.contact')); ?></h4>
            <p class="text-sm">wisata@daerah.id</p>
        </div>
    </div>
    <div class="border-t border-gray-800 py-4 text-center text-xs text-gray-600">
        &copy; <?php echo e(date('Y')); ?> WisataDaerah. All rights reserved.
    </div>
</footer>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
    // Mobile menu toggle
    document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
        document.getElementById('mobileMenu')?.classList.toggle('hidden');
    });
</script>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\code neerrr\Project\Project Rekomendasi Tempat Wisata\wisata-daerah\resources\views/visitor/layouts/app.blade.php ENDPATH**/ ?>