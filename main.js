let mainSwiper;

// All Swiper initialization now happens in DOMContentLoaded
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
        neet: "#panel-neet",
        jee: "#panel-jee",
        nursing: "#panel-nursing",
        anmgnm: "#panel-anmgnm"
      },

      // Behavior
      defaultCategory: "neet",
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
    this._clearCloseTimer();
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
  constructor() {
    this._weakmap = new WeakMap();
    this._onEnter = this._onEnter.bind(this);
    this._onLeave = this._onLeave.bind(this);

    this.cards = [];
  }
  init() {
    if (!window.gsap || !window.ScrollTrigger) return;
    gsap.registerPlugin(ScrollTrigger);

    const section = document.getElementById('features');
    if (!section) return;
    const cards = section.querySelectorAll('.glow-card');
    if (!cards) return;

    this._card = Array.from(cards);


    gsap.set(cards, { opacity: 0, y: 36 });

    const tl = gsap.timeline();

    tl.to(cards, {
      opacity: 1,
      y: 0,
      duration: 0.9,
      stagger: 0.4,
      ease: "power3.out"
    });

    //Attaching hover listener .for glow animation
    this._card.forEach((card) => {
      card.addEventListener('mouseenter', this._onEnter);
      card.addEventListener('mouseleave', this._onLeave);
    });

  }
  _onEnter(e) {
    const el = e.currentTarget;
    //Kill any animation running
    gsap.killTweensOf(el);

    const rgb = el.dataset?.glowColor || '99,102,241';

    gsap.to(el, {
      duration: 0.38,
      ease: 'power2.out',
      y: -4,
      scale: 1.015,
      boxShadow: `0 0 0 2px rgba(${rgb}, 0.25), 0 10px 28px rgba(${rgb}, 0.30), 0 0 56px rgba(${rgb}, 0.45)`
    })

    const tl = gsap.timeline({ repeat: -1, yoyo: true, defaults: { duration: 1.2, ease: 'sine.inOut' } });
    tl.to(el, {
      boxShadow: `0 0 0 2px rgba(${rgb}, 0.35), 0 12px 32px rgba(${rgb}, 0.38), 0 0 64px rgba(${rgb}, 0.60)`
    });

    this._weakmap.set(el, tl);
  }

  _onLeave(e) {
    const el = e.currentTarget;

    const tl = this._weakmap.get(el);
    if (tl) {
      tl.kill();
      this._weakmap.delete(el);
    }

    gsap.to(el, {
      duration: 0.28,
      ease: 'power2.inOut',
      y: 0,
      scale: 1,
      boxShadow: '0 8px 24px rgba(0,0,0,0.08)'
    });
  }

  destroy() {
    if (!this._card.length) return;

    this._card.forEach((el) => {
      el.removeEventListener('mouseenter', this._onEnter);
      el.removeEventListener('mouseleave', this._onLeave);

      // Kill any GSAP tweens on the element
      gsap.killTweensOf(el);

      // If a timeline exists in WeakMap, kill it
      const tl = this._weakmap.get(el);
      if (tl) {
        tl.kill();
        this._weakmap.delete(el);
      }
    });

    this._card = [];
  }
}


// function Achievers(){
//   const grid = document.getElementById('achievers-grid');
//   if (!grid) return;

//   fetch('php_backend/api/achievers.php')
//     .then(r => r.json())
//     .then(data => {
//       const achievers = Array.isArray(data) ? data : (data.achievers || []);
//       if (!achievers.length) {
//         grid.innerHTML = '<p class="text-gray-600">No achievers found.</p>';
//         return;
//       }
//       const html = achievers.map(a => {
//         const name = a.name || 'Achiever';
//         const rank = a.rank || 'Unknown';
//         const imgPath = a.image_path || a.image || '';
//         return `
//           <div class="achiever-card flex rounded-lg shadow-lg p-3 border border-purple-400">
//             <img src="${imgPath}" alt="${name}" class="w-24 h-24 object-cover rounded-full" onerror="this.src='https://via.placeholder.com/600x360?text=No+Image'">
//             <div class="text-center">
//               <p class="text-3xl pt-[50px] font-bold text-purple-700 mb-6">AIR<br /><span class="text-6xl">${rank}</span></p>
//               <p class="font-semibold text-gray-700">${name}<br /> <span>In unreserved category</span></p>
//             </div>
//           </div>`;
//       }).join('');
//       grid.innerHTML = html;
//     });
// }


// fetch('php_backend/api/candidate.php')
//           .then(r => r.json())
//           .then(data => {
//             const candidates = Array.isArray(data) ? data : (data.candidates || []);
//             if (!candidates.length) {
//               if (window.gsap) gsap.killTweensOf('.skeleton');
//               grid.innerHTML = '<p class="text-gray-600">No candidates found.</p>';
//               return;
//             }
//             const baseSeg = window.location.pathname.split('/')[1] || '';
//             const basePrefix = baseSeg ? ('/' + baseSeg + '/') : '/';
//             const html = candidates.map(c => {
//               const name = c.name || c.candidate_name || 'Candidate';
//               const imgPath = c.image_path || c.image || '';
//               const imgSrc = /^https?:/i.test(imgPath) ? imgPath : (basePrefix + String(imgPath || '').replace(/^\\+/, ''));
//               return `
//                 <div class="selection-card candidate-card opacity-0 translate-y-3 rounded-lg bg-gradient-to-r from-blue-300 to-purple-300 border border-purple-300 bg-white shadow-md hover:shadow-xl transition-shadow duration-200 overflow-hidden max-w-[450px] w-full mx-auto">
//                   <div class="overflow-hidden">
//                     <img src="${imgSrc}" alt="${name}"
//                       class="w-full h-56 sm:h-64 object-cover transform transition-transform duration-300 hover:scale-105"
//                       onerror="this.src='https://via.placeholder.com/600x360?text=No+Image'">
//                   </div>
//                   <div class="p-4 text-center">
//                     <p class="font-semibold text-gray-800 text-lg">${name}</p>
//                   </div>
//                 </div>`;
//             }).join('');
//             if (window.gsap) gsap.killTweensOf('.skeleton');
//             grid.innerHTML = html;
//             if (window.gsap) gsap.to('.candidate-card', {opacity:1, y:0, duration:0.5, stagger:0.08, ease:'power2.out'});




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
    const grid = document.getElementById('achievers-grid');
    if (!grid) return;

    fetch('php_backend/api/candidate.php')
      .then(r => r.json())
      .then(data => {
        let achievers = Array.isArray(data) ? data : (data.achievers || []);
        achievers = achievers.filter((a, idx, arr) =>
          a && a.name && idx === arr.findIndex(b => b.name === a.name)
        );
        if (!achievers.length) {
          grid.innerHTML = '<p class="text-gray-600">No achievers found.</p>';
          return;
        }
        const displayList = achievers.slice(0, 16);

        // Build slides (name overlaid on image bottom)
        const makeSlide = (a) => {
          const name = a.name || 'Achiever';
          const imgPath = a.image_path || a.image || '';
          return `
            <div class="swiper-slide achiever-card group relative mx-auto w-36 sm:w-40 md:w-48 lg:w-56 my-3 snap-center">
              <div class="relative w-full aspect-[4/5] rounded-2xl overflow-hidden shadow-xl ring-1 ring-purple-200/50 bg-white">
                <img src="${imgPath}" alt="${name}"
                  class="w-full h-full object-cover transition-all duration-400 group-hover:scale-105 glow-border"
                  onerror="this.src='https://via.placeholder.com/400x533?text=No+Image'">
                <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 via-black/30 to-transparent pointer-events-none"></div>
                <p class="absolute inset-x-0 bottom-1 sm:bottom-2 text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-white text-center drop-shadow-md px-2">${name}</p>
              </div>
            </div>
          `;
        };
        const slides = displayList.map(makeSlide).join('');

        // Swiper markup (single professional slider)
        const html = `
        <div class="swiper achiever-swiper">
          <div class="swiper-wrapper">
            ${slides}
          </div>
          <div class="swiper-pagination"></div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>`;
        grid.innerHTML = html;

        // Responsive container classes
        grid.className = 'w-full max-w-7xl mx-auto overflow-hidden relative';

        // Initialize Swiper with autoplay and responsive breakpoints
        if (window.Swiper) {
          new Swiper('.achiever-swiper', {
            loop: true,
            speed: 900,
            autoplay: {
              delay: 2000,
              disableOnInteraction: false,
              pauseOnMouseEnter: true
            },
            slidesPerView: 1,
            spaceBetween: 20,
            breakpoints: {
              640: { slidesPerView: 2, spaceBetween: 20 },
              768: { slidesPerView: 3, spaceBetween: 20 },
              1024: { slidesPerView: 4, spaceBetween: 24 }
            },
            pagination: { el: '.achiever-swiper .swiper-pagination', clickable: true },
            navigation: { nextEl: '.achiever-swiper .swiper-button-next', prevEl: '.achiever-swiper .swiper-button-prev' }
          });
        }

        // GSAP entry animation for cards
        if (window.gsap) {
          const cards = grid.querySelectorAll('.achiever-card');
          if (cards && cards.length) {
            gsap.from(cards, {
              opacity: 0,
              y: 16,
              duration: 0.6,
              stagger: 0.06,
              ease: 'power2.out',
              clearProps: 'transform,opacity'
            });
          }
        }
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
    this.message = `At THETA FORNIX, we don’t just prepare students for exams — we prepare them for life. With dedication, innovation,
     and personal care, we have built a space where every dream finds direction. Our exclusive collaboration with NEETPREP.COM, 
     Delhi gives our students an edge with national-level content, while our modern classrooms, doubt-solving, 
     and individual attention ensure no student is left behind. The trust of parents and the success of our students in Government Colleges inspire us every day. 
     We welcome you to join the THETA FORNIX family, where excellence is not an option — it’s a habit`;
  }
  init() {
    const target = document.getElementById('team-typewriter');
    if (!target || !window.gsap) return;
    target.style.minHeight = "70px";
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
              setTimeout(typeNextWord, 30 + Math.min(words[index - 1].length * 8, 55));
            } else {
              target.textContent = current;
            }
          }
          typeNextWord();
        }
      });
    }, { threshold: 0.27 });

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




class RecentBatchesGrid {
  constructor(targetId = 'recent-batches') {
    this.targetId = targetId;
    document.addEventListener('DOMContentLoaded', () => {
      this.init();
    });
  }

  async init() {
    try {
      const allCourses = await this.fetchCourses();
      console.log('allCourses', allCourses); // <-- ADD THIS LINE
      const chosen = this.pickRandomRelevant(allCourses, 4);
      this.renderBatches(chosen);
    } catch (err) {
      this.renderError(err);
    }
  }

  async fetchCourses() {
    const resp = await fetch('php_backend/api/courses.php');
    if (!resp.ok) throw new Error('Failed to fetch courses');
    return await resp.json();
  }

  pickRandomRelevant(allCourses, count = 4) {
  // Get only COHORT batches of (JEE + 12) or (NEET + 11)
  const filtered = allCourses.filter(c => {
    const title = (c.title || '').toLowerCase();
    return title.includes('cohort');
  });
  // Shuffle
  for (let i = filtered.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [filtered[i], filtered[j]] = [filtered[j], filtered[i]];
  }
  return filtered.slice(0, count);
}

  renderBatches(courses) {
    const el = document.getElementById(this.targetId);
    if (!el) return;
    el.innerHTML = courses.map(course => `
      <div class="bg-white w-80 shadow rounded-lg p-4 flex flex-col border border-amber-700 transition-transform duration-200 ease-in-out hover:shadow-2xl hover:scale-105">
        <img src="${course.batch_image_path || 'fallback.jpg'}" alt="${course.title || ''}" class="w-96 h-full object-cover rounded-md mb-4">
        <h3 class="text-xl font-bold mb-2 ml-2">${course.title || ''}</h3>
        <ul class="mb-6 text-black space-y-1 pl-5 list-disc text-sm">
          ${course.description
        ? course.description.split('\n').map(line => `<li>${line}</li>`).join('')
        : '<li>No details available.</li>'}
        </ul>
        <div class="mt-auto flex justify-between items-center pt-3">
          <div>
            <span class="text-xl font-semibold text-green-800 mr-2">₹${course.price || 0}</span>
            ${course.original_price
        ? `<span class="text-gray-600 line-through text-lg">₹${course.original_price}</span>`
        : ''}
          </div>
          <a href="https://wa.me/919564787621" target="_blank" rel="noopener"
            class="inline-block bg-green-700 hover:bg-green-600 text-white font-bold py-2 px-5 rounded-3xl transition duration-200">
            Enquire
          </a>
        </div>
      </div>
    `).join('');
  }

  renderError(err) {
    const el = document.getElementById(this.targetId);
    if (!el) return;
    el.innerHTML = `<p class="text-red-700">Failed to load recent batches. Please try again later.</p>`;
    // Optionally log error
    // console.error(err);
  }
}

new RecentBatchesGrid('recent-batches');


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


// class ScrollHybridCardGSAP {
//   init() {
//     if (!window.gsap || !window.IntersectionObserver) return;
//     const hybridCard = document.getElementById('hybrid-card');
//     const hybridVideo = document.getElementById('hybrid-video');
//     const hybridText = document.getElementById('hybrid-text');
//     if (!hybridCard || !hybridVideo || !hybridText) return;

//     let hasAnimated = false;
//     const observer = new IntersectionObserver((entries, obs) => {
//       entries.forEach(entry => {
//         if (entry.isIntersecting && !hasAnimated) {
//           hasAnimated = true;

//           // Reveal card container instantly
//           hybridCard.classList.remove('opacity-0', 'pointer-events-none');
//           gsap.set([hybridVideo, hybridText], { opacity: 0 });
//           gsap.set(hybridVideo, { x: -120 });
//           gsap.set(hybridText, { x: 120 });

//           // Animate video and text
//           gsap.to(hybridVideo, { opacity: 1, x: 0, duration: 1, ease: "power3.out" });
//           gsap.to(hybridText, { opacity: 1, x: 0, duration: 1, delay: 0.15, ease: "power3.out" });

//           // Autoplay video if possible when revealed
//           if (hybridVideo.paused) {
//             try { hybridVideo.play(); } catch (e) { /* Ignore */ }
//           }
//           obs.unobserve(entry.target);
//         }
//       });
//     }, { threshold: 0.37 });
//     observer.observe(hybridCard);
//   }
// }

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


document.addEventListener('DOMContentLoaded', function () {
  var waContactForm = document.getElementById('wa-contact-form');
  if (waContactForm) {
    waContactForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const name = document.getElementById('wa-name').value.trim();
      const track = document.getElementById('wa-track').value.trim();
      const level = document.getElementById('wa-level').value.trim();
      const address = document.getElementById('wa-address').value.trim();
      const contact = document.getElementById('wa-contact').value.trim();
      const query = document.getElementById('wa-query').value.trim();
      const founderNumber = '919999999999'; // With country code, no plus or spaces
      const msg = `Name: ${name}\nCourse: ${track} (${level})\nAddress: ${address}\nContact: ${contact}\nQuery: ${query}`;
      const url = `https://wa.me/${founderNumber}?text=${encodeURIComponent(msg)}`;
      window.open(url, '_blank');
    });
  }
});

    class MentorshipSlider {
      constructor() {
        this.swiper = new Swiper('.mentorship-slider', {
          loop: true,
          autoplay: {
            delay: 3000,
            disableOnInteraction: false,
          },
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
        });
      }
    }


  class OfferNotificationPopup {
    constructor() {
      this.offerBubble = document.getElementById('earlybird-popup');
      this.offerFormPopup = document.getElementById('earlybird-form-popup');
      this.offerFormClose = document.getElementById('earlybird-form-close');
      this.offerForm = document.getElementById('earlybird-wa-form');
      this.showTimeout = null;
      this.reopenInterval = null;
      this.isPopupVisible = false; // Register form popup state
      this.visibleMs = 20000; // Show 3s
      this.intervalMs = 60000; // Repeat every 30s
      this.autoTimeout = null;
      this.reopenTimeout = null;
      this.setup();
    }
    setup() {
      // Always make bubble visible
      if (this.offerBubble) {
        this.offerBubble.classList.remove('opacity-0', 'pointer-events-none', 'hidden');
      }
      this.addListeners();
      this._autoShowPopupInitial();
    }
    addListeners() {
      // Notification bubble click opens popup
      if (this.offerBubble && this.offerFormPopup) {
        this.offerBubble.addEventListener('click', () => {
          this._showPopupManually();
        });
      }
      // Register popup close button (cross)
      if (this.offerFormClose) {
        this.offerFormClose.addEventListener('click', () => this._closePopupAndReschedule());
      }
      // WhatsApp form submit
      if (this.offerForm) {
        this.offerForm.addEventListener('submit', (e) => {
          e.preventDefault();
          const name = document.getElementById('earlybird-wa-name').value.trim();
          const track = document.getElementById('earlybird-wa-track').value.trim();
          const level = document.getElementById('earlybird-wa-level').value.trim();
          const address = document.getElementById('earlybird-wa-address').value.trim();
          const contact = document.getElementById('earlybird-wa-contact').value.trim();
          const query = document.getElementById('earlybird-wa-query').value.trim();
          const founderNumber = '919999999999';
          const msg = `Name: ${name}\nCourse: ${track} (${level})\nAddress: ${address}\nContact: ${contact}\nQuery: ${query}`;
          const url = `https://wa.me/${founderNumber}?text=${encodeURIComponent(msg)}`;
          window.open(url, '_blank');
          this._closePopupAndReschedule();
        });
      }
    }
    // Auto open on page load, then auto cycles every 30s
    _autoShowPopupInitial() {
      this._showPopup(true);
    }

    _scheduleNextAutoPopup() {
      if (this.reopenTimeout) clearTimeout(this.reopenTimeout);
      this.reopenTimeout = setTimeout(() => {
        this._showPopup(true);
      }, this.intervalMs);
    }
    _showPopup(isAuto = false) {
      if (!this.offerFormPopup) return;
      // Don't pop up if already visible
      if (this.isPopupVisible) return;
      // Show popup
      this.isPopupVisible = true;
      this.offerFormPopup.classList.remove('hidden');
      this.offerFormPopup.classList.add('animate-fadeInUp');
      // Hide after 3s unless closed early
      if (this.autoTimeout) clearTimeout(this.autoTimeout);
      this.autoTimeout = setTimeout(() => {
        this._closePopupAndReschedule();
      }, this.visibleMs);
    }
    // Manual show = show popup, then reschedule auto popup cycle
    _showPopupManually() {
      if (this.isPopupVisible || !this.offerFormPopup) return;
      this._showPopup(false);
      this._scheduleNextAutoPopup();
    }
    // Hide the popup and start 30s interval for next auto open
    _closePopupAndReschedule() {
      if (!this.offerFormPopup) return;
      if (this.autoTimeout) clearTimeout(this.autoTimeout);
      this.offerFormPopup.classList.add('hidden');
      this.offerFormPopup.classList.remove('animate-fadeInUp');
      this.isPopupVisible = false;
      this._scheduleNextAutoPopup();
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
  new MentorshipSlider();
  new ResourceSectionGSAPAnim('.max-w-3xl').init();
  // new ScrollHybridCardGSAP().init();
  new OfferNotificationPopup(); 

  // Registration Modal Popup Logic (legacy popup, keep hidden but support old code)   


  // Offer Notification/Popup Toggling as a Class

});




