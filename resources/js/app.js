import gsap from 'gsap';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

document.addEventListener('DOMContentLoaded', () => {

    /* =========================================================================
     * 1. CINEMATIC INTRO (PRD §6.1 Timeline)
     * ========================================================================= */
    const introOverlay = document.getElementById('cinematic-intro');
    const skipBtn = document.getElementById('btn-skip-intro');
    const replayBtn = document.getElementById('btn-replay-intro');

    let introTimeline = null;

    const endIntro = () => {
        if (!introOverlay) return;
        sessionStorage.setItem('aocc_intro_seen', 'true');
        document.body.style.overflow = '';
        introOverlay.style.opacity = '0';
        setTimeout(() => {
            introOverlay.style.display = 'none';
        }, 800);
    };

    const startIntroTimeline = () => {
        if (!introOverlay) return;
        
        introOverlay.style.display = 'flex';
        introOverlay.style.opacity = '1';
        document.body.style.overflow = 'hidden';

        const introTitles = document.getElementById('intro-titles');
        const introLogoContainer = document.getElementById('intro-logo-container');
        const introWelcomeContainer = document.getElementById('intro-welcome-container');
        const introVerseContainer = document.getElementById('intro-verse-container');

        const alphaPath = document.getElementById('svg-alpha-path');
        const alphaBar = document.getElementById('svg-alpha-bar');
        const crossV = document.getElementById('svg-cross-v');
        const crossH = document.getElementById('svg-cross-h');

        if (introTimeline) {
            introTimeline.kill();
        }

        introTimeline = gsap.timeline({
            onComplete: endIntro
        });

        // Stage 1 & 2: 0.0s - 4.0s (AOCC Title & Alfa Omega Community Church)
        introTimeline
            .to(introTitles, { opacity: 1, scale: 1, duration: 1.8, ease: 'power2.out' })
            .to(introTitles, { opacity: 1, duration: 2.2 }) // hold
            .to(introTitles, { opacity: 0, scale: 1.05, duration: 1.0, ease: 'power2.in' }) // fade out to black

        // Stage 3: 7.0s - 10.5s (Step-by-step vector SVG logo drawing)
        introTimeline
            .call(() => {
                introTitles.classList.add('hidden');
                introLogoContainer.classList.remove('hidden');
            })
            .to(introLogoContainer, { opacity: 1, duration: 0.6 })
            .to(alphaPath, { strokeDashoffset: 0, duration: 1.6, ease: 'power1.inOut' }, '-=0.2')
            .to(alphaBar, { strokeDashoffset: 0, duration: 0.7, ease: 'power1.inOut' }, '-=0.5')
            .to([crossV, crossH], { strokeDashoffset: 0, duration: 0.6, ease: 'power1.inOut' }, '-=0.3')
            .to(introLogoContainer, { scale: 1.1, duration: 1.0, ease: 'sine.inOut' })

        // Stage 4: 11.5s - 14.5s ("Welcome Home" Handwriting Lettering Reveal)
        introTimeline
            .call(() => {
                introWelcomeContainer.classList.remove('hidden');
            })
            .to(introWelcomeContainer, { opacity: 1, y: 0, duration: 1.6, ease: 'power2.out' })

        // Stage 5: 14.5s - 17.5s (Daily Verse on Intro)
        introTimeline
            .call(() => {
                introVerseContainer.classList.remove('hidden');
            })
            .to(introVerseContainer, { opacity: 1, duration: 1.2, ease: 'power2.out' })
            .to({}, { duration: 1.8 }); // hold final scene before smooth release
    };

    if (introOverlay) {
        const hasSeenIntro = sessionStorage.getItem('aocc_intro_seen');
        if (hasSeenIntro) {
            introOverlay.style.display = 'none';
        } else {
            startIntroTimeline();
        }

        if (skipBtn) {
            skipBtn.addEventListener('click', () => {
                if (introTimeline) introTimeline.kill();
                endIntro();
            });
        }
    }

    if (replayBtn) {
        replayBtn.addEventListener('click', () => {
            sessionStorage.removeItem('aocc_intro_seen');
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setTimeout(() => {
                startIntroTimeline();
            }, 300);
        });
    }


    /* =========================================================================
     * 2. NAVBAR SCROLL MORPHING & ACTIVE PILL SLIDER (PRD §6.2)
     * ========================================================================= */
    const navbarContainer = document.getElementById('navbar-container');
    const navLogoWrapper = document.getElementById('nav-logo-wrapper');
    const navActivePill = document.getElementById('nav-active-pill');
    const navLinks = document.querySelectorAll('.nav-link');
    const heroSection = document.getElementById('hero');

    const updateNavbarOnScroll = () => {
        const scrollY = window.scrollY;

        // Floating pill transition
        if (navbarContainer) {
            if (scrollY > 70) {
                navbarContainer.classList.add('glass-panel-glow', 'bg-slate-950/85', 'shadow-2xl', 'max-w-4xl');
                navbarContainer.classList.remove('max-w-7xl');
            } else {
                navbarContainer.classList.remove('glass-panel-glow', 'bg-slate-950/85', 'shadow-2xl', 'max-w-4xl');
                navbarContainer.classList.add('max-w-7xl');
            }
        }

        // Logo opacity & smooth slide as per PRD §6.2:
        // Visible in Hero section, smoothly fades out to opacity 0 in other sections
        if (navLogoWrapper && heroSection) {
            const heroBottom = heroSection.offsetTop + heroSection.offsetHeight - 120;
            if (scrollY > heroBottom) {
                // Past hero: fade out logo
                navLogoWrapper.style.opacity = '0';
                navLogoWrapper.style.maxWidth = '0px';
                navLogoWrapper.style.marginRight = '0px';
                navLogoWrapper.style.pointerEvents = 'none';
            } else {
                // Inside hero: full opacity
                navLogoWrapper.style.opacity = '1';
                navLogoWrapper.style.maxWidth = '220px';
                navLogoWrapper.style.marginRight = '';
                navLogoWrapper.style.pointerEvents = 'auto';
            }
        }
    };

    window.addEventListener('scroll', updateNavbarOnScroll, { passive: true });
    updateNavbarOnScroll();

    // ScrollSpy & Moving Indicator Pill
    const sections = ['tentang', 'ibadah', 'beranda', 'sermon', 'acara'];
    
    const moveActivePill = (activeLink) => {
        if (!navActivePill || !activeLink) return;
        const parentLeft = activeLink.parentElement.parentElement.getBoundingClientRect().left;
        const linkRect = activeLink.getBoundingClientRect();

        navActivePill.style.left = `${linkRect.left - parentLeft}px`;
        navActivePill.style.width = `${linkRect.width}px`;
        navActivePill.style.opacity = '1';
    };

    const updateScrollSpy = () => {
        let currentSection = '';
        const scrollPos = window.scrollY + 200;

        sections.forEach(secId => {
            const el = document.getElementById(secId);
            if (el) {
                const top = el.offsetTop;
                const height = el.offsetHeight;
                if (scrollPos >= top && scrollPos < top + height) {
                    currentSection = secId;
                }
            }
        });

        if (currentSection) {
            const targetLink = document.querySelector(`.nav-link[data-nav-target="${currentSection}"]`);
            if (targetLink) {
                moveActivePill(targetLink);
            }
        } else if (navActivePill) {
            navActivePill.style.opacity = '0';
        }
    };

    window.addEventListener('scroll', updateScrollSpy, { passive: true });
    updateScrollSpy();

    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            moveActivePill(link);
        });
    });


    /* =========================================================================
     * 3. MOBILE MENU DRAWER
     * ========================================================================= */
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenuDrawer = document.getElementById('mobile-menu-drawer');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');

    if (mobileMenuBtn && mobileMenuDrawer) {
        mobileMenuBtn.addEventListener('click', () => {
            const isOpen = !mobileMenuDrawer.classList.contains('hidden');
            if (isOpen) {
                mobileMenuDrawer.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            } else {
                mobileMenuDrawer.classList.remove('hidden');
                hamburgerIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            }
        });

        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenuDrawer.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            });
        });
    }


    /* =========================================================================
     * 4. IBADAH 6-TABS SWITCHER (PRD §6.3 & FR-122)
     * ========================================================================= */
    const ibadahTabBtns = document.querySelectorAll('.ibadah-tab-btn');
    const ibadahPanels = document.querySelectorAll('.ibadah-panel');

    ibadahTabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetSlug = btn.getAttribute('data-ibadah-btn');

            // Button styling
            ibadahTabBtns.forEach(b => {
                b.className = 'ibadah-tab-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap transition-all duration-300 bg-slate-900/80 text-slate-400 hover:text-white hover:bg-slate-800 border border-blue-500/20';
            });
            btn.className = 'ibadah-tab-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap transition-all duration-300 bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30';

            // Panel visibility with crossfade
            ibadahPanels.forEach(panel => {
                if (panel.id === `ibadah-panel-${targetSlug}`) {
                    panel.classList.remove('hidden');
                    panel.style.opacity = '0';
                    setTimeout(() => {
                        panel.style.opacity = '1';
                    }, 50);
                } else {
                    panel.classList.add('hidden');
                    panel.style.opacity = '0';
                }
            });
        });
    });


    /* =========================================================================
     * 5. BERANDA 3-TABS SWITCHER (PRD §6.3 & FR-131)
     * ========================================================================= */
    const berandaTabBtns = document.querySelectorAll('.beranda-tab-btn');
    const berandaPanels = document.querySelectorAll('.beranda-panel');

    berandaTabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetSlug = btn.getAttribute('data-beranda-btn');

            berandaTabBtns.forEach(b => {
                b.className = 'beranda-tab-btn px-6 py-2.5 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap transition-all duration-300 bg-slate-900/80 text-slate-400 hover:text-white hover:bg-slate-800 border border-blue-500/20';
            });
            btn.className = 'beranda-tab-btn px-6 py-2.5 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap transition-all duration-300 bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30';

            berandaPanels.forEach(panel => {
                if (panel.id === `beranda-panel-${targetSlug}`) {
                    panel.classList.remove('hidden');
                    panel.style.opacity = '0';
                    setTimeout(() => {
                        panel.style.opacity = '1';
                    }, 50);
                } else {
                    panel.classList.add('hidden');
                    panel.style.opacity = '0';
                }
            });
        });
    });


    /* =========================================================================
     * 6. SWIPER CAROUSELS FOR SERMON & ACARA (PRD §6.3)
     * ========================================================================= */
    if (document.querySelector('.sermon-swiper')) {
        new Swiper('.sermon-swiper', {
            modules: [Navigation, Autoplay],
            slidesPerView: 1,
            spaceBetween: 24,
            speed: 600,
            navigation: {
                nextEl: '#sermon-next',
                prevEl: '#sermon-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 28,
                },
            },
        });
    }

    if (document.querySelector('.event-swiper')) {
        new Swiper('.event-swiper', {
            modules: [Navigation, Autoplay],
            slidesPerView: 1,
            spaceBetween: 24,
            speed: 600,
            navigation: {
                nextEl: '#event-next',
                prevEl: '#event-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 28,
                },
            },
        });
    }


    /* =========================================================================
     * 7. BETHEL PHILOSOPHY MODAL (PRD §6.3 & FR-112)
     * ========================================================================= */
    const bethelModal = document.getElementById('bethel-modal');
    const openBethelBtn = document.getElementById('btn-open-bethel');
    const closeBethelBtn = document.getElementById('btn-close-bethel');
    const bethelBackdrop = document.getElementById('bethel-modal-backdrop');

    const openBethel = () => {
        if (!bethelModal) return;
        bethelModal.classList.remove('opacity-0', 'pointer-events-none');
        bethelModal.classList.add('opacity-100');
        document.body.style.overflow = 'hidden';
    };

    const closeBethel = () => {
        if (!bethelModal) return;
        bethelModal.classList.add('opacity-0', 'pointer-events-none');
        bethelModal.classList.remove('opacity-100');
        document.body.style.overflow = '';
    };

    if (openBethelBtn) openBethelBtn.addEventListener('click', openBethel);
    if (closeBethelBtn) closeBethelBtn.addEventListener('click', closeBethel);
    if (bethelBackdrop) bethelBackdrop.addEventListener('click', closeBethel);


    /* =========================================================================
     * 8. YOUTUBE VIDEO MODAL
     * ========================================================================= */
    const youtubeModal = document.getElementById('youtube-modal');
    const closeYoutubeBtn = document.getElementById('btn-close-youtube');
    const youtubeBackdrop = document.getElementById('youtube-modal-backdrop');
    const youtubeIframe = document.getElementById('youtube-modal-iframe');
    const youtubeTitle = document.getElementById('youtube-modal-title');
    const youtubeSpeaker = document.getElementById('youtube-modal-speaker');
    const youtubeRef = document.getElementById('youtube-modal-ref');

    const openYouTubeModal = (videoId, title, speaker, scriptureRef) => {
        if (!youtubeModal || !youtubeIframe) return;
        youtubeIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
        if (youtubeTitle) youtubeTitle.textContent = title || 'Khotbah AOCC';
        if (youtubeSpeaker) youtubeSpeaker.textContent = speaker || 'Pembicara';
        if (youtubeRef) youtubeRef.textContent = scriptureRef || '';

        youtubeModal.classList.remove('opacity-0', 'pointer-events-none');
        youtubeModal.classList.add('opacity-100');
        document.body.style.overflow = 'hidden';
    };

    const closeYouTubeModal = () => {
        if (!youtubeModal || !youtubeIframe) return;
        youtubeIframe.src = '';
        youtubeModal.classList.add('opacity-0', 'pointer-events-none');
        youtubeModal.classList.remove('opacity-100');
        document.body.style.overflow = '';
    };

    document.querySelectorAll('.btn-open-sermon').forEach(el => {
        el.addEventListener('click', (e) => {
            const target = e.currentTarget;
            const yId = target.getAttribute('data-youtube-id') || 'dQw4w9WgXcQ';
            const yTitle = target.getAttribute('data-sermon-title') || '';
            const ySpeaker = target.getAttribute('data-sermon-speaker') || '';
            const yRef = target.getAttribute('data-sermon-ref') || '';
            openYouTubeModal(yId, yTitle, ySpeaker, yRef);
        });
    });

    if (closeYoutubeBtn) closeYoutubeBtn.addEventListener('click', closeYouTubeModal);
    if (youtubeBackdrop) youtubeBackdrop.addEventListener('click', closeYouTubeModal);

    // Escape key closes open modals
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeBethel();
            closeYouTubeModal();
        }
    });

});
