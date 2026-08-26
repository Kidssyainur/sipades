// ============================================================================
// Interaktivitas Landing Page — Pemerintah Desa Karduluk
// ============================================================================
document.addEventListener('DOMContentLoaded', () => {
    // ------------------------------------------------------------------
    // Navbar: solid saat scroll
    // ------------------------------------------------------------------
    const navbar = document.getElementById('landing-navbar');

    if (navbar) {
        const onScroll = () => navbar.classList.toggle('navbar-scrolled', window.scrollY > 24);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    // ------------------------------------------------------------------
    // Dropdown desktop (klik toggle + tutup saat klik di luar)
    // ------------------------------------------------------------------
    const dropdowns = document.querySelectorAll('.nav-dropdown');

    const closeAllDropdowns = (except = null) => {
        dropdowns.forEach((dd) => {
            if (dd === except) return;
            dd.querySelector('.nav-dropdown-btn')?.setAttribute('aria-expanded', 'false');
            dd.querySelector('.nav-dropdown-panel')?.classList.add('invisible', 'opacity-0', 'translate-y-2');
            dd.querySelector('.nav-chevron')?.classList.remove('rotate-180');
        });
    };

    const setDropdown = (dd, open) => {
        dd.querySelector('.nav-dropdown-btn')?.setAttribute('aria-expanded', String(open));
        const panel = dd.querySelector('.nav-dropdown-panel');
        panel?.classList.toggle('invisible', !open);
        panel?.classList.toggle('opacity-0', !open);
        panel?.classList.toggle('translate-y-2', !open);
        dd.querySelector('.nav-chevron')?.classList.toggle('rotate-180', open);
    };

    dropdowns.forEach((dd) => {
        const btn = dd.querySelector('.nav-dropdown-btn');
        let lastHoverAt = 0;

        btn?.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = btn.getAttribute('aria-expanded') === 'true';

            // Perangkat sentuh memicu mouseenter sebelum click pada ketukan yang sama.
            // Abaikan click yang datang <300ms setelah hover agar dropdown tidak
            // terbuka-tutup sekaligus (double-toggle) saat diketuk.
            if (Date.now() - lastHoverAt < 300) return;

            // Sudah terbuka (via hover): klik tombol tidak menutup — biarkan user
            // memilih item submenu. Penutupan terjadi via mouseleave, klik di luar,
            // pemilihan submenu, atau tombol Escape.
            if (isOpen) return;

            closeAllDropdowns(dd);
            setDropdown(dd, true);
        });

        // Buka via hover di desktop (pengguna mouse)
        dd.addEventListener('mouseenter', () => {
            lastHoverAt = Date.now();
            setDropdown(dd, true);
        });
        dd.addEventListener('mouseleave', () => {
            setDropdown(dd, false);
        });

        // Tutup dropdown setelah memilih submenu (anchor scroll atau navigasi)
        dd.querySelectorAll('.nav-dropdown-panel a').forEach((link) => {
            link.addEventListener('click', () => closeAllDropdowns());
        });
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.nav-dropdown')) closeAllDropdowns();
    });

    // Tutup dropdown dengan tombol Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAllDropdowns();
    });

    // ------------------------------------------------------------------
    // Menu mobile (hamburger + accordion)
    // ------------------------------------------------------------------
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('icon-open');
    const iconClose = document.getElementById('icon-close');

    if (menuBtn && mobileMenu) {
        const closeMenu = () => {
            mobileMenu.classList.add('hidden');
            iconOpen?.classList.remove('hidden');
            iconClose?.classList.add('hidden');
        };

        menuBtn.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.toggle('hidden');
            iconOpen?.classList.toggle('hidden', !isHidden);
            iconClose?.classList.toggle('hidden', isHidden);
        });

        // Accordion submenu
        mobileMenu.querySelectorAll('.mobile-accordion-btn').forEach((btn) => {
            btn.addEventListener('click', () => {
                const panel = btn.nextElementSibling;
                const chevron = btn.querySelector('.mobile-chevron');
                const isOpen = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', String(!isOpen));
                panel?.classList.toggle('hidden', isOpen);
                chevron?.classList.toggle('rotate-180', !isOpen);
            });
        });

        // Tutup menu saat tautan diklik
        mobileMenu.querySelectorAll('.mobile-nav-link').forEach((link) => link.addEventListener('click', closeMenu));
    }

    // ------------------------------------------------------------------
    // Scroll reveal
    // ------------------------------------------------------------------
    const revealEls = document.querySelectorAll('.reveal');

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('reveal-visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
        );

        revealEls.forEach((el) => observer.observe(el));
    } else {
        revealEls.forEach((el) => el.classList.add('reveal-visible'));
    }

    // ------------------------------------------------------------------
    // Animasi counter statistik
    // ------------------------------------------------------------------
    const animateCounter = (el) => {
        const target = parseInt(el.dataset.target || '0', 10);
        const duration = 1400;
        const start = performance.now();

        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target).toLocaleString('id-ID');
            if (progress < 1) requestAnimationFrame(tick);
        };

        requestAnimationFrame(tick);
    };

    const counters = document.querySelectorAll('[data-counter]');

    if ('IntersectionObserver' in window && counters.length) {
        const counterObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        counterObserver.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.5 }
        );

        counters.forEach((el) => counterObserver.observe(el));
    } else {
        counters.forEach((el) => {
            el.textContent = parseInt(el.dataset.target || '0', 10).toLocaleString('id-ID');
        });
    }
});
