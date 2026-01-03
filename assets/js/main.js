/**
 * Template Name: Aiero | AI Agency & Technology HTML Template
 * Description: AI Agency, tech startup, chat bot, data science, digital agency, consulting, IT solutions, voiceover, video voiceover, robotics, science, industry, machine learning, neural network or other modern technology business template.
 * Version: 1.1.0
 * Author: ib-thems
 * Author https://themeforest.net/user/ib-themes
 */

"use strict";

// ============================
// Preloader
// ============================
var Preloader = {
  init: function () {
    const preloader = document.getElementById("preloader");
    if (!preloader) return;

    const transitionDuration = 500; // ms
    const fallbackDelay = 3000; // ms

    const hidePreloader = () => {
      if (!preloader || preloader.dataset.hidden === "true") return;
      preloader.dataset.hidden = "true"; // prevent double calls
      preloader.style.transition = `opacity ${transitionDuration}ms ease`;
      preloader.style.opacity = "0";
      setTimeout(() => {
        preloader.style.display = "none";
      }, transitionDuration);
    };

    // Hide when the page fully loaded
    window.addEventListener("load", hidePreloader, { once: true });

    // Fallback in case some assets hang or slow CDN
    setTimeout(hidePreloader, fallbackDelay);
  },
};
// ============================
// Side Menu (Primary)
// ============================
var SideMenu = {
  init: function () {
    var menuToggles = document.querySelectorAll(".menu-toggle");
    var sideMenu = document.getElementById("sideMenu");
    var closeMenuBtn = document.getElementById("closeBtn");
    var overlay = document.getElementById("overlay");

    if (menuToggles.length && sideMenu && closeMenuBtn && overlay) {
      menuToggles.forEach(function (toggle) {
        toggle.addEventListener("click", function (e) {
          e.preventDefault();
          sideMenu.classList.add("active");
          overlay.classList.add("active");
        });
      });

      closeMenuBtn.addEventListener("click", function () {
        sideMenu.classList.remove("active");
        overlay.classList.remove("active");
      });

      overlay.addEventListener("click", function () {
        sideMenu.classList.remove("active");
        overlay.classList.remove("active");
      });
    }

    // Sub-menu hover handling
    var subMenus = document.querySelectorAll(
      ".sub-menu .sub-menu, .menu-style1 > ul > li .sub-menu"
    );
    subMenus.forEach(function (subMenu) {
      var parent = subMenu.parentElement;

      parent.classList.add("menu-has-items");

      subMenu.addEventListener("mouseenter", function () {
        parent.classList.add("active");
      });

      subMenu.addEventListener("mouseleave", function () {
        parent.classList.remove("active");
      });
    });
  },
};

// ============================
// Side Menu 2 (Multi Menu)
// ============================
var SideMenu2 = {
  init: function () {
    var overlay2 = document.querySelector(".overlay2");
    var toggles = document.querySelectorAll(".hamburger.popup-menu");
    var sideMenus = document.querySelectorAll(".side-menu2");
    var closeBtns = document.querySelectorAll(".side-menu2 .close-btn");

    if (toggles.length && overlay2) {
      // Open menu
      toggles.forEach(function (toggle) {
        toggle.addEventListener("click", function () {
          var menuName = this.getAttribute("data-menu");
          var menu = document.querySelector(
            '.side-menu2[data-menu="' + menuName + '"]'
          );

          if (menu) {
            menu.classList.add("active");
            overlay2.classList.add("active");
          }
        });
      });

      // Close menu via close button
      closeBtns.forEach(function (closeBtn) {
        closeBtn.addEventListener("click", function () {
          var menu = this.closest(".side-menu2");
          menu.classList.remove("active");
          overlay2.classList.remove("active");
          menu.querySelectorAll(".active").forEach(function (el) {
            el.classList.remove("active");
          });
        });
      });

      // Close menu via overlay
      overlay2.addEventListener("click", function () {
        sideMenus.forEach(function (menu) {
          menu.classList.remove("active");
          menu.querySelectorAll(".active").forEach(function (el) {
            el.classList.remove("active");
          });
        });
        overlay2.classList.remove("active");
      });

      // Toggle sub-menus
      var menuLinks = document.querySelectorAll(".menu-left li > a");
      menuLinks.forEach(function (link) {
        link.addEventListener("click", function (e) {
          var parentLi = this.parentElement;
          var subMenu = parentLi.querySelector("ul");

          if (subMenu) {
            e.preventDefault();
            // Toggle inner menu
            subMenu.classList.toggle("active");
            // Toggle active class on parent <li>
            parentLi.classList.toggle("active");
          }
        });
      });

      // Side menu list item toggle
      var sideMenuItems = document.querySelectorAll(".side-menu2 > ul > li");
      sideMenuItems.forEach(function (item) {
        item.addEventListener("click", function () {
          this.classList.toggle("active");
        });
      });

      var sideSubMenus = document.querySelectorAll(".side-menu2 .sub-menu");
      sideSubMenus.forEach(function (subMenu) {
        subMenu.addEventListener("click", function (e) {
          e.stopPropagation();
          this.classList.toggle("active");
        });
      });
    }
  },
};

// ============================
// SideMenu3 Home-11
// ============================

var SideMenu3 = {
  init: function () {
    // Check if the main menu container exists on this page first.
    var sideMenu3 = document.querySelector(".side-menu3");

    // If the element is not found, stop execution silently to prevent errors.
    if (!sideMenu3) {
      return;
    }

    // If sideMenu3 exists, proceed to find the other required elements.
    var overlay3 = document.querySelector(".overlay3");
    var toggles3 = document.querySelectorAll(".hamburger3");
    var closeBtn3 = sideMenu3 ? sideMenu3.querySelector(".close-btn") : null;

    // This check now only triggers if the page IS supposed to have the menu
    // but is missing a critical component (overlay, toggle, or close button).
    if (!overlay3 || !toggles3.length || !closeBtn3) {
      console.error(
        "SideMenu3 container found, but some elements not found. Check your selectors: .overlay3, .hamburger3, or .close-btn."
      );
      return;
    }

    // Main menu items toggle
    var mainMenuItems = sideMenu3.querySelectorAll(".menu-left > ul > li > a");
    mainMenuItems.forEach(function (link) {
      link.addEventListener("click", function (e) {
        var parentLi = this.parentElement;
        var subMenu = parentLi.querySelector(".inner-menu");
        if (subMenu) {
          e.preventDefault();
          e.stopPropagation();

          sideMenu3
            .querySelectorAll(".inner-menu.active")
            .forEach(function (menu) {
              if (menu !== subMenu) menu.classList.remove("active");
            });
          sideMenu3
            .querySelectorAll(".menu-left > ul > li.active")
            .forEach(function (li) {
              if (li !== parentLi) li.classList.remove("active");
            });

          subMenu.classList.toggle("active");
          parentLi.classList.toggle("active");
        }
      });
    });

    // Nested submenu toggle
    var nestedMenuItems = sideMenu3.querySelectorAll(
      ".inner-menu .sub-menu > a"
    );
    nestedMenuItems.forEach(function (link) {
      link.addEventListener("click", function (e) {
        var parentLi = this.parentElement;
        var nestedSubMenu = parentLi.querySelector("ul");
        if (nestedSubMenu) {
          e.preventDefault();
          e.stopPropagation();

          var parentInnerMenu = parentLi.closest(".inner-menu");
          parentInnerMenu
            .querySelectorAll(".sub-menu ul.active")
            .forEach(function (menu) {
              if (menu !== nestedSubMenu) menu.classList.remove("active");
            });
          parentInnerMenu
            .querySelectorAll(".sub-menu.active")
            .forEach(function (li) {
              if (li !== parentLi) li.classList.remove("active");
            });

          nestedSubMenu.classList.toggle("active");
          parentLi.classList.toggle("active");
        }
      });
    });

    // Open side menu
    toggles3.forEach(function (toggle) {
      toggle.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        sideMenu3.classList.add("active");
        overlay3.classList.add("active");
      });
    });

    // Close side menu function
    function closeMenu() {
      sideMenu3.classList.remove("active");
      overlay3.classList.remove("active");
      sideMenu3.querySelectorAll(".active").forEach(function (el) {
        el.classList.remove("active");
      });
    }

    // Close menu events
    closeBtn3.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      closeMenu();
    });
    overlay3.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      closeMenu();
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && sideMenu3.classList.contains("active")) {
        closeMenu();
      }
    });
  },
};

// Initialize immediately if DOM is ready
document.addEventListener("DOMContentLoaded", function () {
  SideMenu3.init();
});

// ============================
// Sidebar Toggle (2 & 3)
// ============================
var SidebarToggle = {
  init: function () {
    // Sidebar 2
    this.initSidebar(
      ".side-bar2",
      ".sidebar-toggle",
      ".sidebar-close",
      ".sidebar-overlay"
    );

    // Sidebar 3
    this.initSidebar(
      ".side-bar3",
      ".sidebar-toggle3",
      ".sidebar-close3",
      ".sidebar-overlay3"
    );
  },

  initSidebar: function (sidebarClass, toggleClass, closeClass, overlayClass) {
    var sidebar = document.querySelector(sidebarClass);
    var toggleBtns = document.querySelectorAll(toggleClass);
    var closeBtn = document.querySelector(closeClass);
    var overlay = document.querySelector(overlayClass);

    if (toggleBtns.length && sidebar && closeBtn && overlay) {
      toggleBtns.forEach(function (toggleBtn) {
        toggleBtn.addEventListener("click", function () {
          sidebar.classList.add("active");
          overlay.classList.add("active");
        });
      });

      closeBtn.addEventListener("click", function () {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
      });

      overlay.addEventListener("click", function () {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
      });
    }
  },
};

// ============================
// Video Modal
// ============================
var VideoModal = {
  init: function () {
    const videoBtns = document.querySelectorAll(".video-popup");

    if (!videoBtns.length) return;

    videoBtns.forEach((btn) => {
      this.bindEvents(btn);
    });

    // Global ESC key handler
    this.initEscapeKey();
  },

  bindEvents: function (btn) {
    const videoModal = document.querySelector(".video-modal");

    if (!videoModal) return;

    const popupVideo = videoModal.querySelector("iframe");
    const closeVideoBtn = videoModal.querySelector(".close-btn");

    // Open modal
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      this.openModal(btn, videoModal, popupVideo);
    });

    // Close via button
    if (closeVideoBtn) {
      closeVideoBtn.addEventListener("click", () => {
        this.closeModal(videoModal, popupVideo);
      });
    }

    // Close via overlay click
    videoModal.addEventListener("click", (e) => {
      if (e.target === videoModal) {
        this.closeModal(videoModal, popupVideo);
      }
    });
  },

  openModal: function (btn, modal, video) {
    const videoUrl = btn.getAttribute("data-video");
    video.src = videoUrl + "?autoplay=1";
    modal.style.display = "flex";
    setTimeout(() => modal.classList.add("show"), 10);
  },

  closeModal: function (modal, video) {
    modal.classList.remove("show");
    setTimeout(() => {
      modal.style.display = "none";
      video.src = "";
    }, 300);
  },

  initEscapeKey: function () {
    window.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        const videoModal = document.querySelector(".video-modal");
        const popupVideo = videoModal?.querySelector("iframe");

        if (videoModal && videoModal.classList.contains("show")) {
          this.closeModal(videoModal, popupVideo);
        }
      }
    });
  },
};

// ============================
// Sticky Header
// ============================
var StickyHeader = {
  init: function () {
    var header = document.querySelector(".sticky-active");

    if (header) {
      window.addEventListener("scroll", function () {
        if (window.scrollY > 100) {
          header.classList.add("is-sticky");
        } else {
          header.classList.remove("is-sticky");
        }
      });
    }
  },
};

// ============================
// Counter Animation
// ============================
var CounterAnimation = {
  init: function () {
    var counters = document.querySelectorAll(".counter-number");

    if (counters.length && typeof IntersectionObserver !== "undefined") {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              CounterAnimation.animate(entry.target);
              observer.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.5 }
      );

      counters.forEach(function (counter) {
        observer.observe(counter);
      });
    }
  },

  formatNumber: function (num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  },

  animate: function (counter) {
    var target = parseFloat(counter.getAttribute("data-target"));
    var count = 0;
    var duration = 6000;
    var startTime = performance.now();
    var isDecimal = counter.classList.contains("percent-counter3");

    function update(currentTime) {
      var elapsed = currentTime - startTime;
      var progress = Math.min(elapsed / duration, 1);
      count = target * progress;

      if (isDecimal) {
        counter.textContent = count.toFixed(1);
      } else {
        counter.textContent = CounterAnimation.formatNumber(Math.ceil(count));
      }

      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        if (isDecimal) {
          counter.textContent = target.toFixed(1);
        } else {
          counter.textContent = CounterAnimation.formatNumber(target);
        }
      }
    }
    requestAnimationFrame(update);
  },
};

// ============================
// Search Popup
// ============================
var SearchPopup = {
  init: function () {
    // Use event delegation for popup-search buttons
    document.addEventListener("click", function (e) {
      if (
        e.target.matches(".popup-search") ||
        e.target.closest(".popup-search")
      ) {
        e.preventDefault();
        var button = e.target.matches(".popup-search")
          ? e.target
          : e.target.closest(".popup-search");
        var popupId = button.getAttribute("data-popup");
        var popup = document.querySelector(
          '.search-popup[data-popup="' + popupId + '"]'
        );
        if (popup) {
          popup.classList.add("active");
        }
      }
    });

    // Close popups when clicking close button or background
    document.addEventListener("click", function (e) {
      // Close when clicking close button
      if (
        e.target.matches(".close-popup") ||
        e.target.closest(".close-popup")
      ) {
        var closeBtn = e.target.matches(".close-popup")
          ? e.target
          : e.target.closest(".close-popup");
        var popup = closeBtn.closest(".search-popup");
        if (popup) {
          popup.classList.remove("active");
        }
      }
      // Close when clicking on popup background (but not content)
      else if (e.target.matches(".search-popup")) {
        e.target.classList.remove("active");
      }
    });

    // Prevent closing when clicking inside content
    document.addEventListener(
      "click",
      function (e) {
        if (
          e.target.matches(".search-popup-content") ||
          e.target.closest(".search-popup-content")
        ) {
          e.stopPropagation();
        }
      },
      true
    ); // Use capture phase to ensure this runs first
  },
};

// ============================
// Scroll To Top Button
// ============================
var ScrollToTop = {
  init: function () {
    var scrollBtn = document.getElementById("scrollBtn");
    if (scrollBtn) {
      window.addEventListener("scroll", function () {
        if (window.scrollY > 200) {
          scrollBtn.style.display = "block";
        } else {
          scrollBtn.style.display = "none";
        }
      });

      scrollBtn.addEventListener("click", function () {
        window.scrollTo({
          top: 0,
          behavior: "smooth",
        });
      });
    }
  },
};

// ============================
// Horizontal Scroll Slider
// ============================
var HorizontalScroll = {
  init: function () {
    var section = document.querySelector(".project-sec3");
    if (!section) return;

    var track = section.querySelector(".project-track");
    var currentX = 0;
    var velocity = 0;
    var lastScroll = window.scrollY;

    // Duplicate content for infinite loop
    track.innerHTML = track.innerHTML + track.innerHTML;
    var totalWidth = track.scrollWidth / 2;

    window.addEventListener("resize", function () {
      totalWidth = track.scrollWidth / 2;
    });

    function animate() {
      currentX += velocity;

      if (currentX <= -totalWidth) currentX = 0;
      if (currentX >= 0) currentX = -totalWidth;

      track.style.transform = "translateX(" + currentX + "px)";
      velocity *= 0.9;

      requestAnimationFrame(animate);
    }
    animate();

    window.addEventListener("scroll", function () {
      var rect = section.getBoundingClientRect();
      if (rect.top < window.innerHeight && rect.bottom > 0) {
        var currentScroll = window.scrollY;
        if (currentScroll > lastScroll) {
          velocity = -5;
        } else {
          velocity = 5;
        }
        lastScroll = currentScroll;
      }
    });
  },
};

// ============================
// Price Range Filter
// ============================
var PriceFilter = {
  init: function () {
    var minInput = document.querySelector("#min");
    var maxInput = document.querySelector("#max");
    var minValue = document.querySelector("#min-value");
    var maxValue = document.querySelector("#max-value");
    var sliderTrack = document.querySelector(".slider-track");

    if (minInput && maxInput && minValue && maxValue && sliderTrack) {
      var sliderMaxValue = Number(maxInput.getAttribute("max"));

      function fillColor() {
        var percent1 = (Number(minInput.value) / sliderMaxValue) * 100;
        var percent2 = (Number(maxInput.value) / sliderMaxValue) * 100;

        sliderTrack.style.left = percent1 + "%";
        sliderTrack.style.width = percent2 - percent1 + "%";

        minValue.textContent = "$" + minInput.value;
        maxValue.textContent = "$" + maxInput.value;
      }

      minInput.addEventListener("input", fillColor);
      maxInput.addEventListener("input", fillColor);
      fillColor();
    }
  },
};

// ============================
// Quantity Filter
// ============================
window.changeQty = function (id, change) {
  var input = $("#" + id);
  if (!input.length) return;

  var newVal = parseInt(input.val()) + change;
  var min = parseInt(input.attr("min")) || 0;
  var max = parseInt(input.attr("max")) || 999;

  if (newVal >= min && newVal <= max) {
    input.val(newVal);
  }
};

window.applyQtyFilter = function () {
  var minInput = $("#minQty");
  var maxInput = $("#maxQty");

  var minQty = minInput.length ? minInput.val() : 0;
  var maxQty = maxInput.length ? maxInput.val() : 0;

  console.log("Filter applied:", minQty, "-", maxQty);
};

// ============================
// Active Menu Item
// ============================
var ActiveMenu = {
  init: function () {
    var currentPage = window.location.pathname.split("/").pop();

    function setActive(menuLi) {
      var links = menuLi.querySelectorAll("a");
      var found = false;

      // Check all links in this menu item
      for (var i = 0; i < links.length; i++) {
        var link = links[i];
        var href = link.getAttribute("href");
        if (href) {
          var linkPage = href.split("/").pop();
          if (linkPage === currentPage) {
            link.classList.add("active");
            found = true;
          }
        }
      }

      // Check child list items recursively
      var childLis = menuLi.querySelectorAll("li");
      for (var j = 0; j < childLis.length; j++) {
        if (setActive(childLis[j])) {
          found = true;
        }
      }

      // Set active state for parent elements
      var topLink = null;
      // Get direct child anchor (alternative to :scope selector)
      for (var k = 0; k < menuLi.children.length; k++) {
        if (menuLi.children[k].tagName === "A") {
          topLink = menuLi.children[k];
          break;
        }
      }

      if (found) {
        if (topLink) topLink.classList.add("active");
        menuLi.classList.add("active");
      } else {
        if (topLink) topLink.classList.remove("active");
        menuLi.classList.remove("active");
      }

      return found;
    }

    // ✅ Desktop Menu
    var topMenuItems = document.querySelectorAll(".menu-style1 > ul > li");
    for (var i = 0; i < topMenuItems.length; i++) {
      setActive(topMenuItems[i]);
    }

    // ✅ Mobile / Side Menu
    var mobileMenuItems = document.querySelectorAll(".side-menu2 > ul > li");
    for (var j = 0; j < mobileMenuItems.length; j++) {
      setActive(mobileMenuItems[j]);
    }
  },
};

// ============================
// GSAP Animations
// ============================

var GsapAnimations = {
  init: function () {
    if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined")
      return;

    gsap.registerPlugin(ScrollTrigger);

    this.animateSubtitles();
    this.animateHeadings();
    this.initHeroSlider();
    this.animateContactButtons();
    this.animateDemoImages();
    this.animateSection24();
    this.animateServiceCards();
  },

  // =====================================
  // SUBTITLE ANIMATION
  // =====================================
  animateSubtitles: function () {
    const subtitles = document.querySelectorAll(".sec-title .sub-title");

    subtitles.forEach(function (sub) {
      const text = sub.textContent.trim();
      sub.innerHTML = '<span class="sub-text">' + text + "</span>";
      const innerSpan = sub.querySelector(".sub-text");

      gsap.set(innerSpan, {
        width: 1,
        display: "inline-block",
        overflow: "hidden",
      });

      gsap.to(innerSpan, {
        width: innerSpan.scrollWidth,
        duration: 1.2,
        ease: "power2.out",
        scrollTrigger: {
          trigger: sub.closest(".sec-title"),
          start: "top 90%",
          toggleActions: "play none none none",
        },
      });
    });
  },

  // =====================================
  // HEADING ANIMATION
  // =====================================
  animateHeadings: function () {
    const headings = document.querySelectorAll(".title.animated-heading");

    headings.forEach(function (title) {
      const words = title.textContent.trim().split(/\s+/);

      const wrappedWords = words
        .map(function (word) {
          const letters = word
            .split("")
            .map(function (l) {
              return '<span class="letter">' + l + "</span>";
            })
            .join("");
          return '<span class="word">' + letters + "</span>";
        })
        .join('<span class="space"> </span>');

      title.innerHTML = wrappedWords;

      const letters = title.querySelectorAll(".letter");

      gsap.from(letters, {
        y: 40,
        opacity: 0,
        stagger: 0.04,
        duration: 0.9,
        ease: "power3.out",
        delay: 0.3,
        scrollTrigger: {
          trigger: title,
          start: "top 85%",
          once: true,
        },
      });
    });
  },

  // =====================================
  // HERO SLIDER ANIMATION
  // =====================================
  initHeroSlider: function () {
    if (typeof Swiper === "undefined") return;

    const heroSlider = document.querySelector(".hero-slider2");
    if (!heroSlider) return;

    var heroSwiper = new Swiper(".hero-slider2", {
      loop: true,
      effect: "fade",
      speed: 800,
      fadeEffect: { crossFade: true },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      on: {
        init: function () {
          GsapAnimations.animateSlide(this.slides[this.activeIndex]);
        },
        slideChange: function () {
          GsapAnimations.animateSlide(this.slides[this.activeIndex]);
        },
      },
    });
  },

  animateSlide: function (slide) {
    if (!slide) return;

    gsap.set(slide, { visibility: "visible" });

    // Get all elements to animate
    const elements = slide.querySelectorAll(
      ".title, .hero-btn2 p, .ibt-btn, .exp-box"
    );
    gsap.set(elements, { y: 50, opacity: 0 });

    const tl = gsap.timeline();
    const title = slide.querySelector(".title");
    const paragraph = slide.querySelector(".hero-btn2 p");
    const btn = slide.querySelector(".ibt-btn");
    const expBox = slide.querySelector(".exp-box");

    if (title)
      tl.to(title, { y: 0, opacity: 1, duration: 0.6, ease: "power3.out" });
    if (paragraph)
      tl.to(
        paragraph,
        { y: 0, opacity: 1, duration: 0.5, ease: "power3.out" },
        "-=0.4"
      );
    if (btn)
      tl.to(
        btn,
        { y: 0, opacity: 1, duration: 0.5, ease: "power3.out" },
        "-=0.4"
      );
    if (expBox)
      tl.to(
        expBox,
        { y: 0, opacity: 1, duration: 0.6, ease: "power3.out" },
        "-=0.3"
      );
  },

  // =====================================
  // CONTACT BUTTON ANIMATION
  // =====================================
  animateContactButtons: function () {
    const buttons = document.querySelectorAll(".contact-btn");

    buttons.forEach(function (btn) {
      const text = btn.querySelector(".text");
      const outer = btn.querySelector(".border-outer");
      const inner = btn.querySelector(".border-inner");
      let moveTimeout;

      btn.addEventListener("mousemove", function (e) {
        clearTimeout(moveTimeout);

        const rect = btn.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;

        gsap.to(inner, {
          x: x * 0.35,
          y: y * 0.35,
          duration: 0.08,
          ease: "power2.out",
        });

        gsap.to(outer, {
          x: x * 0.15,
          y: y * 0.15,
          duration: 0.25,
          ease: "power2.out",
        });

        gsap.to(text, {
          x: x * 0.1,
          y: y * 0.1,
          duration: 0.2,
          ease: "power2.out",
        });

        moveTimeout = setTimeout(function () {
          gsap.to([inner, outer], {
            x: x * 0.1,
            y: y * 0.1,
            duration: 0.2,
            ease: "power2.out",
          });
          gsap.to(text, {
            x: x * 0.1,
            y: y * 0.1,
            duration: 0.25,
            ease: "power2.out",
          });
        }, 100);
      });

      btn.addEventListener("mouseleave", function () {
        clearTimeout(moveTimeout);
        gsap.to([inner, outer, text], {
          x: 0,
          y: 0,
          duration: 0.25,
          ease: "power2.out",
        });
      });
    });
  },

  // =====================================
  // DEMO IMAGES ANIMATION
  // =====================================
  animateDemoImages: function () {
    const demoImgs = document.querySelectorAll(".demo-img, .ser-anim");

    if (!demoImgs.length) return;

    gsap.utils.toArray(demoImgs).forEach((img, i) => {
      gsap.fromTo(
        img,
        { y: 80, opacity: 0 },
        {
          y: 0,
          opacity: 1,
          duration: 0.8,
          ease: "power3.out",
          delay: i * 0.03,
          scrollTrigger: {
            trigger: img,
            start: "top 95%",
            end: "bottom 80%",
            toggleActions: "play none none none",
            once: true,
          },
        }
      );
    });
  },

  // =====================================
  // SERVICE CARDS ANIMATION
  // =====================================
  animateServiceCards: function () {
    const serviceCards = document.querySelectorAll(
      ".ser-card14-card1, .ser-card14-card2, .ser-card14-card3, .ser-card14-card4, .ser-card14-card5"
    );

    if (!serviceCards.length) return;

    gsap.utils.toArray(serviceCards).forEach((card, i) => {
      gsap.fromTo(
        card,
        { y: 50, opacity: 0 },
        {
          y: 0,
          opacity: 1,
          duration: 0.1,
          ease: "power3.out",
          delay: i * 0.2,
          scrollTrigger: {
            trigger: card,
            start: "top 90%",
            end: "bottom 80%",
            toggleActions: "play none none none",
            once: true,
          },
        }
      );
    });
  },

  // =====================================
  // SECTION 24 ANIMATION
  // =====================================
  animateSection24: function () {
    const funfact = document.querySelector(".funfact-content24");
    if (funfact) {
      gsap.from(".funfact-content24", {
        x: 100,
        opacity: 0,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: {
          trigger: ".funfact-content24",
          start: "top 85%",
          toggleActions: "play none none none",
          once: true,
        },
      });
    }

    const serContent = document.querySelector(".ser-content24");
    if (serContent) {
      gsap.from(".ser-content24", {
        x: -100,
        opacity: 0,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: {
          trigger: ".ser-content24",
          start: "top 85%",
          toggleActions: "play none none none",
          once: true,
        },
      });
    }
  },
};

/**
 * ========================================
 * SWIPER SLIDERS - PURE JAVASCRIPT
 * ========================================

 */

var SwiperSliders = {
  init: function () {
    if (typeof Swiper === "undefined") return;

    // Brand Slider
    if (document.querySelector(".brand")) {
      new Swiper(".brand", {
        loop: true,
        autoplay: { delay: 3000, disableOnInteraction: false },
        slidesPerView: 4,
        spaceBetween: 20,
        breakpoints: {
          1920: { slidesPerView: 4, spaceBetween: 30 },
          1440: { slidesPerView: 4, spaceBetween: 30 },
          1366: { slidesPerView: 4, spaceBetween: 30 },
          1201: { slidesPerView: 4, spaceBetween: 30 },
          769: { slidesPerView: 3, spaceBetween: 30 },
          480: { slidesPerView: 2, spaceBetween: 20 },
          375: { slidesPerView: 2, spaceBetween: 20 },
        },
      });
    }

    // Pro7 Slider
    if (document.querySelector(".pro7")) {
      new Swiper(".pro7", {
        loop: true,
        autoplay: { delay: 3000, disableOnInteraction: false },
        slidesPerView: 2,
        spaceBetween: 20,
        breakpoints: {
          1920: { slidesPerView: 2, spaceBetween: 30 },
          1440: { slidesPerView: 2, spaceBetween: 30 },
          1366: { slidesPerView: 2, spaceBetween: 30 },
          1201: { slidesPerView: 2, spaceBetween: 30 },
          769: { slidesPerView: 1, spaceBetween: 30 },
          480: { slidesPerView: 1, spaceBetween: 20 },
          375: { slidesPerView: 1, spaceBetween: 20 },
        },
      });
    }

    // Brand Slider 2
    if (document.querySelector(".brand2")) {
      new Swiper(".brand2", {
        loop: true,
        autoplay: { delay: 3000, disableOnInteraction: false },
        navigation: { nextEl: ".brand-next", prevEl: ".brand-prev" },
        slidesPerView: 6,
        spaceBetween: 20,
        breakpoints: {
          1920: { slidesPerView: 6, spaceBetween: 30 },
          1201: { slidesPerView: 5, spaceBetween: 30 },
          1025: { slidesPerView: 4, spaceBetween: 30 },
          769: { slidesPerView: 3, spaceBetween: 30 },
          577: { slidesPerView: 2, spaceBetween: 30 },
          480: { slidesPerView: 2, spaceBetween: 30 },
          375: { slidesPerView: 2, spaceBetween: 30 },
        },
      });
    }

    // Choose Slider 2
    if (document.querySelector(".choose-slider2")) {
      new Swiper(".choose-slider2", {
        loop: true,
        autoplay: { delay: 3000, disableOnInteraction: false },
        slidesPerView: 3,
        spaceBetween: 20,
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        breakpoints: {
          1440: { slidesPerView: 3, spaceBetween: 30 },
          1366: { slidesPerView: 3, spaceBetween: 30 },
          1201: { slidesPerView: 3, spaceBetween: 30 },
          1025: { slidesPerView: 2, spaceBetween: 30 },
          769: { slidesPerView: 2, spaceBetween: 30 },
          577: { slidesPerView: 1, spaceBetween: 30 },
          480: { slidesPerView: 1, spaceBetween: 30 },
          375: { slidesPerView: 1, spaceBetween: 30 },
        },
      });
    }

    // Project Slider 10
    if (document.querySelector(".project-slider10")) {
      new Swiper(".project-slider10", {
        loop: true,
        // autoplay: { delay: 3000, disableOnInteraction: false },
        slidesPerView: 4.5,
        spaceBetween: 20,
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        breakpoints: {
          1440: { slidesPerView: 4.5, spaceBetween: 30 },
          1366: { slidesPerView: 4.5, spaceBetween: 30 },
          1201: { slidesPerView: 4.5, spaceBetween: 30 },
          1025: { slidesPerView: 3.5, spaceBetween: 30 },
          769: { slidesPerView: 2.4, spaceBetween: 30 },
          577: { slidesPerView: 2.3, spaceBetween: 20 },
          480: { slidesPerView: 1, spaceBetween: 20 },
          375: { slidesPerView: 1, spaceBetween: 20 },
        },
      });
    }

    // Choose Slider 4
    if (document.querySelector(".choose-slider4")) {
      new Swiper(".choose-slider4", {
        loop: true,
        autoplay: { delay: 3000, disableOnInteraction: false },
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        breakpoints: {
          1440: { slidesPerView: 4, spaceBetween: 0 },
          1366: { slidesPerView: 3, spaceBetween: 0 },
          1201: { slidesPerView: 3, spaceBetween: 0 },
          1025: { slidesPerView: 3, spaceBetween: 0 },
          769: { slidesPerView: 2, spaceBetween: 0 },
          577: { slidesPerView: 2, spaceBetween: 0 },
          480: { slidesPerView: 1, spaceBetween: 0 },
          375: { slidesPerView: 1, spaceBetween: 0 },
        },
      });
    }

    // Testimonial Slide 5
    if (document.querySelector(".testi-slide5")) {
      new Swiper(".testi-slide5", {
        loop: true,
        autoplay: { delay: 3000, disableOnInteraction: false },
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        breakpoints: {
          1440: { slidesPerView: 4, spaceBetween: 40 },
          1366: { slidesPerView: 3, spaceBetween: 20 },
          1201: { slidesPerView: 3, spaceBetween: 20 },
          1025: { slidesPerView: 2, spaceBetween: 20 },
          769: { slidesPerView: 2, spaceBetween: 20 },
          577: { slidesPerView: 2, spaceBetween: 20 },
          480: { slidesPerView: 1, spaceBetween: 20 },
          375: { slidesPerView: 1, spaceBetween: 20 },
        },
      });
    }

    // Project Slider (with custom dots)
    if (document.querySelector(".project-slider")) {
      var projectSwiper = new Swiper(".project-slider", {
        loop: true,
        slidesPerView: 4,
        spaceBetween: 0,
        autoplay: { delay: 3000, disableOnInteraction: false },
        breakpoints: {
          1920: { slidesPerView: 4 },
          1440: { slidesPerView: 4 },
          1366: { slidesPerView: 3 },
          1201: { slidesPerView: 3 },
          769: { slidesPerView: 2 },
          577: { slidesPerView: 1 },
          480: { slidesPerView: 1 },
          375: { slidesPerView: 1 },
        },
        on: {
          init: function () {
            SwiperSliders.createCustomDots(this);
            SwiperSliders.updateDots(this.realIndex);
          },
          slideChangeTransitionEnd: function () {
            SwiperSliders.updateDots(this.realIndex);
          },
          slidesLengthChange: function () {
            SwiperSliders.updateDots(this.realIndex);
          },
        },
      });
    }

    // Project Slider 4
    if (document.querySelector(".project-slider4")) {
      new Swiper(".project-slider4", {
        loop: true,
        slidesPerView: 4,
        spaceBetween: 20,
        autoplay: { delay: 3000, disableOnInteraction: false },
        navigation: {
          nextEl: ".slider-btn4 .swiper-button-next",
          prevEl: ".slider-btn4 .swiper-button-prev",
        },
        breakpoints: {
          1920: { slidesPerView: 4 },
          1440: { slidesPerView: 4 },
          1366: { slidesPerView: 3 },
          1201: { slidesPerView: 3 },
          769: { slidesPerView: 2 },
          577: { slidesPerView: 1 },
          480: { slidesPerView: 1 },
          375: { slidesPerView: 1 },
        },
      });
    }

    // Service Slider 15
    if (document.querySelector(".ser-slider15")) {
      new Swiper(".ser-slider15", {
        loop: true,
        slidesPerView: 4.3,
        spaceBetween: 20,
        autoplay: { delay: 3000, disableOnInteraction: false },
        navigation: {
          nextEl: ".slider-btn5 .swiper-button-next",
          prevEl: ".slider-btn5 .swiper-button-prev",
        },
        breakpoints: {
          1920: { slidesPerView: 4.3 },
          1440: { slidesPerView: 4.3 },
          1366: { slidesPerView: 3.3 },
          1201: { slidesPerView: 3.3 },
          769: { slidesPerView: 2.3 },
          577: { slidesPerView: 1.3 },
          480: { slidesPerView: 1.3 },
          375: { slidesPerView: 1.2 },
        },
      });
    }

    // Testimonial Slider
    if (document.querySelector(".testi-slider")) {
      new Swiper(".testi-slider", {
        loop: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        slidesPerView: 1,
        spaceBetween: 200,
        pagination: { el: ".swiper-pagination", clickable: true },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
      });
    }

    // Testimonial Slider 4 (with custom dots)
    if (document.querySelector(".testi-slider4")) {
      new Swiper(".testi-slider4", {
        loop: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        slidesPerView: 1,
        spaceBetween: 200,
        pagination: { el: ".swiper-pagination", clickable: true },
        on: {
          init: function () {
            SwiperSliders.createCustomDots(this);
            SwiperSliders.updateDots(this.realIndex);
          },
          slideChangeTransitionEnd: function () {
            SwiperSliders.updateDots(this.realIndex);
          },
          slidesLengthChange: function () {
            SwiperSliders.updateDots(this.realIndex);
          },
        },
      });
    }
  },

  // =====================================
  // CUSTOM DOTS CREATOR (Pure JS)
  // =====================================
  createCustomDots: function (swiper) {
    const container = document.querySelector(".custom-pagination");
    if (!container) return;

    // Clear existing dots
    container.innerHTML = "";

    // Create 3 dots
    for (let i = 0; i < 3; i++) {
      const dot = document.createElement("span");
      dot.className = "custom-dot";
      dot.setAttribute("data-index", i);
      container.appendChild(dot);
    }

    // Add click event to all dots
    const dots = document.querySelectorAll(".custom-dot");
    dots.forEach(function (dot) {
      dot.addEventListener("click", function () {
        const index = parseInt(this.getAttribute("data-index"));
        swiper.slideToLoop(index, 600);
      });
    });
  },

  // =====================================
  // UPDATE DOTS (Pure JS)
  // =====================================
  updateDots: function (realIndex) {
    const dots = document.querySelectorAll(".custom-dot");

    // Remove active class from all dots
    dots.forEach(function (dot) {
      dot.classList.remove("active");
    });

    // Add active class to current dot
    const activeDot = realIndex % 3;
    if (dots[activeDot]) {
      dots[activeDot].classList.add("active");
    }
  },
};

// ============================
// Light Gallery
// ============================
var LightGalleryInit = {
  init: function () {
    if (typeof lightGallery === "undefined") return;

    // Light Gallery 1
    var lg1 = document.getElementById("lightgallery");
    if (lg1) {
      lightGallery(lg1, {
        selector: ".project-block7",
        plugins: [lgZoom, lgThumbnail],
        speed: 500,
        thumbnail: true,
        licenseKey: "your-license-key",
      });
    }

    // Light Gallery 2
    var lg2 = document.getElementById("lightgallery2");
    if (lg2) {
      lightGallery(lg2, {
        selector: ".isotope-item",
        plugins: [lgZoom, lgThumbnail],
        speed: 500,
        thumbnail: true,
        licenseKey: "your-license-key",
      });
    }

    // Light Gallery 3
    var lg3 = document.getElementById("lightgallery3");
    if (lg3) {
      lightGallery(lg3, {
        selector: ".project-block7",
        plugins: [lgZoom, lgThumbnail],
        speed: 500,
        thumbnail: true,
        licenseKey: "your-license-key",
      });
    }
  },
};

// ============================
// Isotope Filter
// ============================
var IsotopeFilter = {
  init: function () {
    // Check if Isotope is loaded
    if (typeof Isotope === "undefined") return;

    // ===========================
    // Isotope Container 1 (Masonry)
    // ===========================
    var grid = document.querySelector(".iso-container");

    if (grid) {
      var iso = new Isotope(grid, {
        itemSelector: ".isotope-item",
        percentPosition: true,
        masonry: {
          columnWidth: ".grid-sizer",
        },
      });

      // Fix layout after images load
      imagesLoaded(grid, function () {
        iso.layout();
      });

      // Filter buttons
      var filters = document.querySelectorAll("#custom-filter a");

      filters.forEach(function (btn) {
        btn.addEventListener("click", function (e) {
          e.preventDefault();

          // Remove Active
          filters.forEach((f) => f.classList.remove("active"));

          // Add Active
          this.classList.add("active");

          // Filter Value
          var filterValue = this.getAttribute("data-filter");
          iso.arrange({ filter: filterValue });
        });
      });
    }

    // ===========================
    // Isotope Container 2 (FitRows)
    // ===========================
    var grid2 = document.querySelector(".iso-container2");

    if (grid2) {
      var iso2 = new Isotope(grid2, {
        itemSelector: ".isotope-item",
        layoutMode: "fitRows",
        fitRows: {
          gutter: 0,
        },
      });

      // Default First Active
      var firstItem = document.querySelector(
        "#custom-filter li:first-child > a"
      );
      if (firstItem) firstItem.classList.add("is-checked");

      var filterBtns2 = document.querySelectorAll("#custom-filter a");

      filterBtns2.forEach(function (btn) {
        btn.addEventListener("click", function (e) {
          e.preventDefault();

          document
            .querySelectorAll("#custom-filter .is-checked")
            .forEach((item) => item.classList.remove("is-checked"));

          this.classList.add("is-checked");

          var customSelector = this.getAttribute("data-filter");

          iso2.arrange({
            filter: customSelector,
          });
        });
      });
    }
  },
};

// ============================
// Custom Cursor
// ============================
var AieroEffects = {
  init: function () {
    var customCursor = document.querySelector(".custom-cursor");
    var cursorBlocks = document.querySelectorAll(
      ".project-block8, .project-block7, .project-img10, .test-block5"
    );

    if (customCursor && cursorBlocks.length) {
      // Move custom cursor to body
      document.body.appendChild(customCursor);

      // Set initial cursor styles
      Object.assign(customCursor.style, {
        position: "fixed",
        top: "0px",
        left: "0px",
        pointerEvents: "none",
        zIndex: "99999",
        opacity: "0",
        transform: "translate(-50%, -50%) scale(0)",
        transition: "transform 0.15s ease, opacity 0.2s ease",
      });

      let isDragging = false;

      // ===== Demo Hover Boxes =====
      const demoImgs = document.querySelectorAll(".demo-img");
      demoImgs.forEach((img) => {
        const hoverBox = img.querySelector(".demo-hover");
        if (hoverBox) {
          document.body.appendChild(hoverBox); // Move to body for fixed positioning

          // Follow mouse movement
          img.addEventListener("mousemove", (e) => {
            hoverBox.style.left = e.clientX + 10 + "px";
            hoverBox.style.top = e.clientY + 10 + "px";
          });

          // Show/hide hover box
          img.addEventListener("mouseenter", () => {
            hoverBox.classList.add("show");
          });
          img.addEventListener("mouseleave", () => {
            hoverBox.classList.remove("show");
          });
        }
      });

      // ===== Custom Cursor =====
      cursorBlocks.forEach((block) => {
        block.addEventListener("mouseenter", function () {
          customCursor.style.opacity = "1";
          customCursor.style.transform = "translate(-50%, -50%) scale(1)";
        });

        block.addEventListener("mouseleave", function () {
          customCursor.style.opacity = "0";
          customCursor.style.transform = "translate(-50%, -50%) scale(0)";
        });
      });

      // Global cursor movement
      document.addEventListener("mousemove", function (e) {
        customCursor.style.top = e.clientY + "px";
        customCursor.style.left = e.clientX + "px";
      });

      // Drag states
      document.addEventListener("mousedown", function () {
        isDragging = true;
        customCursor.classList.add("dragging");
      });

      document.addEventListener("mouseup", function () {
        isDragging = false;
        customCursor.classList.remove("dragging");
      });

      // Swiper Sync Fix
      if (typeof Swiper !== "undefined") {
        document.querySelectorAll(".swiper").forEach(function (el) {
          const swiperInstance = el.swiper;
          if (swiperInstance) {
            swiperInstance.on("touchMove", function (swiper, e) {
              if (e && e.clientX && e.clientY) {
                customCursor.style.top = e.clientY + "px";
                customCursor.style.left = e.clientX + "px";
              }
            });

            swiperInstance.on("touchStart", function () {
              customCursor.classList.add("dragging");
            });

            swiperInstance.on("touchEnd", function () {
              customCursor.classList.remove("dragging");
            });
          }
        });
      }
    }
  },
};
// ============================
// Body Active mode
// ============================
var ThemeToggle = {
  init: function () {
    var body = document.body;
    var themeBtn = document.getElementById("themeBtn");
    var darkModeImages = document.querySelectorAll(
      ".darkModeTrigger, .darkModeTriggerImg, .darkModeTriggerImg2"
    );

    // ✅ Apply stored preferences on page load
    if (localStorage.getItem("themeMode") === "active") {
      body.classList.add("active-body", "dark-mode");
      if (themeBtn) themeBtn.classList.add("active-btn");
    }

    // ✅ Button toggle without refresh
    if (themeBtn) {
      themeBtn.addEventListener("click", function () {
        toggleDarkMode();
      });
    }

    // ✅ Image toggle without refresh
    darkModeImages.forEach(function (el) {
      el.addEventListener("click", function (e) {
        e.preventDefault(); // prevent redirect
        toggleDarkMode();
      });
    });

    // ✅ Toggle function WITHOUT refresh
    function toggleDarkMode() {
      if (!body.classList.contains("active-body")) {
        // Turn ON
        body.classList.add("active-body", "dark-mode");
        if (themeBtn) themeBtn.classList.add("active-btn");
        localStorage.setItem("themeMode", "active");
        localStorage.setItem("darkMode", "enabled");
      } else {
        // Turn OFF
        body.classList.remove("active-body", "dark-mode");
        if (themeBtn) themeBtn.classList.remove("active-btn");
        localStorage.setItem("themeMode", "inactive");
        localStorage.setItem("darkMode", "disabled");
      }
    }
  },
};

// ============================
// Parallax Efect
// ============================
var AieroParallax = {
  init: function () {
    const parallaxSections = document.querySelectorAll(
      ".contact-banner3, .newsletter-banner, .video-banner4"
    );
    if (!parallaxSections.length) return;

    const speed = 0.2;

    function handleScroll() {
      const scrollTop = window.scrollY || window.pageYOffset;

      parallaxSections.forEach((section) => {
        const img = section.querySelector(".parallax-img");
        if (!img) return;

        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        const distance = scrollTop - sectionTop;

        // Move only while section is visible
        if (distance >= -window.innerHeight && distance <= sectionHeight) {
          img.style.transform = `translateY(${distance * speed}px)`;
        }
      });
    }

    window.addEventListener("scroll", handleScroll);
    handleScroll();
  },
};

// ============================
// OGLDeformEffect
// ============================
var OGLDeformEffect = {
  init: function (selector, imagePath) {
    const section = document.querySelector(selector);
    if (!section) return;

    // Always show the image as background for mobile
    section.style.backgroundImage = `url(${imagePath})`;
    section.style.backgroundSize = "cover";
    section.style.backgroundPosition = "center";

    // Check viewport width - disable effect on mobile (768px and below)

    if (window.innerWidth <= 768) {
      // Mobile: Show image with custom CSS
      section.style.backgroundImage = `url(${imagePath})`;
      section.style.backgroundSize = "auto";
      section.style.backgroundPosition = "center -156px";
      section.style.backgroundRepeat = "no-repeat";
      return; // Exit early on mobile devices
    }

    import("https://cdn.skypack.dev/ogl").then(
      ({ Renderer, Geometry, Program, Mesh, Texture, Flowmap, Vec2 }) => {
        // Create canvas
        const canvas = document.createElement("canvas");
        canvas.classList.add("fluid-canvas");
        canvas.style.position = "absolute";
        canvas.style.top = 0;
        canvas.style.left = 0;
        canvas.style.width = "100%";
        canvas.style.height = "100%";
        canvas.style.borderRadius = "inherit";
        canvas.style.pointerEvents = "none"; // so it doesn't block hover
        section.style.position = "relative";
        section.prepend(canvas);

        const renderer = new Renderer({ dpr: 2, canvas });
        const gl = renderer.gl;
        renderer.setSize(section.clientWidth, section.clientHeight);
        gl.clearColor(0, 0, 0, 0);

        const vertex = `
          attribute vec2 uv;
          attribute vec2 position;
          varying vec2 vUv;
          void main() {
              vUv = uv;
              gl_Position = vec4(position, 0, 1);
          }`;

        const fragment = `
          precision highp float;
          uniform sampler2D tWater;
          uniform sampler2D tFlow;
          varying vec2 vUv;
          void main() {
              vec3 flow = texture2D(tFlow, vUv).rgb;
              vec2 uv = vUv - flow.xy * 0.08;
              vec3 color = texture2D(tWater, uv).rgb;
              gl_FragColor = vec4(color, 1.0);
          }`;

        const geometry = new Geometry(gl, {
          position: {
            size: 2,
            data: new Float32Array([-1, -1, 3, -1, -1, 3]),
          },
          uv: { size: 2, data: new Float32Array([0, 0, 2, 0, 0, 2]) },
        });

        const texture = new Texture(gl);
        const img = new Image();
        img.crossOrigin = "Anonymous";
        img.src = imagePath;
        img.onload = () => (texture.image = img);

        const flowmap = new Flowmap(gl, {
          falloff: 0.35,
          alpha: 1,
          dissipation: 0.95,
        });

        const program = new Program(gl, {
          vertex,
          fragment,
          uniforms: {
            tWater: { value: texture },
            tFlow: flowmap.uniform,
          },
        });

        const mesh = new Mesh(gl, { geometry, program });

        const mouse = new Vec2(-1);
        const velocity = new Vec2();
        const lastMouse = new Vec2();
        let lastTime;
        let isMoving = false;
        let idleTimeout;

        section.addEventListener("mousemove", function (e) {
          updateMouse(e);
          isMoving = true;
          clearTimeout(idleTimeout);
          idleTimeout = setTimeout(() => (isMoving = false), 100);
        });

        section.addEventListener("mouseleave", function () {
          mouse.set(-1);
          velocity.set(0);
          isMoving = false;
        });

        function updateMouse(e) {
          const rect = section.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;

          mouse.set(x / rect.width, 1.0 - y / rect.height);

          if (!lastTime) {
            lastTime = performance.now();
            lastMouse.set(x, y);
          }

          const deltaX = x - lastMouse.x;
          const deltaY = y - lastMouse.y;
          lastMouse.set(x, y);

          const time = performance.now();
          const delta = Math.max(16, time - lastTime);
          lastTime = time;

          velocity.x = deltaX / delta;
          velocity.y = deltaY / delta;
        }

        // Handle window resize
        window.addEventListener("resize", function () {
          if (window.innerWidth <= 768) {
            // Show background image and remove canvas on mobile
            section.style.backgroundImage = `url(${imagePath})`;
            canvas.remove();
            return;
          }
          renderer.setSize(section.clientWidth, section.clientHeight);
        });

        function update() {
          requestAnimationFrame(update);
          if (!isMoving) {
            velocity.x *= 0.9;
            velocity.y *= 0.9;
          }
          flowmap.mouse.copy(mouse);
          flowmap.velocity.lerp(velocity, 0.15);
          flowmap.update();
          renderer.render({ scene: mesh });
        }
        update();
      }
    );
  },
};

var SignupToggle = {
  init: function () {
    var signupLink = document.getElementById("signupLink");
    var customForm = document.getElementById("custom-form4");

    if (!signupLink || !customForm) return;

    signupLink.addEventListener("click", function (e) {
      e.preventDefault();
      customForm.classList.toggle("active");

      if (customForm.classList.contains("active")) {
        signupLink.textContent = "Back to Sign in";
      } else {
        signupLink.textContent = "Sign up";
      }
    });
  },
};

// ============================
// hide and show
// ============================
var PasswordToggle = {
  init: function () {
    var toggleBtn = document.getElementById("togglePassword");
    var passwordInput = document.getElementById("passwordInput");

    if (!toggleBtn || !passwordInput) return;

    var icon = toggleBtn.querySelector("i");
    if (!icon) return;

    toggleBtn.addEventListener("click", function () {
      // Toggle password type
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        passwordInput.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    });
  },
};

var LenisScroll = {
  /**
   * Setup Lenis smooth scroll with optimized settings
   */
  setupLenis: function () {
    const lenis = new Lenis({
      // Increased duration for smoother, slower scroll
      duration: 2.8,

      // Smoother easing function (less aggressive)
      easing: (t) => {
        // Custom easing: easeOutExpo for very smooth deceleration
        return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
      },

      // Alternative smoother easing options:
      // easing: (t) => t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1, // easeInOutCubic
      // easing: (t) => 1 - Math.pow(1 - t, 3), // easeOutCubic

      // Direction of scroll
      direction: "vertical",

      // Gesture orientation
      gestureDirection: "vertical",

      // Smooth scroll behavior
      smooth: true,

      // Mouse multiplier (lower = slower, smoother)
      mouseMultiplier: 0.8,

      // Smooth on touch devices
      smoothTouch: false, // Set to true for touch devices (can be laggy on mobile)

      // Touch multiplier
      touchMultiplier: 1.5,

      // Infinite scroll
      infinite: false,

      // Orientation
      orientation: "vertical",

      // Lerp intensity (0-1, lower = smoother but slower response)
      lerp: 0.05, // Try values between 0.05 - 0.15

      // Wheel event target
      wheelEventsTarget: document.body,
    });

    // Store instance
    this.lenis = lenis;

    // Smoother RAF loop
    function raf(time) {
      lenis.raf(time);
      requestAnimationFrame(raf);
    }

    requestAnimationFrame(raf);

    // Integrate with GSAP ScrollTrigger
    lenis.on("scroll", ScrollTrigger.update);

    // Alternative GSAP integration (more performant)
    gsap.ticker.add((time) => {
      lenis.raf(time * 1000);
    });

    gsap.ticker.lagSmoothing(0);

    // Optional: Prevent default scroll behavior
    lenis.on("scroll", (e) => {
      // You can add custom logic here
    });

    // Stop Lenis on specific elements (like modals, etc.)
    const stopElements = document.querySelectorAll("[data-lenis-prevent]");
    stopElements.forEach((el) => {
      el.addEventListener("wheel", (e) => {
        e.stopPropagation();
      });
    });
  },

  /**
   * The main initialization method.
   */
  init: function () {
    const self = this;

    setTimeout(() => {
      if (
        typeof Lenis !== "function" ||
        typeof gsap === "undefined" ||
        typeof ScrollTrigger === "undefined"
      ) {
        console.error("Lenis library not loaded. Check your CDN script tag.");
        return;
      }

      gsap.registerPlugin(ScrollTrigger);
      self.setupLenis();
    }, 0);
  },

  /**
   * Get Lenis instance
   */
  getInstance: function () {
    return this.lenis;
  },

  /**
   * Stop smooth scroll
   */
  stop: function () {
    if (this.lenis) {
      this.lenis.stop();
    }
  },

  /**
   * Start smooth scroll
   */
  start: function () {
    if (this.lenis) {
      this.lenis.start();
    }
  },

  /**
   * Scroll to specific position
   */
  scrollTo: function (target, options = {}) {
    if (this.lenis) {
      this.lenis.scrollTo(target, options);
    }
  },
};

// Pure JS modules (NO jQuery dependency)
document.addEventListener("DOMContentLoaded", function () {
  // Side menus (ensure DOM ready and after layout/UI elements)
  SideMenu.init();
  SideMenu2.init();
  SideMenu3.init();
  SidebarToggle.init();

  // Layout / core
  Preloader.init();
  ThemeToggle.init();
  StickyHeader.init();
  AieroParallax.init();

  // Visual effects / animations
  LenisScroll.init();
  GsapAnimations.init();
  AieroEffects.init();
  OGLDeformEffect.init(".hero-style8", "assets/images/bg/hero8.png");
  OGLDeformEffect.init(".main-sec6", "assets/images/bg/intro-bg.png");

  // Media
  VideoModal.init();
  LightGalleryInit.init();

  // UI components
  SwiperSliders.init();
  SignupToggle.init();
  ScrollToTop.init();
  PasswordToggle.init();
  HorizontalScroll.init();
  PriceFilter.init();
  SearchPopup.init();
  ActiveMenu.init();
  CounterAnimation.init();
  IsotopeFilter.init();
});


/**
 * ============================
 * END OF FILE
 * ============================
 */
