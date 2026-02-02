/**
 * Mesa Circular Menu - Frontend JavaScript
 * Handles advanced animations and interactions
 */

(function ($) {
  "use strict";

  $(document).ready(function () {
    initMenuAnimations();
    initAccessibility();
    initTouchEnhancements();
  });

  /**
   * Initialize menu animations
   */
  function initMenuAnimations() {
    const $menuItems = $(".mcm-menu-item");
    const $menuGrid = $(".mcm-menu-grid");

    if ($menuItems.length === 0) return;

    // Trigger Bloom Animation
    setTimeout(function () {
      $menuGrid.addClass("mcm-anim-active");
    }, 100);

    // Add ripple effect on click
    $menuItems.on("click", function (e) {
      const $circle = $(this).find(".mcm-circle");
      const $ripple = $('<span class="mcm-ripple"></span>');

      const circleOffset = $circle.offset();
      const x = e.pageX - circleOffset.left;
      const y = e.pageY - circleOffset.top;

      $ripple.css({
        left: x + "px",
        top: y + "px",
      });

      $circle.append($ripple);

      setTimeout(function () {
        $ripple.remove();
      }, 600);
    });
  }

  /**
   * Initialize accessibility features
   */
  function initAccessibility() {
    const $menuItems = $(".mcm-menu-item");

    // Add keyboard navigation
    $menuItems.on("keydown", function (e) {
      const currentIndex = $menuItems.index(this);
      let nextIndex;

      switch (e.key) {
        case "ArrowRight":
        case "ArrowDown":
          e.preventDefault();
          nextIndex = (currentIndex + 1) % $menuItems.length;
          $menuItems.eq(nextIndex).focus();
          break;

        case "ArrowLeft":
        case "ArrowUp":
          e.preventDefault();
          nextIndex =
            (currentIndex - 1 + $menuItems.length) % $menuItems.length;
          $menuItems.eq(nextIndex).focus();
          break;

        case "Home":
          e.preventDefault();
          $menuItems.first().focus();
          break;

        case "End":
          e.preventDefault();
          $menuItems.last().focus();
          break;
      }
    });

    // Add ARIA labels
    $menuItems.each(function () {
      const title = $(this).data("title");
      $(this).attr({
        role: "button",
        "aria-label": `Vai a ${title}`,
        tabindex: "0",
      });
    });
  }

  /**
   * Initialize touch enhancements for mobile
   */
  function initTouchEnhancements() {
    if ("ontouchstart" in window) {
      const $menuItems = $(".mcm-menu-item");

      $menuItems.on("touchstart", function () {
        $(this).addClass("mcm-touching");
      });

      $menuItems.on("touchend touchcancel", function () {
        const $this = $(this);
        setTimeout(function () {
          $this.removeClass("mcm-touching");
        }, 300);
      });
    }
  }

  /**
   * Lazy load icons for performance
   */
  function lazyLoadIcons() {
    if ("IntersectionObserver" in window) {
      const imageObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            const img = entry.target;
            const src = img.getAttribute("data-src");
            if (src) {
              img.src = src;
              img.removeAttribute("data-src");
              imageObserver.unobserve(img);
            }
          }
        });
      });

      document.querySelectorAll(".mcm-icon[data-src]").forEach(function (img) {
        imageObserver.observe(img);
      });
    }
  }
})(jQuery);

// Add CSS for ripple effect
const rippleStyles = document.createElement("style");
rippleStyles.textContent = `
    .mcm-circle {
        position: relative;
        overflow: hidden;
    }
    
    .mcm-ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        width: 20px;
        height: 20px;
        margin-left: -10px;
        margin-top: -10px;
        animation: mcmRipple 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes mcmRipple {
        to {
            transform: scale(15);
            opacity: 0;
        }
    }
    
    .mcm-touching .mcm-circle {
        transform: scale(0.95);
    }
`;
document.head.appendChild(rippleStyles);
