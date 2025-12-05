<?php $__env->startSection('title', 'Kasa - BMCODEX'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 1000px; margin: 3rem auto;">
    <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--text-light); border-bottom: 2px solid var(--primary-color); padding-bottom: 1rem;">
        Finalizacja zamówienia
    </h1>

    <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem;">
        <!-- Formularz zamówienia -->
        <div>
            <form action="<?php echo e(route('order.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <!-- Dane do wysyłki -->
                <div style="background-color: var(--light-gray); padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h2 style="font-size: 1.8rem; margin-bottom: 1.5rem; color: var(--text-light);">Adres dostawy</h2>
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light);">Adres dostawy *</label>
                        <textarea name="shipping_address" required rows="4" style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;"><?php echo e(old('shipping_address')); ?></textarea>
                        <?php $__errorArgs = ['shipping_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #dc3545; font-size: 0.9rem;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Dane do faktury -->
                <div style="background-color: var(--light-gray); padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h2 style="font-size: 1.8rem; margin-bottom: 1.5rem; color: var(--text-light);">Adres do faktury</h2>
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="display: flex; align-items: center; color: var(--text-light); cursor: pointer;">
                            <input type="checkbox" id="same_address" checked style="margin-right: 0.5rem;">
                            Taki sam jak adres dostawy
                        </label>
                    </div>
                    
                    <div id="billing_address_field" style="display: none;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light);">Adres do faktury *</label>
                        <textarea name="billing_address" rows="4" style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;"><?php echo e(old('billing_address')); ?></textarea>
                        <?php $__errorArgs = ['billing_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #dc3545; font-size: 0.9rem;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <?php if(auth()->guard()->guest()): ?>
                <!-- Email dla gości -->
                <div style="background-color: var(--light-gray); padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h2 style="font-size: 1.8rem; margin-bottom: 1.5rem; color: var(--text-light);">Dane kontaktowe</h2>
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light);">Email *</label>
                        <input type="email" name="guest_email" required value="<?php echo e(old('guest_email')); ?>" style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">
                        <?php $__errorArgs = ['guest_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #dc3545; font-size: 0.9rem;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <?php endif; ?>

                <button type="submit" style="width: 100%; padding: 1.2rem; background-color: var(--primary-color); color: var(--dark-bg); border: none; border-radius: 4px; font-size: 1.3rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease;">
                    Złóż zamówienie
                </button>
            </form>
        </div>

        <!-- Podsumowanie zamówienia -->
        <div>
            <div style="background-color: var(--light-gray); padding: 2rem; border-radius: 8px; position: sticky; top: 2rem;">
                <h2 style="font-size: 1.8rem; margin-bottom: 1.5rem; color: var(--text-light); border-bottom: 2px solid var(--primary-color); padding-bottom: 0.5rem;">
                    Twoje zamówienie
                </h2>

                <div style="margin-bottom: 1.5rem;">
                    <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid #333; color: var(--text-light);">
                            <div>
                                <div style="font-weight: 600;"><?php echo e($item->product->name); ?></div>
                                <div style="color: #999; font-size: 0.9rem;">Ilość: <?php echo e($item->quantity); ?></div>
                            </div>
                            <div style="font-weight: 700; color: var(--primary-color);">
                                <?php echo e(number_format($item->product->price * $item->quantity, 2)); ?> PLN
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid #333; color: var(--text-light);">
                    <span>Produkty</span>
                    <span><?php echo e(number_format($total, 2)); ?> PLN</span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid #333; color: var(--text-light);">
                    <span>Dostawa</span>
                    <span style="color: #28a745; font-weight: 600;">Gratis</span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 1.5rem 0; font-size: 1.5rem; font-weight: 700; color: var(--primary-color);">
                    <span>Razem</span>
                    <span><?php echo e(number_format($total, 2)); ?> PLN</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('same_address').addEventListener('change', function() {
    const billingField = document.getElementById('billing_address_field');
    const billingTextarea = billingField.querySelector('textarea');
    const shippingTextarea = document.querySelector('textarea[name="shipping_address"]');
    
    if (this.checked) {
        billingField.style.display = 'none';
        billingTextarea.value = shippingTextarea.value;
        billingTextarea.removeAttribute('required');
    } else {
        billingField.style.display = 'block';
        billingTextarea.setAttribute('required', 'required');
    }
});

// Synchronizuj adresy gdy checkbox jest zaznaczony
document.querySelector('textarea[name="shipping_address"]').addEventListener('input', function() {
    const sameAddress = document.getElementById('same_address');
    if (sameAddress.checked) {
        document.querySelector('textarea[name="billing_address"]').value = this.value;
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /resources/views/checkout.blade.php ENDPATH**/ ?>