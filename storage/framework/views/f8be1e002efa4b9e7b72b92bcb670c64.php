<?php $__env->startSection('title', 'Logowanie - BMCODEX'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 500px; margin: 4rem auto;">
    <div style="background-color: var(--light-gray); padding: 3rem; border-radius: 8px; border: 2px solid var(--primary-color);">
        <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--text-light); text-align: center;">
            Logowanie
        </h1>

        <form action="<?php echo e(route('login.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light); font-weight: 600;">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus 
                    style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span style="color: #dc3545; font-size: 0.9rem; margin-top: 0.3rem; display: block;"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light); font-weight: 600;">Hasło</label>
                <input type="password" name="password" required 
                    style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span style="color: #dc3545; font-size: 0.9rem; margin-top: 0.3rem; display: block;"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; color: var(--text-light); cursor: pointer;">
                    <input type="checkbox" name="remember" style="margin-right: 0.5rem;">
                    Zapamiętaj mnie
                </label>
            </div>

            <button type="submit" 
                style="width: 100%; padding: 1rem; background-color: var(--primary-color); color: var(--dark-bg); border: none; border-radius: 4px; font-size: 1.2rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; margin-bottom: 1rem;">
                Zaloguj się
            </button>

            <div style="text-align: center; color: var(--text-light);">
                Nie masz konta? 
                <a href="<?php echo e(route('register')); ?>" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">
                    Zarejestruj się
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /resources/views/login.blade.php ENDPATH**/ ?>