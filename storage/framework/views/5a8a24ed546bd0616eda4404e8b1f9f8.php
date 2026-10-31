<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — WisataDaerah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-sky-500 to-cyan-600 flex items-center justify-center p-4">

<div class="w-full max-w-md">
    
    <div class="text-center mb-8">
        <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2 text-white font-bold text-2xl">
            <i class="fa-solid fa-map-location-dot text-3xl"></i> WisataDaerah
        </a>
        <p class="text-sky-100 mt-2 text-sm">Masuk ke akun Anda</p>
    </div>

    <div class="bg-white rounded-3xl shadow-2xl p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Selamat Datang Kembali</h2>

        <form action="<?php echo e(route('login')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                       class="w-full border <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-sky-600 rounded">
                    <span class="text-gray-600">Ingat saya</span>
                </label>
            </div>
            <button type="submit"
                    class="w-full bg-sky-500 hover:bg-sky-600 text-white font-bold py-3.5 rounded-xl transition-colors">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Belum punya akun?
            <a href="<?php echo e(route('register')); ?>" class="text-sky-600 font-semibold hover:underline">Daftar sekarang</a>
        </p>
    </div>
</div>
</body>
</html>
<?php /**PATH D:\code neerrr\Project\Project Rekomendasi Tempat Wisata\wisata-daerah\resources\views/auth/login.blade.php ENDPATH**/ ?>