<?php $__env->startSection('title', __('home.hero_title') . ' — WisataDaerah'); ?>

<?php $__env->startSection('content'); ?>


<section class="relative bg-gradient-to-br from-sky-600 via-sky-500 to-cyan-500 overflow-hidden">
    
    <div class="absolute inset-0 opacity-10"
         style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")">
    </div>

    <div class="relative max-w-5xl mx-auto px-4 py-24 text-center">
        <div class="inline-flex items-center gap-2 bg-white/20 text-white text-sm px-4 py-2 rounded-full mb-6 backdrop-blur-sm">
            <i class="fa-solid fa-map-pin animate-pulse"></i>
            <?php echo e($stats['destinations']); ?> <?php echo e(__('home.destinations_in')); ?> <?php echo e($stats['regions']); ?> <?php echo e(__('home.regions')); ?>

        </div>

        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 leading-tight">
            <?php echo __('home.hero_title'); ?>

        </h1>
        <p class="text-sky-100 text-lg mb-10 max-w-2xl mx-auto">
            <?php echo e(__('home.hero_subtitle')); ?>

        </p>

        
        <div class="max-w-2xl mx-auto relative">
            <div class="flex bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="flex-1 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="heroSearch" placeholder="<?php echo e(__('home.search_placeholder')); ?>"
                           class="w-full pl-11 pr-4 py-4 text-gray-800 focus:outline-none text-sm">
                </div>
                <button onclick="doHeroSearch()"
                        class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-6 transition-colors text-sm">
                    <?php echo e(__('home.search_btn')); ?>

                </button>
            </div>

            
            <div id="searchDropdown"
                 class="absolute top-full left-0 right-0 bg-white rounded-2xl shadow-xl border border-gray-100 mt-2 overflow-hidden z-50 hidden">
            </div>
        </div>

        
        <div class="flex flex-wrap justify-center gap-2 mt-8">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('destinations.index', ['category' => $cat->slug])); ?>"
               class="flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white text-sm px-4 py-2 rounded-full backdrop-blur-sm transition-all duration-200 border border-white/30">
                <i class="fa-solid <?php echo e($cat->icon ?? 'fa-star'); ?> text-xs"></i>
                <?php echo e($cat->localized_name); ?>

            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 py-8 grid grid-cols-3 gap-6 text-center">
        <div>
            <p class="text-3xl font-extrabold text-sky-600"><?php echo e($stats['destinations']); ?>+</p>
            <p class="text-sm text-gray-500 mt-1"><?php echo e(__('home.stat_destinations')); ?></p>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-sky-600"><?php echo e($stats['regions']); ?>+</p>
            <p class="text-sm text-gray-500 mt-1"><?php echo e(__('home.stat_regions')); ?></p>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-sky-600"><?php echo e($stats['categories']); ?></p>
            <p class="text-sm text-gray-500 mt-1"><?php echo e(__('home.stat_categories')); ?></p>
        </div>
    </div>
</section>


<section class="max-w-7xl mx-auto px-4 py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800"><?php echo e(__('home.popular_title')); ?></h2>
            <p class="text-gray-500 mt-1"><?php echo e(__('home.popular_subtitle')); ?></p>
        </div>
        <a href="<?php echo e(route('destinations.index')); ?>"
           class="text-sky-600 hover:text-sky-700 text-sm font-semibold flex items-center gap-1 transition-colors">
            <?php echo e(__('home.see_all')); ?> <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__currentLoopData = $popularDestinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('destinations.show', $dest->slug)); ?>"
           class="card-hover bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 group block">
            
            <div class="relative h-48 overflow-hidden bg-gray-100">
                <?php if($dest->thumbnail): ?>
                    <img src="<?php echo e(asset('storage/'.$dest->thumbnail)); ?>" alt="<?php echo e($dest->name); ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <i class="fa-solid fa-image text-5xl"></i>
                    </div>
                <?php endif; ?>
                
                <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-semibold text-white"
                      style="background-color: <?php echo e($dest->category?->color ?? '#3b82f6'); ?>">
                    <?php echo e($dest->category?->localized_name); ?>

                </span>
            </div>
            
            <div class="p-4">
                <h3 class="font-semibold text-gray-800 group-hover:text-sky-600 transition-colors"><?php echo e($dest->localized_name); ?></h3>
                <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-location-dot text-sky-400 text-xs"></i>
                    <?php echo e($dest->region); ?>

                </p>
                <div class="flex items-center justify-between mt-3">
                    <div class="flex items-center gap-1 text-amber-500 text-sm">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fa-<?php echo e($i <= $dest->rating_avg ? 'solid' : 'regular'); ?> fa-star text-xs"></i>
                        <?php endfor; ?>
                        <span class="text-gray-500 text-xs ml-1">(<?php echo e($dest->review_count); ?>)</span>
                    </div>
                    <span class="text-sm font-semibold text-sky-600"><?php echo e($dest->ticket_price ?? __('home.free')); ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>


<section class="bg-gradient-to-r from-slate-800 to-slate-700 py-16">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <i class="fa-solid fa-map text-sky-400 text-5xl mb-4"></i>
        <h2 class="text-3xl font-bold text-white mb-3"><?php echo e(__('home.map_cta_title')); ?></h2>
        <p class="text-slate-300 mb-8 max-w-xl mx-auto"><?php echo e(__('home.map_cta_desc')); ?></p>
        <a href="<?php echo e(route('map')); ?>"
           class="inline-flex items-center gap-3 bg-sky-500 hover:bg-sky-400 text-white font-bold px-8 py-4 rounded-2xl transition-all duration-200 shadow-lg hover:shadow-sky-500/30">
            <i class="fa-solid fa-map-location-dot"></i>
            <?php echo e(__('home.map_cta_btn')); ?>

        </a>
    </div>
</section>


<?php if($upcomingEvents->count() > 0): ?>
<section class="max-w-7xl mx-auto px-4 py-16">
    <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo e(__('home.events_title')); ?></h2>
    <p class="text-gray-500 mb-8"><?php echo e(__('home.events_subtitle')); ?></p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <?php $__currentLoopData = $upcomingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('events.show', $event->slug)); ?>"
           class="card-hover bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm block group">
            <?php if($event->thumbnail): ?>
                <img src="<?php echo e(asset('storage/'.$event->thumbnail)); ?>" alt="<?php echo e($event->title); ?>"
                     class="w-full h-36 object-cover group-hover:scale-105 transition-transform duration-300">
            <?php endif; ?>
            <div class="p-4">
                <div class="flex items-center gap-2 text-sky-600 text-xs font-semibold mb-2">
                    <i class="fa-solid fa-calendar-days"></i>
                    <?php echo e($event->start_date->format('d M Y')); ?>

                    <?php if($event->start_date != $event->end_date): ?>
                        — <?php echo e($event->end_date->format('d M Y')); ?>

                    <?php endif; ?>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm group-hover:text-sky-600 transition-colors line-clamp-2">
                    <?php echo e($event->localized_title); ?>

                </h3>
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-location-dot"></i> <?php echo e($event->location); ?>

                </p>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('visitor.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\code neerrr\Project\Project Rekomendasi Tempat Wisata\wisata-daerah\resources\views/visitor/home/index.blade.php ENDPATH**/ ?>