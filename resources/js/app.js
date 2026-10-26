/*
 * Soho Sonic clone — interaction layer.
 * Mirrors the Elementor behaviours the source site relies on:
 * sticky header background, mobile menu, dropdowns, FAQ accordion,
 * logo marquee and the newsletter popup. Also handles Elementor
 * accordion/toggle, portfolio filters, slideshows/carousels,
 * gallery lightbox, crew modal, and form suppression.
 */

const onReady = (fn) => {
    if (document.readyState !== 'loading') {
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
};

onReady(() => {
    /* ------------------------------------------------------------------
     * Sticky header
     * ------------------------------------------------------------------ */
    const header = document.getElementById('site-header');
    if (header) {
        // The dark bar fades/descends into place via a CSS transition on the
        // registered --nav-cover property (see app.css); JS only flips the
        // state class so the animation runs instead of snapping on.
        const updateHeader = () => {
            header.classList.toggle('is-scrolled', window.scrollY > 10);
        };
        updateHeader();
        window.addEventListener('scroll', updateHeader, { passive: true });
    }

    /* ------------------------------------------------------------------
     * Mobile menu
     * ------------------------------------------------------------------ */
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('menu-icon-open');
    const iconClose = document.getElementById('menu-icon-close');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', () => {
            const isOpen = !mobileMenu.classList.contains('hidden');
            mobileMenu.classList.toggle('hidden');
            iconOpen?.classList.toggle('hidden', !isOpen);
            iconClose?.classList.toggle('hidden', !isOpen ? false : true);
            menuToggle.setAttribute('aria-expanded', String(!isOpen));
        });
    }

    document.querySelectorAll('.mobile-submenu-toggle').forEach((button) => {
        button.addEventListener('click', () => {
            const submenu = button.closest('li')?.querySelector('.mobile-submenu');
            submenu?.classList.toggle('hidden');
        });
    });

    /* ------------------------------------------------------------------
     * FAQ accordion (legacy data-accordion)
     * ------------------------------------------------------------------ */
    document.querySelectorAll('[data-accordion]').forEach((accordion) => {
        const items = accordion.querySelectorAll('[data-accordion-item]');

        // Un solo punto de entrada para abrir/cerrar: mantiene sincronizados el
        // contenido, el ícono y —cuando existe— el texto del botón
        // ([data-accordion-label] con data-cerrado / data-abierto).
        const setState = (item, open) => {
            const title = item.querySelector('[data-accordion-title]');
            const content = item.querySelector('[data-accordion-content]');
            const label = item.querySelector('[data-accordion-label]');

            item.setAttribute('data-open', open ? 'true' : 'false');

            // Los paneles con [data-accordion-animado] no usan display: la
            // altura la anima el CSS leyendo data-open.
            if (content && !content.hasAttribute('data-accordion-animado')) {
                content.classList.toggle('hidden', !open);
            }

            title?.classList.toggle('text-brand', open);
            title?.setAttribute('aria-expanded', open ? 'true' : 'false');

            if (label) {
                const text = open ? label.dataset.abierto : label.dataset.cerrado;
                if (text) label.textContent = text;
            }

            // Etiqueta accesible completa ("Ver plan de estudios" /
            // "Ocultar plan de estudios") sin cambiar el texto visible.
            if (title?.dataset.etiquetaCerrada) {
                title.setAttribute(
                    'aria-label',
                    open ? title.dataset.etiquetaAbierta : title.dataset.etiquetaCerrada
                );
            }
        };

        items.forEach((item) => {
            const title = item.querySelector('[data-accordion-title]');
            const content = item.querySelector('[data-accordion-content]');
            if (!title || !content) return;

            setState(item, item.getAttribute('data-open') === 'true');

            title.addEventListener('click', () => {
                const isOpen = item.getAttribute('data-open') === 'true';
                items.forEach((other) => setState(other, false));
                if (!isOpen) setState(item, true);
            });
        });
    });

    /* ------------------------------------------------------------------
     * Elementor accordion (.elementor-accordion)
     * ------------------------------------------------------------------ */
    document.querySelectorAll('.elementor-accordion').forEach((acc) => {
        const items = Array.from(acc.querySelectorAll('.elementor-accordion-item'));
        items.forEach((item) => {
            const title = item.querySelector('.elementor-tab-title');
            const content = item.querySelector('.elementor-tab-content');
            if (!title || !content) return;
            title.addEventListener('click', (e) => {
                e.preventDefault();
                const active = title.classList.contains('elementor-active');
                items.forEach((it) => {
                    const t = it.querySelector('.elementor-tab-title');
                    const c = it.querySelector('.elementor-tab-content');
                    t?.classList.remove('elementor-active');
                    t?.setAttribute('aria-expanded', 'false');
                    if (c) c.style.display = 'none';
                });
                if (!active) {
                    title.classList.add('elementor-active');
                    title.setAttribute('aria-expanded', 'true');
                    content.style.display = 'block';
                }
            });
        });
    });

    /* ------------------------------------------------------------------
     * Elementor toggle (.elementor-toggle)
     * ------------------------------------------------------------------ */
    document.querySelectorAll('.elementor-toggle').forEach((toggle) => {
        const items = Array.from(toggle.querySelectorAll('.elementor-toggle-item'));
        items.forEach((item) => {
            const title = item.querySelector('.elementor-tab-title');
            const content = item.querySelector('.elementor-tab-content');
            if (!title || !content) return;
            title.addEventListener('click', (e) => {
                e.preventDefault();
                const active = title.classList.contains('elementor-active');
                if (active) {
                    title.classList.remove('elementor-active');
                    title.setAttribute('aria-expanded', 'false');
                    content.style.display = 'none';
                } else {
                    title.classList.add('elementor-active');
                    title.setAttribute('aria-expanded', 'true');
                    content.style.display = 'block';
                }
            });
        });
    });

    /* ------------------------------------------------------------------
     * Portfolio filters
     * ------------------------------------------------------------------ */
    document.querySelectorAll('.elementor-portfolio').forEach((grid) => {
        const container = grid.closest('.elementor-widget-portfolio') || grid.parentElement;
        const filters = container ? Array.from(container.querySelectorAll('.elementor-portfolio__filter')) : [];
        const items = Array.from(grid.querySelectorAll('.elementor-portfolio-item'));
        filters.forEach((f) => {
            f.addEventListener('click', () => {
                filters.forEach((x) => x.classList.remove('elementor-active'));
                f.classList.add('elementor-active');
                const filter = f.getAttribute('data-filter') || '__all';
                items.forEach((it) => {
                    if (filter === '__all') {
                        it.style.display = '';
                        return;
                    }
                    const classes = it.className;
                    if (classes.includes('elementor-filter-' + filter)) {
                        it.style.display = '';
                    } else {
                        it.style.display = 'none';
                    }
                });
            });
        });
    });

    /* ------------------------------------------------------------------
     * Background slideshow [data-ss-bgslideshow] (fade)
     * ------------------------------------------------------------------ */
    document.querySelectorAll('[data-ss-bgslideshow]').forEach((el) => {
        const wrapper = el.querySelector('.swiper-wrapper');
        const slides = wrapper ? Array.from(wrapper.querySelectorAll('.swiper-slide')) : [];
        if (slides.length <= 1) return;
        slides.forEach((s, i) => {
            s.style.position = 'absolute';
            s.style.inset = '0';
            s.style.opacity = i === 0 ? '1' : '0';
            s.style.transition = 'opacity .6s ease';
            s.style.zIndex = i === 0 ? '1' : '0';
            s.style.pointerEvents = 'none';
        });
        if (wrapper) {
            wrapper.style.position = 'relative';
            wrapper.style.display = 'block';
            wrapper.style.height = '100%';
        }
        let idx = 0;
        setInterval(() => {
            const next = (idx + 1) % slides.length;
            slides[idx].style.opacity = '0';
            slides[idx].style.zIndex = '0';
            slides[next].style.opacity = '1';
            slides[next].style.zIndex = '1';
            idx = next;
        }, 5000);
    });

    /* ------------------------------------------------------------------
     * Carousels: .elementor-main-swiper (media carousel / slides)
     * ------------------------------------------------------------------ */
    document.querySelectorAll('.elementor-main-swiper').forEach((el) => {
        const wrapper = el.querySelector('.swiper-wrapper');
        const slides = wrapper ? Array.from(wrapper.querySelectorAll('.swiper-slide')) : [];
        if (slides.length <= 1) return;
        const widget = el.closest('[data-settings]');
        let slidesPerView = 1;
        let autoplaySpeed = 5000;
        let transition = 'slide';
        if (widget) {
            try {
                const s = JSON.parse(widget.getAttribute('data-settings') || '{}');
                const v = parseInt(s.slides_per_view || s.slidesPerView);
                if (!isNaN(v) && v > 0) slidesPerView = Math.min(v, slides.length);
                if (s.autoplay_speed) autoplaySpeed = parseInt(s.autoplay_speed) || autoplaySpeed;
                if (s.transition) transition = s.transition;
            } catch (e) {}
        }
        // Fallback: if slidesPerView is 1 but many visible? use count of slides fitting? default 1
        if (slidesPerView === 0) slidesPerView = 1;
        if (transition === 'fade') {
            slides.forEach((s, i) => {
                s.style.position = 'absolute';
                s.style.inset = '0';
                s.style.opacity = i === 0 ? '1' : '0';
                s.style.transition = 'opacity .6s ease';
                s.style.zIndex = i === 0 ? '1' : '0';
            });
            if (wrapper) {
                wrapper.style.position = 'relative';
                wrapper.style.display = 'block';
                wrapper.style.height = '100%';
            }
            let idx = 0;
            setInterval(() => {
                const next = (idx + 1) % slides.length;
                slides[idx].style.opacity = '0';
                slides[idx].style.zIndex = '0';
                slides[next].style.opacity = '1';
                slides[next].style.zIndex = '1';
                idx = next;
            }, autoplaySpeed);
            return;
        }
        // Slide/carousel
        const gap = 0;
        const unit = 100 / slidesPerView;
        slides.forEach((s) => {
            s.style.flex = '0 0 ' + unit + '%';
            s.style.maxWidth = unit + '%';
            s.style.boxSizing = 'border-box';
        });
        if (wrapper) {
            wrapper.style.display = 'flex';
            wrapper.style.transition = 'transform .5s ease';
            wrapper.style.willChange = 'transform';
        }
        let idx = 0;
        const maxIdx = Math.max(0, slides.length - slidesPerView);
        setInterval(() => {
            idx++;
            if (idx > maxIdx) idx = 0;
            if (wrapper) {
                wrapper.style.transform = 'translateX(-' + (idx * unit) + '%)';
            }
        }, autoplaySpeed);
    });

    /* ------------------------------------------------------------------
     * Newsletter popup
     * ------------------------------------------------------------------ */
    const overlay = document.getElementById('newsletter-overlay');
    const dialog = document.getElementById('newsletter-dialog');
    const closeButton = document.getElementById('newsletter-close');
    const form = document.getElementById('newsletter-form');
    const message = document.getElementById('newsletter-message');

    if (overlay) {
        const open = () => {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
        };
        const close = () => {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
            sessionStorage.setItem('ss-newsletter-dismissed', '1');
        };

        if (!sessionStorage.getItem('ss-newsletter-dismissed')) {
            window.setTimeout(open, 25000);

            document.addEventListener('mouseout', (event) => {
                if (event.clientY <= 0 && !overlay.classList.contains('flex')) {
                    open();
                }
            });
        }

        closeButton?.addEventListener('click', close);
        overlay.addEventListener('click', (event) => {
            if (event.target === overlay) close();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') close();
        });

        form?.addEventListener('submit', (event) => {
            event.preventDefault();
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            if (message) {
                message.textContent = 'Thanks for signing up! We will be in touch soon.';
                message.classList.remove('hidden');
            }
            window.setTimeout(close, 1800);
        });
    }

    /* ------------------------------------------------------------------
     * Carrusel de fotos de los encabezados [data-hero-slide]
     *
     * Es la unica animacion del sitio ademas de la barra de navegacion. Cada
     * foto lleva .is-active cuando le toca el turno; el resto (desvanecido y
     * acercamiento continuo) vive entero en el CSS.
     *
     * Importante: NO se reinicia ninguna animacion al cambiar de foto. La
     * version anterior quitaba y volvia a poner la clase forzando un reflujo,
     * lo que reiniciaba el acercamiento desde el tamano original y se veia como
     * un salto. Ahora solo se alterna la clase: la escala transiciona de forma
     * continua (el CSS tarda 9 s) mientras las fotos se cruzan.
     *
     * La duracion de cada foto es DURACION_MS. Las imagenes se precargan para
     * que el cruce nunca se atasque con una foto que todavia no llego.
     * ------------------------------------------------------------------ */
    document.querySelectorAll('[data-carousel-hero]').forEach((hero) => {
        const slides = Array.from(hero.querySelectorAll('[data-hero-slide]'));
        if (slides.length <= 1) return;

        const DURACION_MS = 6000;

        // Precarga: descarga cada foto en segundo plano para que el cruce sea
        // instantaneo aunque la conexion sea lenta.
        slides.forEach((slide) => {
            const url = (slide.style.backgroundImage || '').replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
            if (!url) return;
            const img = new Image();
            img.src = url;
        });

        // Respeta a quien pidio menos movimiento: deja la primera foto fija.
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        let actual = 0;

        // Cuando una foto ya se apago del todo (fuera de la vista), se devuelve
        // a su tamano original sin transicion. Como en ese momento es invisible,
        // el reinicio nunca se ve: la foto vuelve a crecer desde 1 la proxima
        // vez que le toque, sin ningun salto perceptible.
        const devolverAlOrigen = (slide) => {
            slide.classList.add('page-hero-slide--reset');
            void slide.offsetWidth; // fuerza el reflujo ya en su tamano original
        };

        window.setInterval(() => {
            const siguiente = (actual + 1) % slides.length;
            const saliente = slides[actual];

            // Solo se alterna la clase: la escala sigue transicionando de forma
            // continua, nunca vuelve de golpe al tamano original a la vista.
            saliente.classList.remove('is-active');
            slides[siguiente].classList.add('is-active');

            // La foto que entra ya no debe estar en modo "reinicio".
            slides[siguiente].classList.remove('page-hero-slide--reset');

            // El velo "respira": se marca el encabezado durante el cruce.
            hero.classList.add('is-switching');
            window.setTimeout(() => hero.classList.remove('is-switching'), 700);

            // Al terminar el desvanecido (1.6 s), la foto saliente ya no se ve:
            // es el momento seguro para devolverla a su tamano original.
            window.setTimeout(() => {
                if (!saliente.classList.contains('is-active')) devolverAlOrigen(saliente);
            }, 1700);

            actual = siguiente;
        }, DURACION_MS);
    });

    /* ------------------------------------------------------------------
     * Justified gallery layout [data-ss-justified]
     * Reimplements Elementor's gallery.js justified layout: items are
     * packed into rows whose height approximates `ideal_row_height`,
     * each item's width proportional to its aspect ratio.
     * ------------------------------------------------------------------ */
    const justifiedContainers = Array.from(document.querySelectorAll('[data-ss-justified]'));
    const layoutJustified = (container) => {
        const items = Array.from(container.querySelectorAll('.e-gallery-item'));
        if (!items.length) return;
        const gap = parseFloat(container.dataset.ssGap || '10') || 0;
        const target = parseFloat(container.dataset.ssRowHeight || '208') || 208;
        const width = container.clientWidth;
        if (!width) return;
        const ratios = items.map((it) => parseFloat(it.dataset.ssAr) || 1);
        const apply = (row, sum, isLast) => {
            if (!row.length) return;
            let h = (width - gap * (row.length - 1)) / sum;
            if (isLast && h > target) h = target;
            row.forEach((it) => {
                const ar = parseFloat(it.dataset.ssAr) || 1;
                it.style.width = ar * h + 'px';
                it.style.height = h + 'px';
            });
        };
        let row = [];
        let sum = 0;
        for (let i = 0; i < items.length; i++) {
            row.push(items[i]);
            sum += ratios[i];
            const projected = (width - gap * (row.length - 1)) / sum;
            if (projected <= target) {
                apply(row, sum, false);
                row = [];
                sum = 0;
            }
        }
        apply(row, sum, true);
    };
    justifiedContainers.forEach(layoutJustified);
    let justifyResize;
    window.addEventListener('resize', () => {
        clearTimeout(justifyResize);
        justifyResize = setTimeout(() => justifiedContainers.forEach(layoutJustified), 150);
    });

    /* ------------------------------------------------------------------
     * Gallery lightbox [data-ss-gallery]
     * ------------------------------------------------------------------ */
    let lightbox;
    const createLightbox = () => {
        if (lightbox) return lightbox;
        lightbox = document.createElement('div');
        lightbox.id = 'ss-lightbox';
        lightbox.className = 'fixed inset-0 z-[9999] hidden items-center justify-center bg-black/90 p-4';
        lightbox.innerHTML = `
            <button class="absolute right-4 top-4 text-white text-4xl leading-none" aria-label="Close" data-ss-close>&times;</button>
            <img class="max-h-full max-w-full object-contain" data-ss-img />
        `;
        document.body.appendChild(lightbox);
        lightbox.querySelector('[data-ss-close]').addEventListener('click', () => {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
        });
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
            }
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
            }
        });
        return lightbox;
    };
    document.querySelectorAll('[data-ss-gallery]').forEach((a) => {
        a.addEventListener('click', (e) => {
            e.preventDefault();
            const full = a.getAttribute('data-ss-full') || a.getAttribute('href');
            const lb = createLightbox();
            const img = lb.querySelector('[data-ss-img]');
            if (img && full) img.src = full;
            lb.classList.remove('hidden');
            lb.classList.add('flex');
        });
    });

    /* ------------------------------------------------------------------
     * Crew modal [data-ss-crew-open]
     * ------------------------------------------------------------------ */
    document.querySelectorAll('[data-ss-crew-modal]').forEach((m) => {
        const closeAll = () => {
            document.querySelectorAll('[data-ss-crew-modal]').forEach((x) => {
                x.hidden = true;
            });
        };
        m.addEventListener('click', (e) => {
            if (e.target.hasAttribute('data-ss-crew-close') || e.target === m.querySelector('[data-ss-crew-close]') || e.target.classList.contains('ss-crew-modal__backdrop')) {
                closeAll();
            }
        });
    });
    document.querySelectorAll('[data-ss-crew-open]').forEach((btn) => {
        const idx = btn.getAttribute('data-ss-crew-open');
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-ss-crew-modal]').forEach((m) => {
                m.hidden = m.getAttribute('data-ss-crew-modal') !== idx;
            });
            const target = document.querySelector('[data-ss-crew-modal="' + idx + '"]');
            if (target) target.hidden = false;
        });
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('[data-ss-crew-modal]').forEach((m) => (m.hidden = true));
        }
    });

    /* ------------------------------------------------------------------
     * Aparicion al hacer scroll
     *
     * Los bloques marcados con [data-reveal] suben con un fade in la primera
     * vez que entran en pantalla. La animacion se dispara UNA sola vez por
     * bloque: en cuanto se marca como revelado se deja de observar, asi que
     * nunca se repite ni se reinicia al subir y bajar. Al terminar, la clase
     * queda puesta y el bloque se queda fijo en su sitio.
     * ------------------------------------------------------------------ */
    const bloquesReveal = document.querySelectorAll('[data-reveal]:not(.is-visible)');

    if (bloquesReveal.length) {
        const mostrar = (el) => {
            el.classList.add('is-visible');
        };

        // Sin IntersectionObserver (o con menos movimiento pedido) el contenido
        // se muestra de inmediato: no se oculta informacion a nadie.
        const sinMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (sinMovimiento || !('IntersectionObserver' in window)) {
            bloquesReveal.forEach(mostrar);
        } else {
            const observador = new IntersectionObserver(
                (entradas, obs) => {
                    // Las tarjetas marcadas con [data-reveal-grupo] (por ejemplo
                    // las fotos de los instrumentos) entran una tras otra: se
                    // cuentan las que aparecen en esta misma entrada de pantalla
                    // y cada una arranca 70 ms despues de la anterior.
                    let escalon = 0;

                    entradas.forEach((entrada) => {
                        if (!entrada.isIntersecting) return;

                        const el = entrada.target;

                        if (el.hasAttribute('data-reveal-grupo')) {
                            el.style.setProperty('--reveal-delay', `${escalon * 70}ms`);
                            escalon += 1;
                        }

                        mostrar(el);
                        // Se deja de vigilar: la animacion no vuelve a correr.
                        obs.unobserve(el);
                    });
                },
                // threshold 0 y un margen inferior: el bloque empieza a subir en
                // cuanto asoma por la parte baja de la pantalla. No se usa un
                // porcentaje alto porque las secciones largas (la cronologia,
                // por ejemplo) nunca llegarian a cumplirlo.
                { threshold: 0, rootMargin: '0px 0px -8% 0px' }
            );

            bloquesReveal.forEach((el) => {
                // Si al cargar la pagina ya quedó por encima de la ventana (por
                // ejemplo al entrar con un ancla), se muestra sin animacion.
                if (el.getBoundingClientRect().top < 0) {
                    mostrar(el);
                    return;
                }
                observador.observe(el);
            });
        }
    }

    /* ------------------------------------------------------------------
     * Galeria de "Nuestra historia" (Inicio)
     *
     * Cada 5 segundos la pista se desplaza un ancho completo hacia la
     * izquierda: la foto sale con su propia leyenda y la siguiente entra
     * desde la derecha con la suya, sin desvanecidos de ningun tipo. Al
     * terminar el recorrido se mueve la primera diapositiva al final y se
     * reinicia la pista sin animacion, de modo que el movimiento siempre va
     * en el mismo sentido (nunca rebobina hacia la derecha).
     * ------------------------------------------------------------------ */
    document.querySelectorAll('[data-galeria]').forEach((galeria) => {
        const pista = galeria.querySelector('[data-galeria-pista]');

        if (!pista || pista.children.length < 2) return;

        // Con menos movimiento pedido se deja la primera foto quieta.
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        const DURACION = 900; // desplazamiento, debe coincidir con app.css

        const reiniciarPista = () => {
            pista.classList.add('ss-galeria-pista--reset');
            pista.appendChild(pista.firstElementChild);
            pista.style.transform = 'translateX(0)';
            // Fuerza el recalculo antes de devolver la transicion, para que el
            // reacomodo no se vea.
            void pista.offsetWidth;
            pista.classList.remove('ss-galeria-pista--reset');
        };

        const avanzar = () => {
            if (document.hidden) return;

            pista.style.transform = 'translateX(-100%)';
            window.setTimeout(reiniciarPista, DURACION + 60);
        };

        window.setInterval(avanzar, 5000);
    });

    /* ------------------------------------------------------------------
     * Ventana emergente de "Ver ejemplo" (Inscripción)
     *
     * Muestra la imagen del documento encima de la página, sin abrir una
     * pestaña nueva ni un lector de PDF. Contiene solo la foto; se cierra con
     * la X, con clic en el fondo o con Esc, y devuelve el foco al botón que
     * la abrió.
     * ------------------------------------------------------------------ */
    const modalEjemplo = document.getElementById('modal-ejemplo');

    if (modalEjemplo) {
        const imagenEjemplo = modalEjemplo.querySelector('#modal-ejemplo-img');
        const botonCerrar = modalEjemplo.querySelector('.ss-modal-cerrar');
        let disparador = null;

        const cerrarModal = () => {
            if (modalEjemplo.hidden) return;

            modalEjemplo.hidden = true;
            document.documentElement.style.overflow = '';
            imagenEjemplo.removeAttribute('src');

            if (disparador) {
                disparador.focus();
                disparador = null;
            }
        };

        document.querySelectorAll('[data-ejemplo-src]').forEach((boton) => {
            boton.addEventListener('click', () => {
                disparador = boton;
                imagenEjemplo.src = boton.dataset.ejemploSrc;
                imagenEjemplo.alt = boton.dataset.ejemploAlt || '';
                modalEjemplo.hidden = false;
                document.documentElement.style.overflow = 'hidden';
                botonCerrar.focus();
            });
        });

        modalEjemplo.querySelectorAll('[data-modal-cerrar]').forEach((elemento) => {
            elemento.addEventListener('click', cerrarModal);
        });

        modalEjemplo.addEventListener('click', (e) => {
            if (e.target === modalEjemplo) cerrarModal();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modalEjemplo.hidden) {
                cerrarModal();
            }
        });
    }

    /* ------------------------------------------------------------------
     * Avisos flotantes (éxito y error)
     *
     * Se cierran solos a los pocos segundos; el temporizador se detiene
     * mientras el puntero o el teclado están sobre el aviso, y la X lo cierra
     * de inmediato. Al desaparecer no dejan hueco en la página.
     * ------------------------------------------------------------------ */
    document.querySelectorAll('[data-aviso]').forEach((aviso) => {
        const duracion = Number(aviso.dataset.duracion) || 6000;
        let temporizador = null;

        const cerrar = () => {
            if (aviso.classList.contains('is-saliendo')) return;

            window.clearTimeout(temporizador);
            aviso.classList.add('is-saliendo');
            window.setTimeout(() => aviso.remove(), 320);
        };

        const programar = () => {
            window.clearTimeout(temporizador);
            temporizador = window.setTimeout(cerrar, duracion);
        };

        aviso.querySelector('[data-aviso-cerrar]')?.addEventListener('click', cerrar);
        aviso.addEventListener('mouseenter', () => window.clearTimeout(temporizador));
        aviso.addEventListener('mouseleave', programar);
        aviso.addEventListener('focusin', () => window.clearTimeout(temporizador));
        aviso.addEventListener('focusout', programar);

        programar();
    });

    /* ------------------------------------------------------------------
     * Elementor forms
     * ------------------------------------------------------------------ */
    document.querySelectorAll('.elementor-form').forEach((f) => {
        f.addEventListener('submit', (e) => {
            e.preventDefault();
        });
    });

    void dialog;
});
