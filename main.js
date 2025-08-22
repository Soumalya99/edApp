var swiper = new Swiper(".mySwiper", {
  slidesPerView: 1,
  spaceBetween: 24,
  slidesPerGroup: 1,
  loop: true,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  breakpoints: {
    640: {
      slidesPerView: 2,
      slidesPerGroup: 2,
    },
    1024: {
      slidesPerView: 3,
      slidesPerGroup: 3,
    },
  },
});


var testimonialSwiper = new Swiper(".testimonial-swiper", {
  loop: true,
  autoplay: {
    delay: 3500,
    disableOnInteraction: false,
  },
  slidesPerView: 1,
  grabCursor: true,
  pagination: {
    el: ".testimonial-swiper .swiper-pagination",
    clickable: true,
  }
});
class HeaderNav {
  constructor(options = {}) {
    const defaults = {
      // Element IDs/selectors
      headerId: "site-header",
      triggerId: "home-trigger",
      megaId: "mega-home",
      burgerId: "burger",
      mobileMenuId: "mobile-menu",
      categorySelector: ".mm-cat",
      mobileToggleSelector: "[data-mobile-toggle]",
      navLinkSelector: "[data-anim='nav-link']",

      // Panels mapping by key
      panelsMap: {
        iat: "#panel-iat",
        isi: "#panel-isi",
        phd: "#panel-phd"
      },

      // Behavior
      defaultCategory: "iat",
      closeDelayMs: 120,
      desktopMinWidth: 768
    };

    this.cfg = { ...defaults, ...options };

    // State
    this.megaOpen = false;
    this.closeTimer = null;

    // Elements (resolved in init)
    this.header = null;
    this.trigger = null;
    this.mega = null;
    this.burger = null;
    this.mobileMenu = null;
    this.categoryButtons = [];
    this.panels = {};
    this.mobileToggles = [];
    this.mq = null;

    // Bound handlers to allow add/removeEventListener safely
    this._onTriggerEnter = this.openMega.bind(this);
    this._onTriggerFocus = this.openMega.bind(this);
    this._onTriggerLeave = this.scheduleClose.bind(this);
    this._onTriggerBlur = this.scheduleClose.bind(this);
    this._onMegaEnter = this._clearCloseTimer.bind(this);
    this._onMegaLeave = this.scheduleClose.bind(this);
    this._onEsc = this._onEsc.bind(this);
    this._onDocClick = this._onDocClick.bind(this);
    this._onBurgerClick = this.toggleMobileMenu.bind(this);
    this._onMQChange = this._onMQChange.bind(this);
  }

  init() {
    const d = document;

    // Resolve elements
    this.header = d.getElementById(this.cfg.headerId);
    this.trigger = d.getElementById(this.cfg.triggerId);
    this.mega = d.getElementById(this.cfg.megaId);
    this.burger = d.getElementById(this.cfg.burgerId);
    this.mobileMenu = d.getElementById(this.cfg.mobileMenuId);

    // Build panels map
    this.panels = {};
    for (const [key, sel] of Object.entries(this.cfg.panelsMap)) {
      this.panels[key] = d.querySelector(sel);
    }

    // Category buttons
    this.categoryButtons = Array.from(d.querySelectorAll(this.cfg.categorySelector));

    // Mobile toggles
    this.mobileToggles = Array.from(d.querySelectorAll(this.cfg.mobileToggleSelector));

    // Match media for desktop breakpoint
    this.mq = window.matchMedia(`(min-width: ${this.cfg.desktopMinWidth}px)`);
    this.mq.addEventListener?.("change", this._onMQChange);

    // GSAP intro animations
    if (window.gsap && this.header) {
      gsap.from(this.header, { y: -40, opacity: 0, duration: 0.6, ease: "power2.out", clearProps: "all" });
      const navLinks = d.querySelectorAll(this.cfg.navLinkSelector);
      if (navLinks.length) {
        gsap.from(navLinks, {
          y: -8,
          opacity: 0,
          duration: 0.4,
          stagger: 0.06,
          delay: 0.15,
          ease: "power2.out",
          clearProps: "all"
        });
      }
    }

    // Desktop mega menu interactions
    if (this.trigger && this.mega) {
      this.trigger.addEventListener("mouseenter", this._onTriggerEnter);
      this.trigger.addEventListener("focus", this._onTriggerFocus);
      this.trigger.addEventListener("mouseleave", this._onTriggerLeave);
      this.trigger.addEventListener("blur", this._onTriggerBlur);
      this.mega.addEventListener("mouseenter", this._onMegaEnter);
      this.mega.addEventListener("mouseleave", this._onMegaLeave);

      document.addEventListener("keydown", this._onEsc);
      document.addEventListener("click", this._onDocClick);
    }

    // Category switching
    this.categoryButtons.forEach((btn) => {
      const targetKey = btn.dataset.target;
      if (!targetKey) return;

      const onEnter = () => this.setActiveCategory(targetKey);
      const onClick = () => this.setActiveCategory(targetKey);

      btn.addEventListener("mouseenter", onEnter);
      btn.addEventListener("click", onClick);

      // Save listeners for cleanup
      btn._headerNavListeners = [
        ["mouseenter", onEnter],
        ["click", onClick]
      ];
    });

    // Initialize default panel
    this.setActiveCategory(this.cfg.defaultCategory);

    // Mobile menu toggles
    if (this.burger) {
      this.burger.addEventListener("click", this._onBurgerClick);
    }

    this.mobileToggles.forEach((btn) => {
      const onClick = () => {
        const key = btn.getAttribute("data-mobile-toggle");
        const panel = d.querySelector(`[data-mobile-panel="${key}"]`);
        if (!panel) return;

        const willOpen = panel.classList.contains("hidden");
        panel.classList.toggle("hidden");

        // update + / - indicator if present
        const indicator = btn.querySelector("span:last-child");
        if (indicator) indicator.textContent = willOpen ? "−" : "+";

        if (willOpen && window.gsap) {
          gsap.from(panel.children, {
            y: 6,
            opacity: 0,
            duration: 0.2,
            stagger: 0.03,
            ease: "power1.out",
            clearProps: "all"
          });
        }
      };

      btn.addEventListener("click", onClick);
      btn._headerNavListeners = [["click", onClick]];
    });

    return this;
  }

  // Public API
  openMega() {
    if (!this.mega || this.megaOpen) return;
    this.megaOpen = true;
    this.trigger?.setAttribute("aria-expanded", "true");
    this.mega.classList.remove("invisible", "opacity-0", "pointer-events-none");

    if (window.gsap) {
      // Animate only the currently visible panel's cards to avoid hidden-panel side effects
      const activePanel = this._getActivePanel();
      const cards = activePanel ? activePanel.querySelectorAll(".mm-card") : [];
      if (cards && cards.length) {
        gsap.killTweensOf(cards);
        gsap.from(cards, {
          y: 10,
          opacity: 0,
          duration: 0.28,
          stagger: 0.07,
          ease: "power1.out",
          clearProps: "transform,opacity"
        });
      }
    }
  }

  closeMega() {
    if (!this.mega || !this.megaOpen) return;
    this.megaOpen = false;
    this.trigger?.setAttribute("aria-expanded", "false");
    this.mega.classList.add("opacity-0", "invisible", "pointer-events-none");
  }

  scheduleClose() {
    this._clearCloseTimer();
    this.closeTimer = setTimeout(() => this.closeMega(), this.cfg.closeDelayMs);
  }

  setActiveCategory(key) {
    // Update button states
    this.categoryButtons.forEach((btn) => {
      const isActive = btn.dataset.target === key;
      btn.dataset.active = isActive ? "true" : "false";
      btn.classList.toggle("bg-blue-100", isActive);
      btn.classList.toggle("text-blue-700", isActive);
    });

    // Toggle panels
    Object.entries(this.panels).forEach(([k, el]) => {
      if (!el) return;
      if (k === key) {
        el.classList.remove("hidden");
        if (window.gsap && this.megaOpen) {
          const cards = el.querySelectorAll(".mm-card");
          if (cards.length) {
            gsap.killTweensOf(cards);
            gsap.from(cards, {
              y: 10,
              opacity: 0,
              duration: 0.25,
              stagger: 0.05,
              ease: "power1.out",
              clearProps: "transform,opacity"
            });
          }
        }
      } else {
        el.classList.add("hidden");
      }
    });
  }

  toggleMobileMenu() {
    if (!this.mobileMenu || !this.burger) return;
    const isHidden = this.mobileMenu.classList.contains("hidden");
    this.mobileMenu.classList.toggle("hidden");
    this.burger.setAttribute("aria-expanded", String(isHidden));

    if (isHidden && window.gsap) {
      const items = this.mobileMenu.querySelectorAll("a, button[data-mobile-toggle]");
      if (items.length) {
        gsap.killTweensOf(items);
        gsap.from(items, {
          y: 8,
          opacity: 0,
          duration: 0.25,
          stagger: 0.04,
          ease: "power1.out",
          clearProps: "transform,opacity"
        });
      }
    }
  }

  destroy() {
    // Clean desktop mega menu listeners
    if (this.trigger) {
      this.trigger.removeEventListener("mouseenter", this._onTriggerEnter);
      this.trigger.removeEventListener("focus", this._onTriggerFocus);
      this.trigger.removeEventListener("mouseleave", this._onTriggerLeave);
      this.trigger.removeEventListener("blur", this._onTriggerBlur);
    }
    if (this.mega) {
      this.mega.removeEventListener("mouseenter", this._onMegaEnter);
      this.mega.removeEventListener("mouseleave", this._onMegaLeave);
    }
    document.removeEventListener("keydown", this._onEsc);
    document.removeEventListener("click", this._onDocClick);

    // Category buttons
    this.categoryButtons.forEach((btn) => {
      (btn._headerNavListeners || []).forEach(([evt, fn]) => btn.removeEventListener(evt, fn));
      delete btn._headerNavListeners;
    });

    // Mobile
    if (this.burger) {
      this.burger.removeEventListener("click", this._onBurgerClick);
    }
    this.mobileToggles.forEach((btn) => {
      (btn._headerNavListeners || []).forEach(([evt, fn]) => btn.removeEventListener(evt, fn));
      delete btn._headerNavListeners;
    });

    // MQ
    this.mq?.removeEventListener?.("change", this._onMQChange);

    // Timers
    this._clearCloseTimer();
  }

  // Private helpers
  _getActivePanel() {
    // Only one panel should be visible at a time
    return this.mega?.querySelector(".mm-panel:not(.hidden)") || null;
  }

  _clearCloseTimer() {
    if (this.closeTimer) {
      clearTimeout(this.closeTimer);
      this.closeTimer = null;
    }
  }

  _onEsc(e) {
    if (e.key === "Escape") this.closeMega();
  }

  _onDocClick(e) {
    if (!this.megaOpen) return;
    const withinTrigger = this.trigger?.contains(e.target);
    const withinMega = this.mega?.contains(e.target);
    if (!withinTrigger && !withinMega) this.closeMega();
  }

  _onMQChange(e) {
    // Close mobile menu when moving to desktop
    if (e.matches) {
      this.mobileMenu?.classList.add("hidden");
      this.burger?.setAttribute("aria-expanded", "false");
    }
  }
}


// document.addEventListener('DOMContentLoaded', () => {
//   gsap.set('.selection-card', { opacity: 0, y: 60, scale: 0.96 });
//   gsap.to('.selection-card', {
//     opacity: 1,
//     y: 0,
//     scale: 1,
//     delay: 0.17,
//     duration: 1.2,
//     stagger: {
//       each: 0.15,
//       grid: [3, 3], // Stagger in a 3x3 grid order
//       from: "start",
//     },
//     ease: 'bounce.out'
//   });
// });

class SelectionCardsAnimation {
  init() {
    if (!window.gsap) return;
    const selectionCards = document.querySelectorAll('.selection-card');
    gsap.set(selectionCards, { opacity: 0, y: 60, scale: 0.96 });
    gsap.to(selectionCards, {
      opacity: 1,
      y: 0,
      scale: 1,
      delay: 0.8,
      duration: 1.2,
      stagger: {
        each: 0.15,
        grid: [3, 3],
        from: "start"
      },
      ease: 'bounce.out'
    });
  }
}

class FeaturesAnimation {
  init() {
    if (!window.gsap || !window.ScrollTrigger) return;
    gsap.registerPlugin(ScrollTrigger);

    const section = document.getElementById('features');
    if (!section) return;
    const cards = section.querySelectorAll('.bg-white');
    gsap.set(cards, { opacity: 0, y: 36 });

    const tl = gsap.timeline();

    tl.to(cards, {
      opacity: 1,
      y: 0,
      duration: 0.75,
      stagger: 0.22,
      ease: "power3.out"
    });
  }
}

class CurriculumSectionAnimation {
  init() {
    const section = document.querySelector('.curriculum-section');
    if (!section) return;

    const cards = section.querySelectorAll('.curriculum-card');
    const image = section.querySelector('.curriculum-image');
    const heading = section.querySelector('h2');

    if (!window.gsap) return;

    if (heading) {
      gsap.set(heading, { opacity: 0, y: -70 });
      gsap.to(heading, {
        opacity: 1,
        y: 0,
        duration: 1,
        ease: "bounce.out",
        delay: 0.15
      });
    }
    if (!cards.length || !image) return;

    let animating = false;
    const animateSection = (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !animating) {
          animating = true;
          gsap.set(image, { opacity: 0, x: 180 });
          cards.forEach(card => {
            gsap.set(card, {
              opacity: 0,
              y: gsap.utils.random(-100, 100),
              x: gsap.utils.random(-100, 100),
              rotation: gsap.utils.random(-30, 30),
              scale: gsap.utils.random(0.7, 0.93),
              filter: "blur(8px)"
            });
          });
          gsap.to(image, { opacity: 1, x: 0, duration: 1, ease: "power3.out" });
          cards.forEach(card => {
            gsap.to(card, {
              opacity: 1,
              x: 0, y: 0, rotation: 0, scale: 1,
              filter: "blur(0px)",
              duration: 1,
              ease: "power3.out",
              delay: gsap.utils.random(0.2, 0.7)
            });
          });
          setTimeout(() => { animating = false; }, 1300);
        }
      });
    };
    new IntersectionObserver(animateSection, { threshold: 0.4 }).observe(section);
  }
}


class StudentCardsAnimation {
  init() {
    if (!window.gsap || !window.ScrollTrigger) return;
    gsap.registerPlugin(ScrollTrigger);
    gsap.utils.toArray('.student-card').forEach((card, i) => {
      gsap.to(card, {
        scrollTrigger: {
          trigger: card,
          start: "top 85%",
          toggleActions: "play none none reverse",
        },
        opacity: 1,
        y: 0,
        duration: 0.7,
        delay: i * 0.12,
        ease: "power3.out"
      });
    });
  }
}


class AchieverCardsAnimation {
  init() {
    if (!window.gsap || !window.ScrollTrigger) return;
    gsap.registerPlugin(ScrollTrigger);

    gsap.fromTo('.about-heading, .learn-h2',
      { opacity: 0, y: 44 },
      {
        opacity: 1,
        y: 0,
        duration: 1,
        ease: "bounce.out",
        scrollTrigger: {
          trigger: "#about",
          start: "top 80%",
          toggleActions: "play none none reverse"
        }
      }
    );
    if (document.querySelector('.about-svg')) {
      gsap.fromTo('.about-svg',
        { opacity: 0, scale: 0.7 },
        {
          opacity: 1, scale: 1, duration: 0.8, ease: "power2.out",
          scrollTrigger: {
            trigger: "#about",
            start: "top 85%",
            toggleActions: "play none none reverse"
          }
        }
      );
    }
    const cards = document.querySelectorAll("#about .achiever-card");
    cards.forEach((card, i) => {
      const fromX = i === 0 ? -96 : 96;
      gsap.fromTo(
        card,
        { opacity: 0, x: fromX, scale: 0.90 },
        {
          opacity: 1,
          x: 0,
          scale: 1,
          duration: 1,
          delay: 0.15 + i * 0.13,
          ease: "power3.out",
          scrollTrigger: {
            trigger: card,
            start: "top 85%",
            toggleActions: "play none none reverse"
          }
        }
      );
    });
  }
}

class MentorCardsAnimation {
  init() {
    const cards = document.querySelectorAll('.mentor-card, .team-mentors-h3, h2');
    if (!window.gsap || !cards.length) return;

    // Set the initial state
    cards.forEach(card => {
      gsap.set(card, { y: 36, opacity: 0, scale: 0.9 });
    });

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
          gsap.to(entry.target, {
            opacity: 1,
            scale: 1,
            y: 0,
            duration: 0.7,
            ease: 'back.out(1.4)',
            delay: i * 0.12
          });
        } else {
          // Reset when scrolling out of view so entry animation can replay
          gsap.set(entry.target, { opacity: 0, y: 36, scale: 0.9 });
        }
      });
    }, { threshold: 0.35 });

    cards.forEach(card => {
      observer.observe(card);
    });
  }
}

class TeamTypewriterAnimation {
  constructor() {
    this.message = `At EduConnect, our dedicated team blends years of academic expertise with a profound passion for mentoring. Our founder, Dr. Meera Sharma, envisioned a learning space where every student is guided by top educators in Physics, Math, Chemistry, and Biology. Together, we are committed to nurturing talent, inspiring curiosity, and helping you achieve your highest dreams.`;
  }
  init() {
    const target = document.getElementById('team-typewriter');
    if (!target || !window.gsap) return;
    target.style.minHeight = "90px";
    target.textContent = "";

    let cursor = document.getElementById('typewriter-cursor');
    if (!cursor) {
      cursor = document.createElement('span');
      cursor.id = 'typewriter-cursor';
      cursor.textContent = '┃';
      cursor.style.animation = "blink 1s steps(1) infinite";
      cursor.style.color = "inherit";
      target.appendChild(cursor);
      if (!document.getElementById('typewriter-blink-style')) {
        const style = document.createElement('style');
        style.id = 'typewriter-blink-style';
        style.innerHTML = `
          @keyframes blink { 0%, 50% { opacity: 1; } 50.1%, 100% { opacity: 0; } }
          #typewriter-cursor { display: inline-block; vertical-align: baseline; }
        `;
        document.head.appendChild(style);
      }
    }
    let hasAnimated = false;
    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !hasAnimated) {
          hasAnimated = true;
          observer.unobserve(entry.target);
          const words = this.message.split(' ');
          let index = 0;
          let current = '';
          function typeNextWord() {
            if (index < words.length) {
              current += (index === 0 ? '' : ' ') + words[index];
              target.textContent = current;
              target.appendChild(cursor);
              index++;
              setTimeout(typeNextWord, 120 + Math.min(words[index - 1].length * 30, 290));
            } else {
              target.textContent = current;
            }
          }
          typeNextWord();
        }
      });
    }, { threshold: 0.23 });

    observer.observe(target);
  }
}


class FounderImagesAnimation {
  init() {
    const imgs = document.querySelectorAll('.founder-img');
    if (!window.gsap || imgs.length !== 2) return;
    gsap.set(imgs[0], { opacity: 0, x: -60, scale: 0.96 });
    gsap.set(imgs[1], { opacity: 0, x: 60, scale: 0.96 });

    const section = document.querySelector('.team-member');
    let hasAnimated = false;
    if (section) {
      new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting && !hasAnimated) {
            hasAnimated = true;
            gsap.to(imgs[0], {
              opacity: 1,
              x: 0,
              scale: 1,
              duration: 1.1,
              ease: "power3.out"
            });
            gsap.to(imgs[1], {
              opacity: 1,
              x: 0,
              scale: 1,
              duration: 1.1,
              ease: "power3.out",
              delay: 0.18
            });
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.32 }).observe(section);
    }
  }
}

class LottieGsapPinAnimation {
  /**
   * @param {Object} options
   * @param {string} options.containerId - The ID of the Lottie container DIV
   * @param {string} options.animationDataUrl - Public URL or path to lottie JSON animation
   */
  constructor({ containerId, animationDataUrl }) {
    this.containerId = containerId;
    this.animationDataUrl = animationDataUrl;
    this.lottieContainer = null;
    this.lottieInstance = null;
    this.lottieLoaded = false;
    this.scrollTriggerInstance = null;
  }

  init() {
    if (!window.gsap || !window.ScrollTrigger || !window.lottie) {
      console.warn("GSAP, ScrollTrigger, or Lottie is not loaded.");
      return;
    }

    this.lottieContainer = document.getElementById(this.containerId);
    if (!this.lottieContainer) {
      console.warn(`Lottie container #${this.containerId} not found`);
      return;
    }

    // Reset container (in case of HMR or SPA navigation)
    this.lottieContainer.innerHTML = "";
    this.lottieInstance = null;
    this.lottieLoaded = false;

    lottie.loadAnimation({
      container: this.lottieContainer,
      renderer: 'svg',
      loop: true,
      autoplay: true,
      path: this.animationDataUrl,
      rendererSettings: { preserveAspectRatio: 'xMidYMid slice' },
      name: this.containerId + "-Lottie"
    }).addEventListener('DOMLoaded', () => {
      this.lottieInstance = lottie.getAnimation(this.containerId + "-Lottie");
      this.lottieLoaded = true;
      this.lottieInstance.goToAndStop(0, true);
    });

    // Animate on scroll/pin
    const aboutSection = this.lottieContainer.closest("section");
    if (!aboutSection) {
      console.warn("Could not find section ancestor for pinning.");
      return;
    }

    gsap.set(this.lottieContainer, { opacity: 0, x: -40, scale: 0.8 });

    this.scrollTriggerInstance = ScrollTrigger.create({
      trigger: aboutSection,
      start: 'top center',
      end: '+=400',
      pin: true,
      anticipatePin: 1,
      scrub: 1.0,
      onEnter: () => {
        gsap.to(this.lottieContainer, {
          opacity: 1,
          x: 0,
          scale: 1,
          duration: 0.7,
          ease: "power3.out",
          onStart: () => {
            if (this.lottieLoaded && this.lottieInstance) this.lottieInstance.play();
          }
        });
      },
      onLeave: () => {
        gsap.to(this.lottieContainer, {
          opacity: 0,
          x: -50,
          scale: 0.7,
          duration: 0.5,
          ease: "power2.in",
          onStart: () => {
            if (this.lottieLoaded && this.lottieInstance) this.lottieInstance.stop();
          }
        });
      },
      onEnterBack: () => {
        gsap.to(this.lottieContainer, {
          opacity: 1,
          x: 0,
          scale: 1,
          duration: 0.65,
          ease: "power3.out",
          onStart: () => {
            if (this.lottieLoaded && this.lottieInstance) this.lottieInstance.play();
          }
        });
      },
      onLeaveBack: () => {
        gsap.to(this.lottieContainer, {
          opacity: 0,
          x: -45,
          scale: 0.7,
          duration: 0.45,
          ease: "power2.in",
          onStart: () => {
            if (this.lottieLoaded && this.lottieInstance) this.lottieInstance.stop();
          }
        });
      }
    });
  }
}

class FAQTickets {
  constructor({ ticketTrackSelector, ticketItemSelector, cardSelector }) {
    this.ticketTrackSelector = ticketTrackSelector;
    this.ticketItemSelector = ticketItemSelector;
    this.cardSelector = cardSelector;
  }

  init() {
    this.initTicket();
    this.initFAQCards();
  }

  initTicket() {
    const ticketTrack = document.querySelector(this.ticketTrackSelector);
    if (!ticketTrack) return;

    const items = Array.from(ticketTrack.children);
    items.forEach((item) => {
      ticketTrack.appendChild(item.cloneNode(true));
    });

    const trackWidth = ticketTrack.scrollWidth / 2;
    ticketTrack.style.width = `${ticketTrack.scrollWidth}px`;

    this.tickerTween = gsap.to(ticketTrack, {
      x: -trackWidth,
      duration: 16,
      ease: "linear",
      repeat: -1,
      onRepeat: () => {
        gsap.set(ticketTrack, { x: 0 });
      }
    });
  }

  initFAQCards() {
    const cards = document.querySelectorAll(this.cardSelector);
    if (!cards.length) return;
    gsap.set(cards, { opacity: 0, y: 40, scale: 0.98 });
    cards.forEach((card, i) => {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            gsap.to(card, {
              opacity: 1,
              y: 0,
              scale: 1,
              duration: 0.7,
              delay: i * 0.13,
              stagger: 2,
              ease: 'bounce.out'
            });
          } else {
            gsap.set(card, { opacity: 0, y: 40, scale: 0.98 });
          }
        });
      }, { threshold: 0.34 });
      observer.observe(card);
    });
  }
}


class ResourceSectionGSAPAnim {
  constructor(containerSelector) {
    // The section container (use the parent .max-w-3xl or assign a class/id)
    this.container = document.querySelector(containerSelector);
    this.faqCard = this.container?.querySelector('.faq-card');
    this.pdfBoxes = Array.from(this.container?.querySelectorAll('.mt-8.max-w-xl') || []);
  }

  init() {
    if (!window.gsap || !this.container) return;
    this.staggerIn();
    this.animatePdfListItems();
  }

  // Stagger the main card and the resource boxes in
  staggerIn() {
    const animTargets = [
      this.faqCard,
      ...this.pdfBoxes
    ].filter(Boolean);

    gsap.set(animTargets, { opacity: 0, y: 48, scale: 0.95 });
    gsap.to(animTargets, {
      opacity: 1,
      y: 0,
      scale: 1,
      duration: 0.75,
      stagger: 0.8,
      ease: "power3.out"
    });
  }

  // Animate the list items inside each PDF resource box
  animatePdfListItems() {
    this.pdfBoxes.forEach((box, boxIndex) => {
      // Wait for box to be visible, then animate its children
      const listItems = box.querySelectorAll('ul > li');
      gsap.set(listItems, { opacity: 0, y: 30 });

      // Intersection-trigger list animation after box appears
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            gsap.to(listItems, {
              opacity: 1,
              y: 0,
              duration: 0.8,
              stagger: 0.8,
              ease: "bounce.out",
            });
            observer.unobserve(box);
          }
        });
      }, { threshold: 0.4 });

      observer.observe(box);
    });
  }
}

/***  MAIN BOOTSTRAP *****/ 
document.addEventListener('DOMContentLoaded', () => {

  new LottieGsapPinAnimation({
    containerId: 'lottie-team',
    animationDataUrl: 'https://lottie.host/embed/95afefff-94d2-4ef5-8e49-430decdbd2d1/K4fTX35Fw3.lottie'
  }).init();
  // lottieAnimations.init();
  new HeaderNav().init();
  new SelectionCardsAnimation().init();
  new FeaturesAnimation().init();
  new CurriculumSectionAnimation().init();
  new StudentCardsAnimation().init();
  new AchieverCardsAnimation().init();
  new MentorCardsAnimation().init();
  new TeamTypewriterAnimation().init();
  new FounderImagesAnimation().init();
  new FAQTickets({
    ticketTrackSelector: '#faq-ticker-track',
    ticketItemSelector: '.faq-ticker-item',
    cardSelector: '.faq-card'
  }).init();
  new ResourceSectionGSAPAnim('.max-w-3xl').init();

});




