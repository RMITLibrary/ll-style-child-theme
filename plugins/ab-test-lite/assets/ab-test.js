document.addEventListener("DOMContentLoaded", function () {
  const timers = {};
  const urlParams = new URLSearchParams(window.location.search);
  const isDebug = urlParams.get('debug_ab') === 'true';

  function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? match[2] : null;
  }

  function setCookie(name, value, days = 30) {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/';
  }

  document.querySelectorAll(".variation-test").forEach(function (container) {
    let variant = container.dataset.variant;
    const testName = container.dataset.testName;
    const cookieName = "abtest_" + testName;

    if (!variant || variant === '__debug') {
      container.querySelectorAll(".ab-variant").forEach(el => {
        el.style.display = "block";
      });
      return;
    }

    // Check cookie override unless force_variant is in URL
    const forced = urlParams.get('force_variant');
    if (!forced) {
      const stored = getCookie(cookieName);
      if (stored) {
        variant = stored;
        container.dataset.variant = variant;
        console.log(`Cookie found, setting variant to stored value: ${variant}`);
      } else {
        variant = Math.random() < 0.5 ? 'a' : 'b'; // Random selection
        container.dataset.variant = variant;
        setCookie(cookieName, variant);
        console.log(`Randomly setting variant to: ${variant}`);
      }
    }
    /* if (!forced) {
      const stored = getCookie(cookieName);
      if (stored) {
        variant = stored;
        container.dataset.variant = variant;
      } else {
        setCookie(cookieName, variant);
      }
    } */

    const target = container.querySelector(`.ab-variant[data-variant="${variant}"]`);
    if (!target) return;

    container.querySelectorAll(".ab-variant").forEach(el => {
      el.style.display = (el === target) ? "block" : "none";
    });

    let visibleTime = 0;
    let lastVisible = null;
    let isTracking = false;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          lastVisible = Date.now();
          isTracking = true;
        } else if (lastVisible) {
          visibleTime += Date.now() - lastVisible;
          lastVisible = null;
          isTracking = false;
        }
      });
    }, { threshold: 0.5 });

    observer.observe(target);

    // Function to send final engagement data
    const sendFinalEngagement = () => {
      // Add any remaining visible time
      if (isTracking && lastVisible) {
        visibleTime += Date.now() - lastVisible;
      }

      const seconds = Math.round(visibleTime / 1000);
      window.dataLayer = window.dataLayer || [];

      if (seconds >= 3) {
        dataLayer.push({
          event: 'ab_test_engagement',
          ab_test_name: testName,
          ab_test_variant: variant,
          dwell_time_seconds: seconds
        });
      } else {
        dataLayer.push({
          event: 'ab_test_bounce',
          ab_test_name: testName,
          ab_test_variant: variant
        });
      }
    };

    // Set up cleanup and final data send
    const cleanup = () => {
      sendFinalEngagement();
      observer.disconnect();
      window.removeEventListener('beforeunload', cleanup);
      document.removeEventListener('visibilitychange', handleVisibilityChange);
    };

    const handleVisibilityChange = () => {
      if (document.visibilityState === 'hidden') {
        cleanup();
      }
    };

    window.addEventListener('beforeunload', cleanup);
    document.addEventListener('visibilitychange', handleVisibilityChange);

    const wrapper = target.querySelector("[class^='track-']");
    if (wrapper) {
      const trackClass = [...wrapper.classList].find(c => c.startsWith("track-"));
      if (trackClass) {
        container.querySelectorAll("." + trackClass).forEach(function (el) {
          el.addEventListener("click", function () {
            window.dataLayer = window.dataLayer || [];
            dataLayer.push({
              event: "ab_test_click",
              ab_test_name: testName,
              ab_test_variant: variant,
              click_class: trackClass
            });
          });
        });
      }
    }
  });
});
