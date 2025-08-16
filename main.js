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

class NavbarAnimation {
  init() {
    const navLinks = document.querySelectorAll('header nav a, header button, header h1');
    if (!window.gsap || !navLinks.length) return;
    gsap.set(navLinks, { opacity: 0, y: -20 });
    gsap.to(navLinks, {
      opacity: 1,
      y: 0,
      duration: 0.7,
      stagger: 0.2,
      ease: "power2.out"
    });
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

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: "top center",
        end: "+=520",
        pin: true,
        scrub: 1.2,
        anticipatePin: 1,
      }
    });

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
  new NavbarAnimation().init();
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




