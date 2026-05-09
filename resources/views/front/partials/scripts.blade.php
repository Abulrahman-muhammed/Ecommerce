
<!-- ========================= JS here ========================= -->
<script src="http://127.0.0.1:8000/front-assets/js/bootstrap.min.js"></script>
<script src="http://127.0.0.1:8000/front-assets/js/tiny-slider.js"></script>
<script src="http://127.0.0.1:8000/front-assets/js/glightbox.min.js"></script>
<script src="http://127.0.0.1:8000/front-assets/js/main.js"></script>

<script type="text/javascript">
    // Hero Slider
    if (document.querySelector('.hero-slider')) {
        tns({
            container: '.hero-slider',
            slideBy: 'page',
            autoplay: true,
            autoplayButtonOutput: false,
            mouseDrag: true,
            gutter: 0,
            items: 1,
            nav: false,
            controls: true,
            controlsText: ['<i class="lni lni-chevron-left"></i>', '<i class="lni lni-chevron-right"></i>'],
        });
    }

    // Brand Slider
    if (document.querySelector('.brands-logo-carousel')) {
        tns({
            container: '.brands-logo-carousel',
            autoplay: true,
            autoplayButtonOutput: false,
            mouseDrag: true,
            gutter: 15,
            nav: false,
            controls: false,
            responsive: {
                0: { items: 1 },
                540: { items: 3 },
                768: { items: 5 },
                992: { items: 6 }
            }
        });
    }
</script>

<script>
    // ✅ الحل: بدل '.container' استخدم selector أدق
    const finaleDate = new Date("February 15, 2026 00:00:00").getTime();

    const timer = () => {
        const now = new Date().getTime();
        let diff = finaleDate - now;

        if (diff < 0) {
            const alertEl = document.querySelector('.offer-content .alert');
            const timerBox = document.querySelector('.offer-content .box-head');
            if (alertEl) alertEl.style.display = 'block';
            if (timerBox) timerBox.style.display = 'none';
            return;
        }

        let days    = Math.floor(diff / (1000 * 60 * 60 * 24));
        let hours   = Math.floor(diff % (1000 * 60 * 60 * 24) / (1000 * 60 * 60));
        let minutes = Math.floor(diff % (1000 * 60 * 60) / (1000 * 60));
        let seconds = Math.floor(diff % (1000 * 60) / 1000);

        days    = String(days).padStart(3, '0');
        hours   = String(hours).padStart(2, '0');
        minutes = String(minutes).padStart(2, '0');
        seconds = String(seconds).padStart(2, '0');

        document.querySelector('#days').textContent    = days;
        document.querySelector('#hours').textContent   = hours;
        document.querySelector('#minutes').textContent = minutes;
        document.querySelector('#seconds').textContent = seconds;
    }

    timer();
    setInterval(timer, 1000);
</script>
@stack('scripts')