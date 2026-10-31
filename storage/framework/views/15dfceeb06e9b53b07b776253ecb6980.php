<?php $__env->startSection('title', $destination->localized_name . ' — WisataDaerah'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    #detailMap { height: 280px; border-radius: 16px; }
    .gallery-item { cursor: pointer; transition: transform .2s, opacity .2s; }
    .gallery-item:hover { transform: scale(1.03); opacity: .9; }
    /* Lightbox overlay */
    #lightbox { backdrop-filter: blur(8px); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 py-10">

    
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="<?php echo e(route('home')); ?>" class="hover:text-sky-500 transition-colors"><?php echo e(__('nav.home')); ?></a>
        <i class="fa-solid fa-chevron-right text-xs"></i>
        <a href="<?php echo e(route('destinations.index')); ?>" class="hover:text-sky-500 transition-colors"><?php echo e(__('nav.destinations')); ?></a>
        <i class="fa-solid fa-chevron-right text-xs"></i>
        <span class="text-gray-600 font-medium"><?php echo e($destination->localized_name); ?></span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        
        <div class="lg:col-span-2 space-y-6">

            
            <div class="relative rounded-2xl overflow-hidden bg-gray-100 h-72 md:h-96">
                <?php if($destination->thumbnail): ?>
                    <img id="mainImage" src="<?php echo e(asset('storage/'.$destination->thumbnail)); ?>"
                         alt="<?php echo e($destination->name); ?>"
                         class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <i class="fa-solid fa-image text-6xl"></i>
                    </div>
                <?php endif; ?>
                
                <span class="absolute top-4 left-4 px-3 py-1.5 rounded-full text-sm font-semibold text-white shadow"
                      style="background-color: <?php echo e($destination->category?->color ?? '#3b82f6'); ?>">
                    <i class="fa-solid <?php echo e($destination->category?->icon ?? 'fa-star'); ?> mr-1"></i>
                    <?php echo e($destination->category?->localized_name); ?>

                </span>
            </div>

            
            <?php if($destination->images->count() > 0): ?>
            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                <?php $__currentLoopData = $destination->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="gallery-item rounded-xl overflow-hidden h-16 bg-gray-100"
                     onclick="openLightbox('<?php echo e(asset('storage/'.$img->image_path)); ?>')">
                    <img src="<?php echo e(asset('storage/'.$img->image_path)); ?>" alt="<?php echo e($img->caption); ?>"
                         class="w-full h-full object-cover">
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900"><?php echo e($destination->localized_name); ?></h1>
                <div class="flex items-center gap-3 mt-2 flex-wrap">
                    <div class="flex items-center gap-1 text-amber-500">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fa-<?php echo e($i <= $destination->rating_avg ? 'solid' : 'regular'); ?> fa-star"></i>
                        <?php endfor; ?>
                        <span class="text-gray-600 text-sm ml-1 font-semibold">
                            <?php echo e($destination->rating_avg); ?>/5 (<?php echo e($destination->review_count); ?> <?php echo e(__('dest.reviews')); ?>)
                        </span>
                    </div>
                    <span class="text-gray-300">|</span>
                    <span class="text-sm text-gray-500 flex items-center gap-1">
                        <i class="fa-solid fa-eye text-gray-400"></i>
                        <?php echo e(number_format($destination->view_count)); ?> <?php echo e(__('dest.views')); ?>

                    </span>
                </div>
                <p class="text-gray-500 text-sm flex items-center gap-1 mt-2">
                    <i class="fa-solid fa-location-dot text-sky-500"></i>
                    <?php echo e($destination->address); ?>, <?php echo e($destination->region); ?>

                </p>
            </div>

            
            <div class="prose prose-gray max-w-none">
                <p class="text-gray-700 leading-relaxed"><?php echo e($destination->localized_description); ?></p>
            </div>

            
            <?php if($destination->facilities->count() > 0): ?>
            <div class="bg-gray-50 rounded-2xl p-5">
                <h3 class="font-semibold text-gray-700 mb-3">
                    <i class="fa-solid fa-circle-check text-green-500 mr-2"></i><?php echo e(__('dest.facilities')); ?>

                </h3>
                <div class="flex flex-wrap gap-2">
                    <?php $__currentLoopData = $destination->facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fac): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-full px-3 py-1.5 text-sm text-gray-700">
                        <i class="fa-solid <?php echo e($fac->icon ?? 'fa-check'); ?> text-sky-500 text-xs"></i>
                        <?php echo e($fac->localized_name); ?>

                    </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            
            <div>
                <h3 class="font-semibold text-gray-700 mb-3">
                    <i class="fa-solid fa-map-location-dot text-sky-500 mr-2"></i><?php echo e(__('dest.location_map')); ?>

                </h3>
                <div id="detailMap" class="w-full border border-gray-200"></div>
                <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo e($destination->latitude); ?>,<?php echo e($destination->longitude); ?>"
                   target="_blank" rel="noopener"
                   class="mt-3 inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2.5 rounded-xl transition-colors text-sm">
                    <i class="fa-brands fa-google"></i>
                    <?php echo e(__('dest.route_btn')); ?>

                </a>
            </div>

            
            <div id="reviews-section">
                <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-comments text-sky-500"></i>
                    <?php echo e(__('dest.reviews_title')); ?>

                    <span class="bg-sky-100 text-sky-700 text-xs font-bold px-2.5 py-0.5 rounded-full">
                        <?php echo e($destination->review_count); ?>

                    </span>
                </h3>

                
                <?php if($destination->review_count > 0): ?>
                <div class="bg-gray-50 rounded-2xl p-5 mb-5">
                    <div class="flex items-center gap-5">
                        <div class="text-center">
                            <p class="text-4xl font-extrabold text-gray-800"><?php echo e($destination->rating_avg); ?></p>
                            <div class="text-amber-500 text-xs mt-1">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa-<?php echo e($i <= $destination->rating_avg ? 'solid' : 'regular'); ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="flex-1 space-y-1.5">
                            <?php for($star = 5; $star >= 1; $star--): ?>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-gray-500 w-8"><?php echo e($star); ?> ★</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-amber-400 h-2 rounded-full"
                                         style="width: <?php echo e($destination->review_count > 0 ? ($ratingDistribution[$star] / $destination->review_count) * 100 : 0); ?>%">
                                    </div>
                                </div>
                                <span class="text-gray-400 w-5"><?php echo e($ratingDistribution[$star]); ?></span>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                
                <div class="space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $destination->approvedReviews->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 bg-sky-100 rounded-full flex items-center justify-center text-sky-600 font-bold flex-shrink-0">
                                <?php echo e(substr($review->reviewer_name, 0, 1)); ?>

                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-sm text-gray-800"><?php echo e($review->reviewer_name); ?></span>
                                    <span class="text-xs text-gray-400"><?php echo e($review->created_at->diffForHumans()); ?></span>
                                </div>
                                <div class="text-amber-500 text-xs mt-0.5">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa-<?php echo e($i <= $review->rating ? 'solid' : 'regular'); ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <?php if($review->title): ?>
                                    <p class="font-medium text-gray-700 mt-1.5 text-sm"><?php echo e($review->title); ?></p>
                                <?php endif; ?>
                                <p class="text-gray-600 text-sm mt-1 leading-relaxed"><?php echo e($review->comment); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-400 text-sm text-center py-8"><?php echo e(__('dest.no_reviews')); ?></p>
                    <?php endif; ?>
                </div>

                
                <div class="mt-6 bg-sky-50 border border-sky-100 rounded-2xl p-5">
                    <h4 class="font-semibold text-gray-700 mb-4">
                        <i class="fa-solid fa-pen-to-square text-sky-500 mr-2"></i><?php echo e(__('dest.write_review')); ?>

                    </h4>
                    <form action="<?php echo e(route('reviews.store', $destination->id)); ?>" method="POST" class="space-y-3">
                        <?php echo csrf_field(); ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <input type="text" name="reviewer_name" placeholder="<?php echo e(__('dest.your_name')); ?>" required
                                       value="<?php echo e(old('reviewer_name', auth()->user()?->name)); ?>"
                                       class="w-full border border-gray-200 bg-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                            </div>
                            <div>
                                <input type="email" name="reviewer_email" placeholder="<?php echo e(__('dest.your_email')); ?>"
                                       value="<?php echo e(old('reviewer_email', auth()->user()?->email)); ?>"
                                       class="w-full border border-gray-200 bg-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                            </div>
                        </div>

                        
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600"><?php echo e(__('dest.rating')); ?>:</span>
                            <div class="flex gap-1" id="starRating">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                <button type="button" data-val="<?php echo e($i); ?>"
                                        class="star-btn text-2xl text-gray-300 hover:text-amber-400 transition-colors">★</button>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="<?php echo e(old('rating', 5)); ?>" required>
                        </div>

                        <input type="text" name="title" placeholder="<?php echo e(__('dest.review_title')); ?>"
                               value="<?php echo e(old('title')); ?>"
                               class="w-full border border-gray-200 bg-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">

                        <textarea name="comment" rows="3" required placeholder="<?php echo e(__('dest.review_comment')); ?>"
                                  class="w-full border border-gray-200 bg-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400"><?php echo e(old('comment')); ?></textarea>

                        <button type="submit"
                                class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors text-sm">
                            <i class="fa-solid fa-paper-plane mr-2"></i><?php echo e(__('dest.submit_review')); ?>

                        </button>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="space-y-4">
            
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-3">
                <h3 class="font-semibold text-gray-700 border-b pb-3"><?php echo e(__('dest.info_title')); ?></h3>
                <div class="flex items-start gap-3 text-sm">
                    <i class="fa-solid fa-clock text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-gray-700"><?php echo e(__('dest.open_hours')); ?></p>
                        <p class="text-gray-500"><?php echo e($destination->open_hours ?? __('dest.not_available')); ?></p>
                    </div>
                </div>
                <div class="flex items-start gap-3 text-sm">
                    <i class="fa-solid fa-ticket text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-gray-700"><?php echo e(__('dest.ticket')); ?></p>
                        <p class="text-gray-500">
                            <?php echo e(app()->getLocale() === 'en' && $destination->ticket_price_en
                                ? $destination->ticket_price_en
                                : ($destination->ticket_price ?? __('home.free'))); ?>

                        </p>
                    </div>
                </div>
                <?php if($destination->phone): ?>
                <div class="flex items-start gap-3 text-sm">
                    <i class="fa-solid fa-phone text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-gray-700"><?php echo e(__('dest.phone')); ?></p>
                        <a href="tel:<?php echo e($destination->phone); ?>" class="text-sky-500 hover:underline"><?php echo e($destination->phone); ?></a>
                    </div>
                </div>
                <?php endif; ?>
                <?php if($destination->website): ?>
                <div class="flex items-start gap-3 text-sm">
                    <i class="fa-solid fa-globe text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-gray-700"><?php echo e(__('dest.website')); ?></p>
                        <a href="<?php echo e($destination->website); ?>" target="_blank" rel="noopener"
                           class="text-sky-500 hover:underline break-all text-xs"><?php echo e($destination->website); ?></a>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            
            <?php if($related->count() > 0): ?>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-700 mb-4"><?php echo e(__('dest.related')); ?></h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('destinations.show', $rel->slug)); ?>"
                       class="flex items-center gap-3 hover:bg-gray-50 rounded-xl p-2 transition-colors">
                        <?php if($rel->thumbnail): ?>
                            <img src="<?php echo e(asset('storage/'.$rel->thumbnail)); ?>" alt=""
                                 class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                        <?php else: ?>
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-300 flex-shrink-0">
                                <i class="fa-solid fa-image text-xs"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <p class="text-sm font-medium text-gray-800 leading-tight"><?php echo e($rel->localized_name); ?></p>
                            <p class="text-xs text-gray-400"><?php echo e($rel->region); ?></p>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div id="lightbox" class="fixed inset-0 bg-black/80 z-[9999] hidden flex items-center justify-center p-4"
     onclick="closeLightbox()">
    <img id="lightboxImg" src="" alt="" class="max-w-full max-h-full rounded-xl shadow-2xl">
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/**
 * Inisialisasi peta lokasi destinasi (read-only, bukan pin editor).
 * Menampilkan marker tunggal di koordinat destinasi.
 */
(function initDetailMap() {
    const lat = <?php echo e($destination->latitude); ?>;
    const lng = <?php echo e($destination->longitude); ?>;

    const map = L.map('detailMap').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    // Marker destinasi
    L.marker([lat, lng])
        .addTo(map)
        .bindPopup('<b><?php echo e(addslashes($destination->name)); ?></b><br><?php echo e(addslashes($destination->address)); ?>')
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('visitor.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\code neerrr\Project\Project Rekomendasi Tempat Wisata\wisata-daerah\resources\views/visitor/destinations/show.blade.php ENDPATH**/ ?>