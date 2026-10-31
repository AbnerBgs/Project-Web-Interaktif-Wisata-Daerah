<?php $__env->startSection('title', __('dest.list_title') . ' — WisataDaerah'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-10">

    <h1 class="text-3xl font-extrabold text-gray-900 mb-2"><?php echo e(__('dest.list_title')); ?></h1>
    <p class="text-gray-500 mb-8"><?php echo e(__('dest.list_subtitle')); ?></p>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-8">
        <form id="filterForm" class="flex flex-wrap gap-3 items-end">
            
            <div class="flex-1 min-w-48">
                <label class="text-xs font-semibold text-gray-500 mb-1 block"><?php echo e(__('dest.search')); ?></label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                           placeholder="<?php echo e(__('dest.search_placeholder')); ?>"
                           class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            
            <div class="min-w-36">
                <label class="text-xs font-semibold text-gray-500 mb-1 block"><?php echo e(__('dest.category')); ?></label>
                <select name="category"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value=""><?php echo e(__('dest.all_categories')); ?></option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->slug); ?>" <?php echo e(request('category') === $cat->slug ? 'selected' : ''); ?>>
                            <?php echo e($cat->localized_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="min-w-36">
                <label class="text-xs font-semibold text-gray-500 mb-1 block"><?php echo e(__('dest.region')); ?></label>
                <select name="region"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value=""><?php echo e(__('dest.all_regions')); ?></option>
                    <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($region); ?>" <?php echo e(request('region') === $region ? 'selected' : ''); ?>>
                            <?php echo e($region); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <button type="submit"
                    class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-5 py-2.5 rounded-xl transition-colors text-sm self-end">
                <i class="fa-solid fa-filter mr-1"></i><?php echo e(__('dest.filter_btn')); ?>

            </button>
            <?php if(request()->hasAny(['search', 'category', 'region'])): ?>
            <a href="<?php echo e(route('destinations.index')); ?>"
               class="text-sm text-gray-400 hover:text-red-500 self-end py-2.5 transition-colors">
                <i class="fa-solid fa-xmark mr-1"></i><?php echo e(__('dest.reset')); ?>

            </a>
            <?php endif; ?>
        </form>
    </div>

    
    <div class="flex items-center justify-between mb-4">
        <p class="text-sm text-gray-500">
            <?php echo e($destinations->total()); ?> <?php echo e(__('dest.results')); ?>

        </p>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        <?php $__empty_1 = true; $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <a href="<?php echo e(route('destinations.show', $dest->slug)); ?>"
           class="card-hover bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 block group">
            <div class="relative h-44 overflow-hidden bg-gray-100">
                <?php if($dest->thumbnail): ?>
                    <img src="<?php echo e(asset('storage/'.$dest->thumbnail)); ?>" alt="<?php echo e($dest->name); ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-gray-200">
                        <i class="fa-solid fa-image text-4xl"></i>
                    </div>
                <?php endif; ?>
                <span class="absolute top-2 left-2 px-2.5 py-1 rounded-full text-xs font-semibold text-white"
                      style="background-color: <?php echo e($dest->category?->color ?? '#3b82f6'); ?>">
                    <?php echo e($dest->category?->localized_name); ?>

                </span>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-gray-800 group-hover:text-sky-600 transition-colors leading-tight line-clamp-2">
                    <?php echo e($dest->localized_name); ?>

                </h3>
                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-location-dot text-sky-400"></i> <?php echo e($dest->region); ?>

                </p>
                <div class="flex items-center justify-between mt-2.5">
                    <span class="text-xs text-amber-500 flex items-center gap-0.5">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fa-<?php echo e($i <= $dest->rating_avg ? 'solid' : 'regular'); ?> fa-star"></i>
                        <?php endfor; ?>
                        <span class="text-gray-400 ml-1">(<?php echo e($dest->review_count); ?>)</span>
                    </span>
                    <span class="text-xs font-semibold text-sky-600">
                        <?php echo e($dest->ticket_price ?? __('home.free')); ?>

                    </span>
                </div>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full py-20 text-center text-gray-400">
            <i class="fa-solid fa-face-sad-tear text-5xl mb-4 block opacity-30"></i>
            <p class="text-lg font-medium"><?php echo e(__('dest.not_found')); ?></p>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="mt-8"><?php echo e($destinations->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('visitor.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\code neerrr\Project\Project Rekomendasi Tempat Wisata\wisata-daerah\resources\views/visitor/destinations/index.blade.php ENDPATH**/ ?>