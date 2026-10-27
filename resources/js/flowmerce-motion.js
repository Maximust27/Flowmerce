/**
 * Flowmerce Studio | Motion system
 *
 * GSAP is used for small, intentional changes of opacity and transform only.
 * No perpetual loops, no layout/height tweens, and no transforms on page
 * shells or modal ancestors (which would clip full-viewport backdrops).
 *
 * One scene is active per Livewire navigation. gsap.context().revert()
 * restores inline styles before the next page is mounted.
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const hoverCapable = window.matchMedia('(hover: hover) and (pointer: fine)');

let sceneRoot = null;
let sceneContext = null;
let pendingFrame = 0;
let sceneCleanups = [];
let livewireHooksRegistered = false;

const animatedNodes = new WeakSet();

function find(selector) {
    return sceneRoot ? Array.from(sceneRoot.querySelectorAll(selector)) : [];
}

function eligible(elements) {
    return Array.from(elements).filter(element =>
        element.isConnected && element.getClientRects().length > 0 &&
        !element.closest('[hidden], [x-cloak]') &&
        !element.querySelector?.('.fm-modal, .fm-command')
    );
}

function isEnabled() {
    return !!sceneContext && !reducedMotion.matches && sceneRoot?.isConnected;
}

/**
 * Each reveal restores transform/opacity at completion so hover effects and
 * position:fixed descendants are never trapped in a transformed ancestor.
 */
function reveal(nodes, options = {}) {
    const elements = eligible(nodes);
    if (!elements.length) return null;

    const {
        distance = 18,
        duration = 0.56,
        stagger = 0.065,
        delay = 0,
        start = null,
        trigger = elements[0],
        subtle = false,
    } = options;

    // Internal pages stay almost fully visible at all times. Moving from a
    // completely transparent state after hydration caused the visible flash.
    const initialOpacity = subtle ? 0.975 : 0;
    const initialDistance = subtle ? Math.min(distance, 7) : distance;

    return gsap.fromTo(elements,
        { opacity: initialOpacity, y: initialDistance },
        {
            opacity: 1,
            y: 0,
            duration,
            delay,
            stagger,
            ease: 'power3.out',
            // Wait for the first scheduled render on subtle UI transitions;
            // never hide existing text or controls during Livewire hydration.
            immediateRender: !subtle,
            clearProps: 'opacity,transform',
            overwrite: 'auto',
            ...(start ? {
                scrollTrigger: {
                    trigger,
                    start,
                    once: true,
                    invalidateOnRefresh: true,
                },
            } : {}),
        }
    );
}

/** Same understated motion language for every authenticated module. */
function softEntrance(selector, delay = 0, stagger = 0.042) {
    return reveal(find(selector), {
        subtle: true,
        distance: 7,
        duration: 0.42,
        stagger,
        delay,
    });
}

function drawRevenueChart(path) {
    if (!path || !path.isConnected || animatedNodes.has(path)) return;
    animatedNodes.add(path);
    path.dataset.fmMotionLastCurve = path.getAttribute('d') || '';

    gsap.fromTo(path, { strokeDashoffset: 16 }, {
        strokeDashoffset: 0,
        duration: 0.85,
        immediateRender: false,
        delay: 0.16,
        ease: 'power2.inOut',
        clearProps: 'strokeDashoffset',
        overwrite: 'auto',
    });
}

function mountWorkspace() {
    // Dashboard, Inventaris, Gudang, Keuangan, Pegawai and Meja use the same
    // light-touch choreography. No nested container + child opacity tweening.
    softEntrance('.fm-page > .fm-page-header, .fm-dashboard > .fm-page-header', 0);
    softEntrance(
        '.fm-page > .fm-kpi-grid > .fm-stat-card, ' +
        '.fm-page > .fm-kpi-grid > .fm-card, ' +
        '.fm-dashboard > .fm-overview-grid > .fm-card, ' +
        '.fm-dashboard > .fm-overview-grid > .fm-overview__metrics',
        0.035
    );
    softEntrance(
        '.fm-page > .fm-section-header, .fm-page > .fm-list-toolbar, ' +
        '.fm-page > .fm-table-card, .fm-page > .fm-timeline, ' +
        '.fm-page > .fm-card, .fm-page > .fm-account__identity, ' +
        '.fm-page > .fm-account__layout, .fm-page > .fm-tables__notice, ' +
        '.fm-page > .fm-surface-panel, .fm-dashboard > .fm-insights-grid > .fm-card, ' +
        '.fm-dashboard > .fm-studio-shortcuts',
        0.085
    );
    // Cards are animated individually only where the parent has not been
    // animated, avoiding a nested "fade twice" that used to look like blinking.
    softEntrance('.fm-tables__grid > .fm-tables__card', 0.12, 0.035);
    find('.fm-lineplot__curve').forEach(drawRevenueChart);
}

/**
 * Ambient animations run only while their section is visible. Browser-tab
 * backgrounding and reduced-motion settings pause every perpetual timeline.
 */
function watchAmbient(section, loops) {
    if (!section || !loops.length) return;

    let inView = false;
    const sync = () => {
        const active = inView && !document.hidden && !reducedMotion.matches;
        loops.forEach(loop => active ? loop.play() : loop.pause());
    };

    const watcher = ScrollTrigger.create({
        trigger: section,
        start: 'top bottom',
        end: 'bottom top',
        onToggle: self => {
            inView = self.isActive;
            sync();
        },
    });

    inView = watcher.isActive;
    document.addEventListener('visibilitychange', sync);
    sceneCleanups.push(() => document.removeEventListener('visibilitychange', sync));
    sync();
}

function floatHeroDecor() {
    const hero = sceneRoot.querySelector('[data-fm-hero-ambient]');
    if (!hero) return;

    const square = hero.querySelector('[data-fm-float="square"]');
    const circle = hero.querySelector('[data-fm-float="circle"]');
    const halo = hero.querySelector('.fm-public-hero__halo');
    const loops = [];

    if (square) loops.push(gsap.to(square, {
        y: -14, x: 7, rotation: 24, scale: 1.04,
        duration: 3.9, ease: 'sine.inOut', repeat: -1, yoyo: true, paused: true,
    }));

    if (circle) loops.push(gsap.to(circle, {
        y: 15, x: -9, scale: 1.12,
        duration: 4.8, ease: 'sine.inOut', repeat: -1, yoyo: true, paused: true,
    }));

    if (halo) loops.push(gsap.to(halo, {
        rotation: 360, duration: 78,
        ease: 'none', repeat: -1, paused: true,
    }));

    watchAmbient(hero, loops);
}

/** Rotate the orbital path and counter-rotate the icons by the same amount. */
function orbitTimeline(track, duration, direction) {
    const satellites = track.querySelectorAll('[data-fm-orbit-satellite]');
    const degrees = direction === 'clockwise' ? 360 : -360;

    return gsap.timeline({ paused: true, repeat: -1 })
        .to(track, {
            rotation: degrees,
            duration,
            ease: 'none',
            transformOrigin: '50% 50%',
        }, 0)
        .to(satellites, {
            rotation: -degrees,
            duration,
            ease: 'none',
            transformOrigin: '50% 50%',
        }, 0);
}

function animateFeatureOrbit() {
    const orbit = sceneRoot.querySelector('[data-fm-orbit]');
    if (!orbit) return;

    const outer = orbit.querySelector('[data-fm-orbit-track="outer"]');
    const inner = orbit.querySelector('[data-fm-orbit-track="inner"]');
    const ring = orbit.querySelector('.fm-orbit__core-ring');
    const loops = [];

    if (outer) loops.push(orbitTimeline(outer, 32, 'clockwise'));
    if (inner) loops.push(orbitTimeline(inner, 43, 'counterclockwise'));

    if (ring) loops.push(gsap.to(ring, {
        scale: 1.055,
        duration: 3.6,
        yoyo: true,
        repeat: -1,
        ease: 'sine.inOut',
        paused: true,
    }));

    watchAmbient(orbit, loops);
}

function mountPublic() {
    // Landing is more expressive than the operational dashboard, but content
    // remains readable even before JS mounts.
    reveal(find('.fm-public-hero__copy > *'), {
        subtle: true, distance: 12, duration: 0.72, stagger: 0.095,
    });
    reveal(find('.fm-public-hero__visual > .fm-product-preview'), {
        subtle: true, distance: 13, duration: 0.85, delay: 0.14,
    });

    floatHeroDecor();

    const scrollGroups = [
        ['.fm-public-strip__inner > *', '.fm-public-strip'],
        ['.fm-public-section__head > *', '.fm-public-section'],
        ['.fm-public-features > .fm-public-feature', '.fm-public-features'],
        ['.fm-public-showcase__content > *', '.fm-public-showcase'],
        ['.fm-public-showcase__orbit', '.fm-public-showcase'],
        ['.fm-public-cta > *', '.fm-public-cta'],
    ];

    for (const [selector, triggerSelector] of scrollGroups) {
        const trigger = sceneRoot.querySelector(triggerSelector);
        if (!trigger) continue;

        reveal(find(selector), {
            start: 'top 87%',
            trigger,
            distance: 19,
            duration: 0.69,
            stagger: 0.085,
        });
    }

    // Feature chart bars grow once when their illustrated card is visible.
    const illustration = sceneRoot.querySelector('.fm-public-feature__illustration');
    if (illustration) {
        gsap.fromTo(illustration.querySelectorAll('span'), {
            scaleY: 0.36, opacity: 0.55,
            transformOrigin: 'bottom center',
        }, {
            scaleY: 1, opacity: 1,
            duration: 0.82,
            stagger: 0.1,
            ease: 'power3.out',
            clearProps: 'transform,opacity',
            scrollTrigger: {
                trigger: illustration,
                start: 'top 92%',
                once: true,
            },
        });
    }

    animateFeatureOrbit();
}

function mountAuth() {
    // Never transform the auth panel itself; it can contain native focus flows.
    reveal(find('.fm-auth-aside__eyebrow, .fm-auth-aside__headline, .fm-auth-aside__copy'), {
        distance: 17, stagger: 0.12, duration: 0.7,
    });
    reveal(find('.fm-auth-aside__center > .fm-product-preview'), {
        distance: 12, duration: 0.75, delay: 0.28,
    });
    reveal(find('.fm-auth-panel__context, .fm-auth-heading, .fm-auth-form > *, .fm-auth-flow__switch'), {
        distance: 12, stagger: 0.065, delay: 0.05, duration: 0.52,
    });
}

function mountPos() {
    reveal(find('.fm-pos__intro, .fm-pos__tabs'), { distance: 14, stagger: 0.09 });
    reveal(find('.fm-pos__grid > .fm-product').slice(0, 18), {
        distance: 14, stagger: 0.035, delay: 0.12, duration: 0.49,
    });
    reveal(find('.fm-pos__online-heading, .fm-pos__lookup, .fm-pos__online-order'), {
        distance: 13, stagger: 0.065,
    });
    // Only fade the fixed/scrolling summary. Transforming it clips its drawer.
    gsap.fromTo(find('.fm-pos__summary'), { opacity: 0 }, {
        opacity: 1, duration: 0.56, ease: 'power2.out',
        clearProps: 'opacity',
    });
}

function mountGuest() {
    reveal(find('.fm-guest__intro > *, .fm-guest__search, .fm-guest__categories'), {
        distance: 14, stagger: 0.07,
    });
    reveal(find('.fm-guest__products > .fm-guest__product').slice(0, 18), {
        distance: 16, duration: 0.51, delay: 0.09, stagger: 0.036,
    });
    // Keep the checkout in normal document flow; animate only header opacity.
    gsap.fromTo(find('.fm-guest__checkout-head'), { opacity: 0 }, {
        opacity: 1, duration: 0.42, delay: 0.16,
        ease: 'power2.out', clearProps: 'opacity',
    });
}

function animateDialog(dialog) {
    if (!dialog || !sceneRoot?.contains(dialog) || animatedNodes.has(dialog)) return;

    const panel = dialog.querySelector('.fm-modal__panel');
    if (!panel) return;
    animatedNodes.add(dialog);

    sceneContext.add(() => {
        const scrim = dialog.querySelector('.fm-modal__scrim');
        if (scrim) {
            gsap.fromTo(scrim, { opacity: 0 }, {
                opacity: 1, duration: 0.22, ease: 'power2.out',
                clearProps: 'opacity',
            });
        }
        gsap.fromTo(panel, { autoAlpha: 0, y: 18, scale: 0.977 }, {
            autoAlpha: 1, y: 0, scale: 1, duration: 0.38,
            ease: 'back.out(1.35)',
            clearProps: 'opacity,visibility,transform',
        });
    });
}

function animateNewNode(element) {
    if (!isEnabled() || !element || !sceneRoot.contains(element)) return;

    const candidateDialogs = element.matches?.('.fm-modal')
        ? [element]
        : Array.from(element.querySelectorAll?.('.fm-modal') ?? []);

    candidateDialogs.forEach(animateDialog);

    const classes = [
        '.fm-chat__message',
        '.fm-pos__basket-row',
        '.fm-activity-row',
        '.fm-tables__card',
        '.fm-pos__online-order',
    ];

    if (element.matches?.(classes.join(',')) && !animatedNodes.has(element)) {
        animatedNodes.add(element);
        sceneContext.add(() => reveal([element], {
            distance: 7, duration: 0.36, subtle: true,
        }));
    }

    const curve = element.matches?.('.fm-lineplot__curve')
        ? element
        : element.querySelector?.('.fm-lineplot__curve');

    if (curve && !animatedNodes.has(curve)) {
        sceneContext.add(() => drawRevenueChart(curve));
    }
}

function initHoverEffects() {
    if (!hoverCapable.matches) return;

    const selector = [
        '.fm-studio-shortcuts__links > a',
        '.fm-public-feature',
        '.fm-tables__card',
        '.fm-guest__product',
        '.fm-product',
    ].join(',');

    const findCard = (target) => target instanceof Element
        ? target.closest(selector) : null;

    const enter = event => {
        const card = findCard(event.target);
        if (!card || !sceneRoot.contains(card) || card.contains(event.relatedTarget)) return;

        sceneContext.add(() => {
            gsap.to(card, {
                y: -4,
                scale: 1.006,
                duration: 0.24,
                ease: 'power2.out',
                overwrite: 'auto',
            });
            const icon = card.querySelector('.fm-public-feature__icon');
            if (icon) gsap.to(icon, {
                y: -3, rotation: -5, duration: 0.3,
                ease: 'power2.out', overwrite: 'auto',
            });
        });
    };
    const leave = event => {
        const card = findCard(event.target);
        if (!card || !sceneRoot.contains(card) || card.contains(event.relatedTarget)) return;

        sceneContext.add(() => {
            gsap.to(card, {
                y: 0,
                scale: 1,
                duration: 0.27,
                ease: 'power2.out',
                overwrite: 'auto',
                clearProps: 'transform',
            });
            const icon = card.querySelector('.fm-public-feature__icon');
            if (icon) gsap.to(icon, {
                y: 0, rotation: 0, duration: 0.33,
                ease: 'power2.out', overwrite: 'auto',
                clearProps: 'transform',
            });
        });
    };

    sceneRoot.addEventListener('pointerover', enter, { passive: true });
    sceneRoot.addEventListener('pointerout', leave, { passive: true });
    sceneCleanups.push(() => {
        sceneRoot?.removeEventListener('pointerover', enter);
        sceneRoot?.removeEventListener('pointerout', leave);
    });
}

function destroyScene() {
    if (pendingFrame) cancelAnimationFrame(pendingFrame);
    pendingFrame = 0;
    sceneCleanups.forEach(cleanup => cleanup());
    sceneCleanups = [];
    if (sceneContext) sceneContext.revert();
    sceneContext = null;
    sceneRoot = null;
    document.documentElement.classList.remove('fm-gsap-enabled');
}

function mountScene() {
    const nextRoot = document.querySelector('[data-fm-motion-root]');
    if (sceneRoot === nextRoot && sceneContext) return;
    destroyScene();

    if (!nextRoot || reducedMotion.matches) return;

    sceneRoot = nextRoot;
    document.documentElement.classList.add('fm-gsap-enabled');

    sceneContext = gsap.context(() => {
        switch (nextRoot.dataset.fmMotionRoot) {
            case 'app': mountWorkspace(); break;
            case 'public': mountPublic(); break;
            case 'auth': mountAuth(); break;
            case 'pos': mountPos(); break;
            case 'guest': mountGuest(); break;
        }
    }, sceneRoot);

    initHoverEffects();
    // These modals may exist on the first paint, or be added later by Livewire.
    find('.fm-modal').forEach(animateDialog);
    ScrollTrigger.refresh();
}

function scheduleMount() {
    if (pendingFrame) cancelAnimationFrame(pendingFrame);
    pendingFrame = requestAnimationFrame(() => {
        pendingFrame = 0;
        mountScene();
    });
}

function registerLivewireHooks() {
    if (!window.Livewire || livewireHooksRegistered) return;
    livewireHooksRegistered = true;

    window.Livewire.hook('morph.added', ({ el }) => animateNewNode(el));
    // A chart period filter updates the existing path via DOM morphing; it does
    // not necessarily create a new element, so replay only when data changed.
    window.Livewire.hook('morph.updated', ({ el }) => {
        if (!isEnabled() || !el.matches?.('.fm-lineplot__curve')) return;
        const nextPath = el.getAttribute('d') || '';
        if (el.dataset.fmMotionLastCurve === nextPath) return;
        el.dataset.fmMotionLastCurve = nextPath;
        sceneContext.add(() => {
            gsap.fromTo(el, { strokeDashoffset: 16 }, {
                strokeDashoffset: 0,
                duration: 0.72,
                immediateRender: false,
                ease: 'power2.inOut',
                overwrite: 'auto',
                clearProps: 'strokeDashoffset',
            });
        });
    });
}

// Global listeners are registered once. Livewire's wire:navigate does not
// unbind document event listeners and DOMContentLoaded is not fired again.
document.addEventListener('livewire:init', registerLivewireHooks, { once: true });
if (window.Livewire) registerLivewireHooks();

document.addEventListener('livewire:navigating', destroyScene);
document.addEventListener('livewire:navigated', scheduleMount);
document.addEventListener('DOMContentLoaded', scheduleMount, { once: true });
if (document.readyState !== 'loading') scheduleMount();

reducedMotion.addEventListener?.('change', () => {
    destroyScene();
    scheduleMount();
});

document.addEventListener('flowmerce:command-open', () => {
    if (!isEnabled()) return;

    requestAnimationFrame(() => {
        const dialog = sceneRoot.querySelector('.fm-command__dialog');
        if (!dialog || !dialog.getClientRects().length) return;

        sceneContext.add(() => {
            gsap.fromTo(dialog, { autoAlpha: 0, y: 14, scale: 0.987 }, {
                autoAlpha: 1, y: 0, scale: 1, duration: 0.32,
                ease: 'power3.out',
                overwrite: 'auto',
                clearProps: 'opacity,visibility,transform',
            });
        });
    });
});

document.addEventListener('flowmerce:cart-added', event => {
    if (!isEnabled() || event.target !== sceneRoot) return;

    const id = Number(event.detail?.productId);
    const row = Array.from(sceneRoot.querySelectorAll('.fm-guest__cart-row'))
        .find(item => Number(item.dataset.cartProductId) === id);
    const badge = sceneRoot.querySelector('[data-cart-count]');

    sceneContext.add(() => {
        if (row) reveal([row], { distance: 8, duration: 0.36 });
        if (badge) {
            gsap.fromTo(badge, { scale: 1.28 }, {
                scale: 1, duration: 0.3, ease: 'back.out(2.3)',
                clearProps: 'transform',
            });
        }
    });
});

document.addEventListener('flowmerce:qris-revealed', event => {
    if (!isEnabled() || event.target !== sceneRoot) return;

    const preview = sceneRoot.querySelector('[data-qris-preview]');
    if (!preview || preview.hidden) return;

    sceneContext.add(() => {
        reveal(Array.from(preview.children), {
            distance: 9, stagger: 0.07, duration: 0.44,
        });
    });
});

document.addEventListener('flowmerce:order-confirmed', event => {
    if (!isEnabled() || event.target !== sceneRoot) return;

    const card = sceneRoot.querySelector('.fm-guest__confirmation-card');
    if (!card || !card.getClientRects().length) return;

    sceneContext.add(() => {
        gsap.fromTo(card, { autoAlpha: 0, y: 20, scale: 0.975 }, {
            autoAlpha: 1, y: 0, scale: 1, duration: 0.47,
            ease: 'back.out(1.45)',
            clearProps: 'opacity,visibility,transform',
        });
    });
});

document.addEventListener('click', event => {
    if (!isEnabled() || !(event.target instanceof Element)) return;
    const toggle = event.target.closest('[data-fm-theme-toggle]');
    if (!toggle) return;

    sceneContext.add(() => {
        gsap.fromTo(toggle, { scale: 0.9 }, {
            scale: 1, duration: 0.35,
            ease: 'back.out(2.1)',
            clearProps: 'transform',
        });
    });
}, { passive: true });
