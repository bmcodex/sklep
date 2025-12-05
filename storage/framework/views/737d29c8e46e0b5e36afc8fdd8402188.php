<?php $__env->startSection('title', 'Ulubione produkty - BMCODEX'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .favorites-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
    }
    
    .favorites-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .favorite-card {
        background-color: var(--light-gray);
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid var(--primary-color);
        transition: transform 0.3s;
        position: relative;
    }
    
    .favorite-card:hover {
        transform: translateY(-5px);
    }
    
    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background-color: var(--dark-bg);
    }
    
    .product-info {
        padding: 1.5rem;
    }
    
    .product-category {
        color: var(--primary-color);
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    
    .product-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-light);
        margin-bottom: 0.5rem;
    }
    
    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 1rem 0;
    }
    
    .product-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .btn-remove {
        flex: 1;
        background-color: #dc3545;
        color: white;
    }
    
    .btn-remove:hover {
        background-color: #c82333;
    }
    
    .btn-view {
        flex: 1;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-gray);
    }
    
    .empty-state h2 {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: var(--text-light);
    }
    
    .empty-state p {
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }
    
    @media (max-width: 768px) {
        .favorites-container {
            padding: 1rem;
        }
        
        .favorites-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="favorites-container">
    <h1>❤️ Moje ulubione produkty</h1>
    
    <?php if($favorites->count() > 0): ?>
        <div class="favorites-grid">
            <?php $__currentLoopData = $favorites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $favorite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="favorite-card">
                    <img src="<?php echo e($favorite->product->image_url ?? 'https://via.placeholder.com/300x200?text=Brak+zdjęcia'); ?>" 
                         alt="<?php echo e($favorite->product->name); ?>" 
                         class="product-image">
                    
                    <div class="product-info">
                        <div class="product-category">
                            <?php echo e($favorite->product->category->name ?? 'Bez kategorii'); ?>

                        </div>
                        
                        <h3 class="product-name"><?php echo e($favorite->product->name); ?></h3>
                        
                        <div class="product-price">
                            <?php echo e(number_format($favorite->product->price, 2, ',', ' ')); ?> PLN
                        </div>
                        
                        <?php if($favorite->product->stock > 0): ?>
                            <span style="color: #28a745; font-size: 0.9rem;">
                                ✓ Dostępny (<?php echo e($favorite->product->stock); ?> szt.)
                            </span>
                        <?php else: ?>
                            <span style="color: #dc3545; font-size: 0.9rem;">
                                ⚠ Brak w magazynie
                            </span>
                        <?php endif; ?>
                        
                        <div class="product-actions">
                            <a href="<?php echo e(route('products.show', $favorite->product)); ?>" class="btn btn-primary btn-view">
                                Zobacz
                            </a>
                            
                            <form action="<?php echo e(route('favorites.remove', $favorite->product)); ?>" method="POST" style="flex: 1;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-remove" style="width: 100%;">
                                    Usuń
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h2>Brak ulubionych produktów</h2>
            <p>Nie dodałeś jeszcze żadnych produktów do ulubionych.</p>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary">
                Przeglądaj produkty
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /resources/views/favorites.blade.php ENDPATH**/ ?>