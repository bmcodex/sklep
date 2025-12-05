<?php $__env->startSection('title', $product->name . ' - BMCODEX'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .product-detail {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin: 3rem 0;
    }
    
    .product-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid var(--primary-color);
    }
    
    .product-info h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: var(--text-light);
    }
    
    .product-category {
        display: inline-block;
        background-color: var(--primary-color);
        color: var(--dark-bg);
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    
    .product-price {
        font-size: 2.5rem;
        color: var(--primary-color);
        font-weight: 700;
        margin: 1.5rem 0;
    }
    
    .product-description {
        line-height: 1.8;
        margin: 1.5rem 0;
        color: var(--text-light);
    }
    
    .product-meta {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin: 2rem 0;
        padding: 1.5rem;
        background-color: var(--light-gray);
        border-radius: 8px;
    }
    
    .meta-item {
        display: flex;
        flex-direction: column;
    }
    
    .meta-label {
        font-size: 0.9rem;
        color: #999;
        margin-bottom: 0.3rem;
    }
    
    .meta-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-light);
    }
    
    .stock-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: 600;
    }
    
    .stock-status.in-stock {
        background-color: #28a745;
        color: white;
    }
    
    .stock-status.low-stock {
        background-color: #ffc107;
        color: #000;
    }
    
    .stock-status.out-of-stock {
        background-color: #dc3545;
        color: white;
    }
    
    .add-to-cart-form {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .quantity-input {
        width: 100px;
        padding: 0.8rem;
        border: 2px solid var(--primary-color);
        background-color: var(--dark-bg);
        color: var(--text-light);
        border-radius: 4px;
        font-size: 1.1rem;
        text-align: center;
    }
    
    .btn-add-cart {
        flex: 1;
        padding: 1rem 2rem;
        background-color: var(--primary-color);
        color: var(--dark-bg);
        border: none;
        border-radius: 4px;
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-add-cart:hover {
        background-color: #ff6a33;
        transform: translateY(-2px);
    }
    
    .btn-add-cart:disabled {
        background-color: #666;
        cursor: not-allowed;
        transform: none;
    }
    
    .related-products {
        margin-top: 4rem;
    }
    
    .related-products h2 {
        font-size: 2rem;
        margin-bottom: 2rem;
        color: var(--text-light);
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .product-detail {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .product-info h1 {
            font-size: 2rem;
        }
        
        .product-price {
            font-size: 2rem;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Breadcrumb -->
    <nav style="margin: 2rem 0; color: #999;">
        <a href="<?php echo e(route('home')); ?>" style="color: var(--primary-color);">Strona główna</a> / 
        <?php if($product->category): ?>
            <a href="<?php echo e(route('products.category', $product->category)); ?>" style="color: var(--primary-color);"><?php echo e($product->category->name); ?></a> / 
        <?php endif; ?>
        <span><?php echo e($product->name); ?></span>
    </nav>

    <div class="product-detail">
        <!-- Product Image -->
        <div>
            <?php if($product->image_url): ?>
                <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" class="product-image">
            <?php else: ?>
                <img src="https://via.placeholder.com/500x500/1A1A1A/FF4500?text=<?php echo e(urlencode($product->name)); ?>" alt="<?php echo e($product->name); ?>" class="product-image">
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="product-info">
            <?php if($product->category): ?>
                <span class="product-category"><?php echo e($product->category->name); ?></span>
            <?php endif; ?>
            
            <h1><?php echo e($product->name); ?></h1>
            
            <div class="product-price"><?php echo e(number_format($product->price, 2)); ?> PLN</div>
            
            <!-- Stock Status -->
            <?php if($product->stock > 10): ?>
                <span class="stock-status in-stock">✓ Dostępny (<?php echo e($product->stock); ?> szt.)</span>
            <?php elseif($product->stock > 0): ?>
                <span class="stock-status low-stock">⚠ Ostatnie sztuki (<?php echo e($product->stock); ?> szt.)</span>
            <?php else: ?>
                <span class="stock-status out-of-stock">✗ Brak w magazynie</span>
            <?php endif; ?>
            
            <div class="product-description">
                <?php echo e($product->description ?? 'Brak opisu produktu.'); ?>

            </div>
            
            <!-- Product Meta -->
            <div class="product-meta">
                <div class="meta-item">
                    <span class="meta-label">SKU</span>
                    <span class="meta-value"><?php echo e($product->sku); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Kategoria</span>
                    <span class="meta-value"><?php echo e($product->category->name ?? 'Brak'); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Dostępność</span>
                    <span class="meta-value"><?php echo e($product->stock); ?> szt.</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Cena</span>
                    <span class="meta-value"><?php echo e(number_format($product->price, 2)); ?> PLN</span>
                </div>
            </div>
            
            <!-- Add to Cart Form -->
            <?php if($product->stock > 0): ?>
                <form action="<?php echo e(route('cart.add', $product)); ?>" method="POST" class="add-to-cart-form">
                    <?php echo csrf_field(); ?>
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo e($product->stock); ?>" class="quantity-input" required>
                    <button type="submit" class="btn-add-cart">Dodaj do koszyka</button>
                </form>
            <?php else: ?>
                <button class="btn-add-cart" disabled>Produkt niedostępny</button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Related Products -->
    <?php if($relatedProducts->count() > 0): ?>
        <div class="related-products">
            <h2>Podobne produkty</h2>
            <div class="products-grid">
                <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="product-card">
                        <div class="product-image-wrapper">
                            <?php if($relatedProduct->image_url): ?>
                                <img src="<?php echo e($relatedProduct->image_url); ?>" alt="<?php echo e($relatedProduct->name); ?>" class="product-img">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/300x300/1A1A1A/FF4500?text=<?php echo e(urlencode($relatedProduct->name)); ?>" alt="<?php echo e($relatedProduct->name); ?>" class="product-img">
                            <?php endif; ?>
                            <?php if($relatedProduct->stock <= 5 && $relatedProduct->stock > 0): ?>
                                <span class="badge badge-warning">Ostatnie sztuki!</span>
                            <?php elseif($relatedProduct->stock == 0): ?>
                                <span class="badge badge-danger">Brak w magazynie</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-details">
                            <span class="product-category-tag"><?php echo e($relatedProduct->category->name ?? 'Brak kategorii'); ?></span>
                            <h3 class="product-name"><?php echo e($relatedProduct->name); ?></h3>
                            <p class="product-desc"><?php echo e(Str::limit($relatedProduct->description, 100)); ?></p>
                            <div class="product-footer">
                                <span class="product-price"><?php echo e(number_format($relatedProduct->price, 2)); ?> PLN</span>
                                <a href="<?php echo e(route('products.show', $relatedProduct)); ?>" class="btn-details">Szczegóły</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /resources/views/product-detail.blade.php ENDPATH**/ ?>