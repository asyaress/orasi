(function () {
    'use strict';

    var PLACEHOLDER_SRC = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E";

    function whenReady(callback) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback);
        } else {
            callback();
        }
    }

    function whenVisible(element, callback, rootMargin) {
        if (!element) {
            return;
        }

        if (!('IntersectionObserver' in window)) {
            callback();
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            if (!entries[0] || !entries[0].isIntersecting) {
                return;
            }

            observer.disconnect();
            callback();
        }, { rootMargin: rootMargin || '240px 0px' });

        observer.observe(element);
    }

    function loadScript(src) {
        return new Promise(function (resolve, reject) {
            if (document.querySelector('script[src="' + src + '"]')) {
                resolve();
                return;
            }

            var script = document.createElement('script');
            script.src = src;
            script.async = true;
            script.onload = function () { resolve(); };
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    function hydrateImage(image) {
        if (!image || !image.dataset.src) {
            return;
        }

        image.src = image.dataset.src;
        image.removeAttribute('data-src');
    }

    function hydrateImagesWithin(root) {
        if (!root) {
            return;
        }

        root.querySelectorAll('img[data-src]').forEach(hydrateImage);
    }

    function initLazyYoutube() {
        document.querySelectorAll('[data-orasi-lazy-youtube]').forEach(function (container) {
            var embedUrl = container.getAttribute('data-orasi-lazy-youtube');

            if (!embedUrl) {
                return;
            }

            var mountIframe = function () {
                if (container.querySelector('iframe')) {
                    return;
                }

                var iframe = document.createElement('iframe');
                iframe.src = embedUrl;
                iframe.title = container.getAttribute('data-orasi-youtube-title') || 'Video Orasi Ilmiah';
                iframe.setAttribute('allow', 'autoplay; encrypted-media; picture-in-picture');
                iframe.setAttribute('allowfullscreen', '');
                iframe.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
                iframe.setAttribute('loading', 'lazy');
                container.appendChild(iframe);
            };

            whenVisible(container, function () {
                if ('requestIdleCallback' in window) {
                    window.requestIdleCallback(mountIframe, { timeout: 1800 });
                } else {
                    window.setTimeout(mountIframe, 400);
                }
            }, '120px 0px');
        });
    }

    function initLazyBackgrounds() {
        document.querySelectorAll('[data-orasi-lazy-bg]').forEach(function (element) {
            var backgroundUrl = element.getAttribute('data-orasi-lazy-bg');

            if (!backgroundUrl) {
                return;
            }

            whenVisible(element, function () {
                element.style.backgroundImage = "url('" + backgroundUrl.replace(/'/g, '%27') + "')";
                element.classList.add('is-bg-loaded');
            });
        });
    }

    function initDeferredImages() {
        document.querySelectorAll('img[data-src]').forEach(function (image) {
            if (!image.getAttribute('src')) {
                image.setAttribute('src', PLACEHOLDER_SRC);
            }
        });

        document.querySelectorAll('[data-orator-slide].is-active, [data-video-slide].is-active').forEach(hydrateImagesWithin);
    }

    function initYearArchive() {
        document.querySelectorAll('[data-year-archive], #orasiProfessorArchive').forEach(function (archive) {
            archive.addEventListener('click', function (event) {
                var trigger = event.target.closest('[data-year-toggle]');

                if (!trigger) {
                    return;
                }

                var item = trigger.closest('[data-year-accordion]');

                if (!item) {
                    return;
                }

                var shouldOpen = !item.classList.contains('is-open');

                archive.querySelectorAll('[data-year-accordion]').forEach(function (node) {
                    node.classList.remove('is-open');
                    var button = node.querySelector('[data-year-toggle]');

                    if (button) {
                        button.setAttribute('aria-expanded', 'false');
                    }
                });

                if (shouldOpen) {
                    item.classList.add('is-open');
                    trigger.setAttribute('aria-expanded', 'true');
                    hydrateImagesWithin(item);
                }
            });
        });
    }

    function initCycleSliders() {
        Array.from(document.querySelectorAll('[data-cycle-slider]')).forEach(function (slider) {
            var slideSelector = slider.dataset.slideSelector || '[data-slide]';
            var slides = Array.from(slider.querySelectorAll(slideSelector));

            if (slides.length <= 1) {
                hydrateImagesWithin(slides[0]);
                return;
            }

            var autoplayMs = Number(slider.dataset.autoplayMs || 4200);
            var transitionMs = 820;
            var currentIndex = slides.findIndex(function (slide) {
                return slide.classList.contains('is-active');
            });

            if (currentIndex < 0) {
                currentIndex = 0;
            }

            var sliderTimer = null;
            var transitionTimer = null;
            var isTransitioning = false;

            function setSliderHeight(slide) {
                if (!slide) {
                    return;
                }

                var contentHeight = Array.from(slide.children).reduce(function (total, child) {
                    var style = window.getComputedStyle(child);
                    var marginTop = parseFloat(style.marginTop) || 0;
                    var marginBottom = parseFloat(style.marginBottom) || 0;

                    return total + child.offsetHeight + marginTop + marginBottom;
                }, 0);

                slider.style.minHeight = Math.ceil(contentHeight || slide.offsetHeight) + 'px';
            }

            function clearLeavingState() {
                slides.forEach(function (slide) {
                    slide.classList.remove('is-leaving');
                });
            }

            function showSlide(index, force) {
                var nextIndex = (index + slides.length) % slides.length;

                if (!force && (isTransitioning || nextIndex === currentIndex)) {
                    return;
                }

                var currentSlide = slides[currentIndex];
                var nextSlide = slides[nextIndex];

                if (!currentSlide || !nextSlide) {
                    return;
                }

                window.clearTimeout(transitionTimer);
                isTransitioning = true;
                setSliderHeight(nextSlide);
                hydrateImagesWithin(nextSlide);

                if (currentSlide !== nextSlide) {
                    currentSlide.classList.add('is-leaving');
                    currentSlide.classList.remove('is-active');
                }

                nextSlide.classList.add('is-active');
                currentIndex = nextIndex;

                transitionTimer = window.setTimeout(function () {
                    clearLeavingState();
                    setSliderHeight(slides[currentIndex]);
                    isTransitioning = false;
                }, transitionMs);
            }

            function queueNextSlide() {
                window.clearTimeout(sliderTimer);

                sliderTimer = window.setTimeout(function advanceSlider() {
                    showSlide(currentIndex + 1);
                    sliderTimer = window.setTimeout(advanceSlider, autoplayMs);
                }, autoplayMs);
            }

            function restartSlider() {
                window.clearTimeout(sliderTimer);
                queueNextSlide();
            }

            function syncSliderHeight() {
                setSliderHeight(slides[currentIndex]);
            }

            document.addEventListener('visibilitychange', function () {
                if (document.hidden) {
                    window.clearTimeout(sliderTimer);
                    window.clearTimeout(transitionTimer);
                } else {
                    clearLeavingState();
                    syncSliderHeight();
                    restartSlider();
                }
            });

            window.addEventListener('resize', syncSliderHeight);

            hydrateImagesWithin(slides[currentIndex]);
            syncSliderHeight();
            showSlide(currentIndex, true);
            restartSlider();
        });
    }

    function initLazyCharts() {
        var statsSection = document.getElementById('statistik');

        if (!statsSection || typeof window.initOrasiCharts !== 'function') {
            return;
        }

        whenVisible(statsSection, function () {
            loadScript('https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js')
                .then(function () {
                    window.initOrasiCharts();
                })
                .catch(function () {
                    // Chart is non-critical for first paint.
                });
        }, '320px 0px');
    }

    function dismissPreloader() {
        var loader = document.getElementById('de-loader');
        var root = document.documentElement;

        if (!loader || loader.classList.contains('is-done')) {
            return;
        }

        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var revealDelay = reducedMotion ? 80 : 460;
        var cleanupDelay = reducedMotion ? 120 : 760;

        window.setTimeout(function () {
            requestAnimationFrame(function () {
                loader.classList.add('is-done');
                root.classList.add('orasi-revealed');

                window.setTimeout(function () {
                    if (loader.parentNode) {
                        loader.parentNode.removeChild(loader);
                    }

                    root.classList.remove('orasi-booting', 'orasi-revealed');
                }, cleanupDelay);
            });
        }, revealDelay);
    }

    whenReady(function () {
        dismissPreloader();

        initLazyYoutube();
        initLazyBackgrounds();
        initDeferredImages();
        initYearArchive();
        initCycleSliders();
        initLazyCharts();
    });
})();
