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

/**
 * Motion must never hide or re-hide business data. Older fromTo(0.975 opacity)
 * was rendered late (immediateRender:false), which made content twitch when
 * the scheduled tween started. Only animate the compositor's translateY.
 * Position is applied up-front, then removed once settled.
 */
function softEntrance(selector, delay = 0, stagger = 0.065, options = {}) {
    const nodes = Array.isArray(selector) ? selector : find(selector);
    const elements = eligible(nodes).filter(node => !animatedNodes.has(node));
    if (!elements.length) return null;
    elements.forEach(node => animatedNodes.add(node));

    const {
        distance = 8,
        duration = 0.66,
        start = null,
        trigger = elements[0],
    } = options;

    return gsap.fromTo(elements,
        { y: distance },
        {
            y: 0,
            duration,
            stagger,
            delay,
            immediateRender: true,
            ease: 'power2.out',
            overwrite: 'auto',
            clearProps: 'transform',
            ...(start ? {
                scrollTrigger: {
                    trigger,
                    start,
                    once: true,
                },
            } : {}),
        }
    );
}

function drawRevenueChart(path) {
    if (!path || !path.isConnected || animatedNodes.has(path)) return;
    animatedNodes.add(path);
    path.dataset.fmMotionLastCurve = path.getAttribute('d') || '';

    // Keep the real chart visible; finish only the last tiny segment.
    gsap.fromTo(path, { strokeDashoffset: 5 }, {
        strokeDashoffset: 0,
        duration: 0.78,
        delay: 0,
        immediateRender: true,
        ease: 'power2.out',
        clearProps: 'strokeDashoffset',
        overwrite: 'auto',
    });
}

function mountWorkspace() {
    // A single choreography for operational UI: no opacity changes and no
    // animation of tables, stock logs, form fields or entire page containers.
    softEntrance('.fm-page > .fm-page-header, .fm-dashboard > .fm-page-header', 0, 0, {
        distance: 7, duration: 0.58,
    });

    if (sceneRoot.querySelector('.fm-dashboard')) {
        // Restore the dashboard's calm bento sequencing without the old flash.
        softEntrance(
            '.fm-dashboard > .fm-overview-grid > .fm-card, ' +
            '.fm-dashboard > .fm-overview-grid > .fm-overview__metrics',
            0.045, 0.082, { distance: 10, duration: 0.72 }
        );
        softEntrance(
            '.fm-dashboard > .fm-insights-grid > .fm-card',
            0.125, 0.075, { distance: 8, duration: 0.66 }
        );
        softEntrance(
            '.fm-dashboard > .fm-studio-shortcuts',
            0.2, 0, { distance: 6, duration: 0.64 }
        );
        find('.fm-lineplot__curve').forEach(drawRevenueChart);
        return;
    }

    // Inventory, warehouse, finance, employee and table management all share
    // an identical, understated card reveal. Lists remain completely stable.
    softEntrance(
        '.fm-page > .fm-kpi-grid > .fm-stat-card, ' +
        '.fm-page > .fm-kpi-grid > .fm-card',
        0.045, 0.065, { distance: 7, duration: 0.65 }
    );
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
    // Keep the already-approved floating square, circle and feature orbit.
    // On the welcome page, a scrolling card never fades out or vanishes.
    reveal(find('.fm-public-hero__copy > *'), {
        subtle: true, distance: 12, duration: 0.72, stagger: 0.095,
    });
    reveal(find('.fm-public-hero__visual > .fm-product-preview'), {
        subtle: true, distance: 13, duration: 0.85, delay: 0.14,
    });

    floatHeroDecor();

    // Headlines get a gentle 8px lift once; their opacity stays 100%.
    // Never animate the card container or the orbit itself on scroll.
    const scrollGroups = [
        ['.fm-public-section__head > *', '.fm-public-section'],
        ['.fm-public-showcase__content > *', '.fm-public-showcase'],
        ['.fm-public-cta > *', '.fm-public-cta'],
    ];

    for (const [selector, triggerSelector] of scrollGroups) {
        const trigger = sceneRoot.querySelector(triggerSelector);
        if (!trigger) continue;

        softEntrance(selector, 0, 0.065, {
            start: 'top 89%',
            trigger,
            distance: 8,
            duration: 0.74,
        });
    }

    // "Stok yang lebih terkendali" and its neighboring feature cards remain
    // fully rendered throughout the scroll. Only decorative ICONS move.
    const featureGrid = sceneRoot.querySelector('.fm-public-features');
    if (featureGrid) {
        softEntrance('.fm-public-features > .fm-public-feature .fm-public-feature__icon',
            0, 0.07, {
                start: 'top 88%',
                trigger: featureGrid,
                distance: 7,
                duration: 0.75,
            }
        );
    }

    // The feature-card illustration grows gently from an almost full height;
    // never fade the bars or the card's content from zero.
    const illustration = sceneRoot.querySelector('.fm-public-feature__illustration');
    if (illustration) {
        gsap.fromTo(illustration.querySelectorAll('span'), {
            scaleY: 0.93,
            transformOrigin: 'bottom center',
        }, {
            scaleY: 1,
            duration: 0.84,
            stagger: 0.09,
            ease: 'power2.out',
            clearProps: 'transform',
            scrollTrigger: {
                trigger: featureGrid ?? illustration,
                start: 'top 88%',
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
    // POS feedback stays responsive; catalog and totals never fade to zero.
    softEntrance('.fm-pos__intro, .fm-pos__tabs', 0, 0.06, {
        distance: 7, duration: 0.6,
    });
    softEntrance(find('.fm-pos__grid > .fm-product').slice(0, 14), 0.04, 0.037, {
        distance: 6, duration: 0.6,
    });
    softEntrance(find('.fm-pos__online-heading, .fm-pos__lookup, .fm-pos__online-order').slice(0, 14),
        0.06, 0.055, { distance: 6, duration: 0.62 }
    );
    // The fixed payment summary must never move or flash.
}

function mountGuest() {
    softEntrance('.fm-guest__intro > *, .fm-guest__search, .fm-guest__categories',
        0, 0.052, { distance: 7, duration: 0.62 }
    );
    softEntrance(find('.fm-guest__products > .fm-guest__product').slice(0, 14),
        0.035, 0.033, { distance: 6, duration: 0.61 }
    );
    // Customer checkout stays fully visible and in normal scroll flow.
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

    // Only animate direct conversational / basket feedback. Business lists
    // (inventory, finance, warehouse logs and employee/table rows) update
    // without visual re-entry, so Livewire filters cannot flash the whole list.
    const feedbackClasses = '.fm-chat__message, .fm-pos__basket-row';
    if (element.matches?.(feedbackClasses) && !animatedNodes.has(element)) {
        sceneContext.add(() => softEntrance([element], 0, 0, {
            distance: 4, duration: 0.46,
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
                y: -2,
                scale: 1,
                duration: 0.38,
                ease: 'sine.out',
                overwrite: 'auto',
            });
            const icon = card.querySelector('.fm-public-feature__icon');
            if (icon) gsap.to(icon, {
                y: -2, rotation: -3, duration: 0.38,
                ease: 'sine.out', overwrite: 'auto',
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
                duration: 0.42,
                ease: 'sine.out',
                overwrite: 'auto',
                clearProps: 'transform',
            });
            const icon = card.querySelector('.fm-public-feature__icon');
            if (icon) gsap.to(icon, {
                y: 0, rotation: 0, duration: 0.42,
                ease: 'sine.out', overwrite: 'auto',
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
        // The Livewire morph already updated the chart. Replaying a
        // stroke-dash animation made the line visually snap backwards.
        gsap.killTweensOf(el);
        el.style.removeProperty('stroke-dashoffset');
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
        if (row) softEntrance([row], 0, 0, { distance: 4, duration: 0.42 });
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
        softEntrance(Array.from(preview.children), 0, 0.055, {
            distance: 5, duration: 0.48,
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
