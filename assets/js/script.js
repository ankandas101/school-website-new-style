// ============================================================
// SCHOOL WEBSITE — MAIN JAVASCRIPT
// TailwindCSS + SwiperJS + AOS + AlpineJS
// ============================================================

'use strict';

/* ===== DOM READY ===== */
document.addEventListener('DOMContentLoaded', function () {
    initAOS();
    initHeroSwiper();
    initTeachersSwiper();
    initStudentsSwiper();
    initLightbox();
    initBackToTop();
    initNavbarScroll();
    initFormValidation();
    initResponsiveTables();
    updateFooterYear();
    initLazyYouTube();
});

/* ===== AOS (Animate On Scroll) ===== */
function initAOS() {
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 620,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50,
            mirror: false,
            disable: false,
        });

        document.querySelectorAll('[data-aos]').forEach(function (el) {
            el.style.transitionDelay = el.getAttribute('data-aos-delay')
                ? (parseInt(el.getAttribute('data-aos-delay'), 10) / 1000) + 's'
                : '0s';
        });
    }
}

/* ===== HERO SWIPER ===== */
function initHeroSwiper() {
    var heroEl = document.getElementById('heroSwiper');
    if (!heroEl) return;
    if (typeof Swiper === 'undefined') return;
    new Swiper('#heroSwiper', {
        loop: true,
        autoplay: {
            delay: 4500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        speed: 800,
        pagination: {
            el: '#heroSwiper .swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '#heroSwiper .swiper-button-next',
            prevEl: '#heroSwiper .swiper-button-prev',
        },
        a11y: {
            prevSlideMessage: 'Previous slide',
            nextSlideMessage: 'Next slide',
        },
    });
}

/* ===== TEACHERS SWIPER ===== */
function initTeachersSwiper() {
    var teachersEl = document.getElementById('teachersSwiper');
    if (!teachersEl) return;
    if (typeof Swiper === 'undefined') return;
    var isMobile = window.matchMedia('(max-width: 768px)').matches;
    new Swiper('#teachersSwiper', {
        loop: true,
        autoplay: {
            delay: isMobile ? 3500 : 3400,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        speed: 600,
        slidesPerView: 1,
        spaceBetween: 14,
        allowTouchMove: true,
        breakpoints: {
            540:  { slidesPerView: 2, spaceBetween: 14 },
            1024: { slidesPerView: 3, spaceBetween: 18 },
        },
        pagination: {
            el: '#teachersSwiper .swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '#teachersSwiper .swiper-button-next',
            prevEl: '#teachersSwiper .swiper-button-prev',
        },
    });
}

/* ===== STUDENTS SWIPER ===== */
function initStudentsSwiper() {
    var studentsEl = document.getElementById('studentsSwiper');
    if (!studentsEl) return;
    if (typeof Swiper === 'undefined') return;
    var isMobile = window.matchMedia('(max-width: 768px)').matches;
    new Swiper('#studentsSwiper', {
        loop: true,
        autoplay: {
            delay: isMobile ? 3600 : 3800,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        speed: 600,
        slidesPerView: 1,
        spaceBetween: 14,
        allowTouchMove: true,
        breakpoints: {
            540:  { slidesPerView: 2, spaceBetween: 14 },
            1024: { slidesPerView: 3, spaceBetween: 18 },
        },
        pagination: {
            el: '#studentsSwiper .swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '#studentsSwiper .swiper-button-next',
            prevEl: '#studentsSwiper .swiper-button-prev',
        },
    });
}

/* ===== LIGHTBOX ===== */
function initLightbox() {
    document.querySelectorAll('.gallery-item').forEach(function (item) {
        item.addEventListener('click', function () {
            var src = this.getAttribute('data-src')
                   || (this.querySelector('img') ? this.querySelector('img').src : null);
            var alt = this.getAttribute('data-alt')
                   || (this.querySelector('img') ? this.querySelector('img').alt : '');
            if (!src) return;

            var overlay = document.createElement('div');
            overlay.className = 'lightbox-overlay';
            overlay.setAttribute('role', 'dialog');
            overlay.setAttribute('aria-modal', 'true');
            overlay.setAttribute('aria-label', 'Image preview');
            overlay.innerHTML = [
                '<span class="lightbox-close" role="button" aria-label="Close" tabindex="0">&times;</span>',
                '<img class="lightbox-img" src="' + src + '" alt="' + _escAttr(alt) + '">'
            ].join('');

            document.body.appendChild(overlay);
            document.body.style.overflow = 'hidden';

            function closeBox() {
                if (overlay.parentNode) {
                    document.body.removeChild(overlay);
                    document.body.style.overflow = '';
                }
            }

            overlay.addEventListener('click', function (e) {
                if (e.target === overlay || e.target.classList.contains('lightbox-close')) {
                    closeBox();
                }
            });

            document.addEventListener('keydown', function escHandler(e) {
                if (e.key === 'Escape') { closeBox(); document.removeEventListener('keydown', escHandler); }
            });
        });
    });
}

/* ===== BACK TO TOP ===== */
function initBackToTop() {
    var btn = document.getElementById('backToTop');
    if (!btn) return;
    window.addEventListener('scroll', function () {
        if (window.pageYOffset > 320) btn.classList.add('visible');
        else btn.classList.remove('visible');
    }, { passive: true });
    btn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

/* ===== NAVBAR SCROLL ===== */
function initNavbarScroll() {
    var header = document.getElementById('main-header');
    if (!header) return;
    window.addEventListener('scroll', function () {
        if (window.pageYOffset > 55) header.classList.add('scrolled');
        else header.classList.remove('scrolled');
    }, { passive: true });
}

/* ===== LAZY YOUTUBE IFRAMES ===== */
function initLazyYouTube() {
    window.addEventListener('load', function () {
        document.querySelectorAll('iframe[data-youtube-src]').forEach(function (iframe) {
            var src = iframe.getAttribute('data-youtube-src');
            if (src) {
                iframe.setAttribute('src', src);
                iframe.removeAttribute('data-youtube-src');
                iframe.setAttribute('loading', 'lazy');
            }
        });
    });
}

/* ===== FORM VALIDATION ===== */
function initFormValidation() {
    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var isValid = true;
            form.querySelectorAll('[required]').forEach(function (field) {
                if (!field.value.trim()) {
                    field.style.borderColor = '#ef4444';
                    field.style.boxShadow   = '0 0 0 2px rgba(239,68,68,0.15)';
                    isValid = false;
                } else {
                    field.style.borderColor = '';
                    field.style.boxShadow   = '';
                }
            });
            if (!isValid) e.preventDefault();
        });
    });
}

/* ===== RESPONSIVE TABLES ===== */
function initResponsiveTables() {
    document.querySelectorAll('table').forEach(function (table) {
        if (table.parentElement.classList.contains('table-wrap')) return;
        var wrapper = document.createElement('div');
        wrapper.className = 'table-wrap';
        wrapper.style.cssText = 'overflow-x:auto; -webkit-overflow-scrolling:touch; border-radius:8px;';
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
    });
}

/* ===== FOOTER YEAR ===== */
function updateFooterYear() {
    var year = new Date().getFullYear();
    document.querySelectorAll('.current-year').forEach(function (el) {
        el.textContent = year;
    });
}

/* ===== UTILITY ===== */
function _escAttr(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

/* ===== SMOOTH ANCHOR SCROLL ===== */
document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
        var href = this.getAttribute('href');
        if (href === '#' || href === '#!' || href === '#0') { e.preventDefault(); return; }
        var target = document.querySelector(href);
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

/* ===== SHARE PAGE ===== */
function sharePage() {
    if (navigator.share) {
        navigator.share({ title: document.title, url: window.location.href }).catch(function(){});
    } else if (navigator.clipboard) {
        navigator.clipboard.writeText(window.location.href);
    }
}

/* ===== PRINT ===== */
function printPage() { window.print(); }