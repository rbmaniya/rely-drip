import * as bootstrap from 'bootstrap';
import { initStorefrontFx } from './storefront-fx';

window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', () => {
    initQuantitySteppers();
    initProductGallery();
    initVariationSelector();
    initWishlistToggle();
    initStorefrontFx();
    initStarPicker();
    initImagePreview();
});

// -----------------------------------------------------------------------
// Generic image-preview file inputs (e.g. custom order design reference)
// -----------------------------------------------------------------------

function initImagePreview() {
    document.querySelectorAll('[data-image-preview-input]').forEach((input) => {
        input.addEventListener('change', () => {
            const preview = document.getElementById(input.dataset.imagePreviewInput);
            const file = input.files?.[0];
            if (!preview || !file) return;

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
            preview.nextElementSibling?.classList.add('d-none');
        });
    });
}

// -----------------------------------------------------------------------
// Star-rating picker (review form)
// -----------------------------------------------------------------------

function initStarPicker() {
    document.querySelectorAll('[data-star-picker]').forEach((picker) => {
        const input = picker.querySelector('[data-star-picker-input]');
        const stars = Array.from(picker.querySelectorAll('.star-picker-icon'));

        const paint = (value) => {
            stars.forEach((star) => {
                const active = Number(star.dataset.value) <= value;
                star.classList.toggle('is-active', active);
                star.classList.toggle('bi-star-fill', active);
                star.classList.toggle('bi-star', !active);
            });
        };

        stars.forEach((star) => {
            star.addEventListener('mouseenter', () => paint(Number(star.dataset.value)));
            star.addEventListener('click', () => {
                input.value = star.dataset.value;
                paint(Number(star.dataset.value));
            });
        });

        picker.addEventListener('mouseleave', () => paint(Number(input.value) || 0));
    });
}

// -----------------------------------------------------------------------
// Quantity steppers (cart lines, product detail "add to cart" form)
// -----------------------------------------------------------------------

function initQuantitySteppers() {
    document.querySelectorAll('[data-quantity-stepper]').forEach((stepper) => {
        const input = stepper.querySelector('input[type="number"]');
        if (!input) return;

        stepper.querySelectorAll('[data-quantity-step]').forEach((button) => {
            button.addEventListener('click', () => {
                const step = parseInt(button.dataset.quantityStep, 10);
                const min = parseInt(input.min || '1', 10);
                const max = input.max ? parseInt(input.max, 10) : Infinity;
                const next = (parseInt(input.value, 10) || min) + step;

                input.value = Math.min(Math.max(next, min), max);
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });
    });
}

// -----------------------------------------------------------------------
// Product gallery — click a thumbnail to swap the main image
// -----------------------------------------------------------------------

function initProductGallery() {
    const main = document.querySelector('[data-gallery-main]');
    if (!main) return;

    const videoWrap = document.querySelector('[data-gallery-video-wrap]');
    const videoEl = document.querySelector('[data-gallery-video]');
    const videoThumb = document.querySelector('[data-gallery-thumb-video]');
    const zoomBox = document.querySelector('[data-gallery-zoom]');
    const fullscreenButton = document.querySelector('[data-gallery-fullscreen]');

    const hideVideo = () => {
        if (!videoWrap || !videoEl) return;

        if (videoEl.tagName === 'VIDEO') videoEl.pause();
        videoEl.removeAttribute('src');
        videoEl.load?.();

        videoWrap.classList.add('d-none');
        zoomBox?.classList.remove('video-active');
        fullscreenButton?.classList.remove('d-none');
        videoThumb?.classList.remove('active');
    };

    const showVideo = () => {
        if (!videoWrap || !videoEl) return;

        videoEl.setAttribute('src', videoEl.dataset.src);
        if (videoEl.tagName === 'VIDEO') videoEl.play().catch(() => {});

        videoWrap.classList.remove('d-none');
        zoomBox?.classList.add('video-active');
        fullscreenButton?.classList.add('d-none');

        document.querySelectorAll('[data-gallery-thumb]').forEach((el) => el.classList.remove('active'));
        videoThumb?.classList.add('active');
    };

    document.querySelectorAll('[data-gallery-thumb]').forEach((thumb) => {
        thumb.addEventListener('click', () => {
            hideVideo();
            main.src = thumb.dataset.galleryThumb;

            document.querySelectorAll('[data-gallery-thumb]').forEach((el) => el.classList.remove('active'));
            thumb.classList.add('active');
        });
    });

    videoThumb?.addEventListener('click', showVideo);

    const thumbsTrack = document.querySelector('[data-gallery-thumbs]');
    document.querySelectorAll('[data-gallery-scroll]').forEach((button) => {
        button.addEventListener('click', () => {
            if (!thumbsTrack) return;
            const direction = parseInt(button.dataset.galleryScroll, 10);
            thumbsTrack.scrollBy({ left: direction * 160, behavior: 'smooth' });
        });
    });

    if (zoomBox) {
        zoomBox.addEventListener('mousemove', (event) => {
            const rect = zoomBox.getBoundingClientRect();
            const x = ((event.clientX - rect.left) / rect.width) * 100;
            const y = ((event.clientY - rect.top) / rect.height) * 100;
            main.style.transformOrigin = `${x}% ${y}%`;
        });

        zoomBox.addEventListener('mouseenter', () => zoomBox.classList.add('zoomed'));
        zoomBox.addEventListener('mouseleave', () => {
            zoomBox.classList.remove('zoomed');
            main.style.transformOrigin = 'center';
        });
    }

    const lightbox = document.querySelector('[data-gallery-lightbox]');
    const lightboxImg = document.querySelector('[data-gallery-lightbox-img]');

    if (lightbox && lightboxImg && fullscreenButton) {
        const openLightbox = () => {
            lightboxImg.src = main.src;
            lightbox.classList.add('open');
        };

        const closeLightbox = () => lightbox.classList.remove('open');

        fullscreenButton.addEventListener('click', openLightbox);
        lightbox.addEventListener('click', (event) => {
            if (event.target === lightbox || event.target.closest('[data-gallery-lightbox-close]')) {
                closeLightbox();
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeLightbox();
        });
    }
}

// -----------------------------------------------------------------------
// Metal → Color → Gold Purity cascading variation selector
// -----------------------------------------------------------------------

function initVariationSelector() {
    const root = document.querySelector('[data-variation-selector]');
    if (!root) return;

    let variations = [];
    try {
        variations = JSON.parse(document.getElementById('variations-data').textContent);
    } catch (e) {
        return;
    }

    const metalInputs = () => Array.from(root.querySelectorAll('[data-variation-field="metal"]'));
    const colorInputs = () => Array.from(root.querySelectorAll('[data-variation-field="color"]'));
    const purityInputs = () => Array.from(root.querySelectorAll('[data-variation-field="gold_purity"]'));
    const purityGroup = root.querySelector('[data-purity-group]');

    const variationIdField = root.querySelector('[data-variation-id-field]');
    const priceDisplay = document.querySelector('[data-variation-price]');
    const skuDisplay = root.querySelector('[data-variation-sku]');
    const stockDisplay = root.querySelector('[data-variation-stock]');
    const addToCartButtons = document.querySelectorAll('[data-add-to-cart-button]');
    const quantityInput = document.querySelector('[data-quantity-input]');

    function selectedValue(inputs) {
        const checked = inputs.find((input) => input.checked);
        return checked ? checked.value : null;
    }

    function refresh() {
        const metal = selectedValue(metalInputs());
        let color = selectedValue(colorInputs());

        // Disable colors that have no variation for the selected metal.
        colorInputs().forEach((input) => {
            const available = variations.some((v) => v.metal === metal && v.color === input.value);
            input.disabled = !available;
            if (!available && input.checked) input.checked = false;
        });

        if (!color || !colorInputs().find((i) => i.value === color && !i.disabled)) {
            const firstAvailable = colorInputs().find((i) => !i.disabled);
            if (firstAvailable) {
                firstAvailable.checked = true;
                color = firstAvailable.value;
            }
        }

        const showPurity = metal === 'gold';
        if (purityGroup) purityGroup.classList.toggle('d-none', !showPurity);

        let purity = showPurity ? selectedValue(purityInputs()) : null;

        if (showPurity) {
            purityInputs().forEach((input) => {
                const available = variations.some((v) => v.metal === metal && v.color === color && v.gold_purity === input.value);
                input.disabled = !available;
                if (!available && input.checked) input.checked = false;
            });

            if (!purity || !purityInputs().find((i) => i.value === purity && !i.disabled)) {
                const firstAvailable = purityInputs().find((i) => !i.disabled);
                if (firstAvailable) {
                    firstAvailable.checked = true;
                    purity = firstAvailable.value;
                }
            }
        }

        const match = variations.find((v) => v.metal === metal
            && v.color === color
            && (showPurity ? v.gold_purity === purity : !v.gold_purity));

        if (variationIdField) variationIdField.value = match ? match.id : '';

        if (match) {
            if (priceDisplay) priceDisplay.textContent = match.price_formatted;
            if (skuDisplay) skuDisplay.textContent = match.sku;

            const inStock = match.stock > 0;
            if (stockDisplay) {
                stockDisplay.textContent = inStock ? `In Stock (${match.stock} available)` : 'Out of Stock';
                stockDisplay.classList.toggle('text-success', inStock);
                stockDisplay.classList.toggle('text-danger', !inStock);
            }
            if (quantityInput) quantityInput.max = match.stock;

            addToCartButtons.forEach((button) => { button.disabled = !inStock; });
        } else {
            addToCartButtons.forEach((button) => { button.disabled = true; });
        }
    }

    root.addEventListener('change', (event) => {
        if (event.target.matches('[data-variation-field]')) refresh();
    });

    refresh();
}

// -----------------------------------------------------------------------
// Wishlist toggle button visual state (form submit does the real work)
// -----------------------------------------------------------------------

function initWishlistToggle() {
    document.querySelectorAll('[data-wishlist-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('[data-wishlist-button]');
            button?.classList.add('disabled');
        });
    });
}
