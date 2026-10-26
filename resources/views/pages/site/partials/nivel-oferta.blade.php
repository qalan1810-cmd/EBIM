{{--
    Sección de un nivel de la Oferta Académica (encabezado, ficha, menciones y plan de estudios).
    Recibe: $nivel (array con clave, nombre, lema, descripcion, duracion, horas, modalidad,
    resolucion, semestres, menciones y malla) y $conFondo (bool: si la sección lleva fondo claro).
--}}
@php($pares = $conFondo ?? false)

<section id="{{ $nivel['clave'] }}"
    class="py-[60px] max-md:py-[45px] {{ $pares ? 'bg-white' : '' }}">
    <div class="ebim-container" data-reveal>

        {{-- Encabezado del nivel --}}
        <div class="mx-auto max-w-[680px] text-center">
            <h3 class="text-[40px] font-bold leading-[1.4] text-ink max-md:text-[30px]">
                {{ $nivel['nombre'] }}
            </h3>

            {{-- Adorno: dos líneas y un punto central --}}
            <span class="mx-auto mt-5 flex items-center justify-center gap-3" aria-hidden="true">
                <span class="block h-[1px] w-[70px] bg-ink/20"></span>
                <span class="block h-[8px] w-[8px] rotate-45 bg-brand"></span>
                <span class="block h-[1px] w-[70px] bg-ink/20"></span>
            </span>

            <p class="mt-5 text-[18px] leading-[1.6] text-ink-soft">
                {{ $nivel['descripcion'] }}
            </p>
        </div>

        {{-- Ficha general + menciones, lado a lado --}}
        <div class="mt-10 grid grid-cols-2 gap-[24px] max-md:mt-7 max-md:grid-cols-1 max-md:gap-[18px]">

            {{-- Cuadro general: duración, modalidad y respaldo --}}
            <div class="group relative overflow-hidden rounded-[25px] border-2 border-ink bg-white p-[40px] max-md:p-[30px]">
                <p class="text-[13px] font-bold uppercase tracking-[2px] text-brand">Datos de la carrera</p>

                <span class="mt-4 block h-[4px] w-[60px] bg-brand" aria-hidden="true"></span>

                <div class="mt-5 flex flex-col gap-[18px]">
                    <div class="flex items-baseline gap-[12px]">
                        <span class="w-[130px] shrink-0 text-[15px] font-semibold text-ink-soft max-md:w-[110px]">Duración</span>
                        <span class="text-[22px] font-bold leading-[1.25] text-ink max-md:text-[19px]">
                            {{ $nivel['duracion'] }}
                        </span>
                    </div>

                    <div class="flex items-baseline gap-[12px] border-t border-dashed border-ink/15 pt-[18px]">
                        <span class="w-[130px] shrink-0 text-[15px] font-semibold text-ink-soft max-md:w-[110px]">Modalidad</span>
                        <span class="text-[22px] font-bold leading-[1.25] text-ink max-md:text-[19px]">
                            {{ $nivel['modalidad'] }}
                        </span>
                    </div>

                    <div class="flex items-baseline gap-[12px] border-t border-dashed border-ink/15 pt-[18px]">
                        <span class="w-[130px] shrink-0 text-[15px] font-semibold text-ink-soft max-md:w-[110px]">Respaldo</span>
                        <span class="text-[22px] font-bold leading-[1.25] text-ink max-md:text-[19px]">
                            {{ $nivel['resolucion'] }}
                            <span class="block text-[15px] font-normal text-ink-soft">Ministerio de Educación</span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Menciones --}}
            <div class="relative overflow-hidden rounded-[25px] bg-brand p-[40px] text-white max-md:p-[30px]">
                <span class="absolute -right-[40px] -top-[40px] h-[150px] w-[150px] rounded-full bg-white/10" aria-hidden="true"></span>

                <div class="relative">
                    <p class="text-[13px] font-bold uppercase tracking-[2px] text-white/80">
                        {{ count($nivel['menciones']) > 1 ? 'Menciones' : 'Mención' }}
                    </p>

                    <span class="mt-4 block h-[4px] w-[60px] bg-white" aria-hidden="true"></span>

                    <ul class="mt-5 flex flex-col gap-[14px]">
                        @foreach ($nivel['menciones'] as $mencion)
                            <li class="flex items-start gap-[12px] text-[19px] font-semibold leading-[1.35]">
                                <span class="mt-[9px] h-[8px] w-[8px] shrink-0 rotate-45 bg-white" aria-hidden="true"></span>
                                {{ $mencion }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Plan de estudios: año por año, en lenguaje claro.
             Va plegado por defecto para no alargar la página; el botón lo
             despliega y vuelve a plegarlo. Dentro no cambia nada. --}}
        <div class="mt-12 max-md:mt-9" data-accordion>
            <div data-accordion-item data-open="false">
                <div class="flex items-end justify-between gap-6 max-md:flex-col max-md:items-start">
                    <h4 class="text-[32px] font-bold leading-[1.3] text-ink max-md:text-[26px]">Plan de Estudios</h4>

                    <span class="mb-[12px] hidden h-[1px] flex-1 bg-ink/15 md:block" aria-hidden="true"></span>

                    <button type="button" data-accordion-title aria-expanded="false"
                        data-etiqueta-cerrada="Ver plan de estudios" data-etiqueta-abierta="Ocultar plan de estudios"
                        class="inline-flex shrink-0 items-center gap-[10px] rounded-[15px] border-2 border-ink px-5 py-[9px] text-[15px] font-bold uppercase tracking-[1px] text-ink hover:bg-ink hover:text-white">
                        {{-- Ancho fijo con el de "Ocultar": el botón no cambia
                             de tamaño al abrir o cerrar. --}}
                        <span data-accordion-label data-cerrado="Ver" data-abierto="Ocultar"
                            class="min-w-[77px] text-center">Ver</span>
                        <span class="plan-icono text-[20px] leading-none" data-accordion-icon aria-hidden="true">+</span>
                    </button>
                </div>

                <div data-accordion-content data-accordion-animado class="plan-desplegable">
                    <div>
                        <div class="mt-8 grid grid-cols-2 gap-[22px] max-md:mt-6 max-md:grid-cols-1 max-md:gap-[16px]">
                        @foreach (range(0, intdiv($nivel['semestres'] - 1, 2)) as $a)
                            @php($materiasAnio = collect($nivel['malla'])->map(fn ($horas) => ($horas[$a * 2] ?? 0) + ($horas[$a * 2 + 1] ?? 0))->filter())

                            <div class="relative flex h-full flex-col overflow-hidden rounded-[25px] border border-ink/10 bg-white p-[30px] max-md:p-[24px]">

                                {{-- Año en un cuadro rojo, centrado --}}
                                <div class="mx-auto rounded-[16px] bg-brand px-[26px] py-[10px] text-center text-white shadow-[0_10px_22px_rgba(188,28,44,0.28)]">
                                    <span class="text-[22px] font-black leading-none max-md:text-[20px]">
                                        {{ $a + 1 }}° año
                                    </span>
                                </div>

                                <ul class="mt-6 flex flex-col gap-[12px]">
                                    @foreach ($materiasAnio as $materia => $horas)
                                        <li class="flex items-start gap-[10px] border-b border-dashed border-ink/10 pb-[12px] last:border-0 last:pb-0">
                                            <span class="mt-[7px] h-[7px] w-[7px] shrink-0 rotate-45 bg-brand/60" aria-hidden="true"></span>
                                            <span class="text-[16px] leading-[1.4] text-ink">{{ $materia }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
