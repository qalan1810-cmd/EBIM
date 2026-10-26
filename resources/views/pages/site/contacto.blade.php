@extends('layouts.app')

@section('title', 'Contacto | EBIM Oruro')
@section('description', 'Contáctate con la Escuela Boliviana Intercultural de Música. Carretera Oruro – Cochabamba Km 13, Zona Capachos, Oruro, Bolivia.')
@section('body-class', 'sin-portada')

@php($contact = config('ebim.contact'))

@section('content')

    {{-- Datos de contacto --}}
    <section class="pt-[78px] pb-[60px] max-md:pt-[52px] max-md:pb-[45px]">
        <div class="ebim-container" data-reveal>
            <div class="flex items-end justify-between gap-6 max-md:flex-col max-md:items-start">
                <h1 class="text-[40px] font-bold leading-[1.4] text-ink max-md:text-[30px]">
                    Datos de contacto
                </h1>

                <span class="mb-[14px] hidden h-[1px] flex-1 bg-ink/15 md:block" aria-hidden="true"></span>

                <p class="mb-[10px] text-[15px] font-bold uppercase tracking-[3px] text-brand">Oruro · Bolivia</p>
            </div>

            {{-- Ficha: columnas separadas por una regla guindo, igual que los
                 datos institucionales de Sobre EBIM. --}}
            <div class="mt-12 grid grid-cols-4 gap-x-[40px] gap-y-[34px] max-lg:grid-cols-2 max-md:mt-9 max-md:grid-cols-1 max-md:gap-y-[26px]">
                {{-- Dirección --}}
                <div class="border-l-[3px] border-brand pl-[26px] max-md:pl-[20px]">
                    <p class="text-[13px] font-bold uppercase tracking-[2px] text-ink-soft">Dirección</p>
                    <p class="mt-3 text-[19px] font-semibold leading-[1.35] text-ink">
                        Zona Capachos
                    </p>
                    <p class="mt-2 text-[16px] leading-[1.5] text-ink-soft">
                        {{ $contact['address'] }}
                    </p>
                </div>

                {{-- Teléfono --}}
                <div class="border-l-[3px] border-brand pl-[26px] max-md:pl-[20px]">
                    <p class="text-[13px] font-bold uppercase tracking-[2px] text-ink-soft">Teléfono</p>
                    <p class="mt-3 text-[19px] font-semibold leading-[1.35] text-ink">
                        <a href="{{ $contact['phone_href'] }}" class="underline decoration-brand/40 decoration-2 underline-offset-4">{{ $contact['phone'] }}</a>
                    </p>
                    <p class="mt-2 text-[16px] leading-[1.5] text-ink-soft">
                        Atención en horario de oficina.
                    </p>
                </div>

                {{-- Correo --}}
                <div class="border-l-[3px] border-brand pl-[26px] max-md:pl-[20px]">
                    <p class="text-[13px] font-bold uppercase tracking-[2px] text-ink-soft">Correo electrónico</p>
                    <p class="mt-3 text-[19px] font-semibold leading-[1.35] text-ink">
                        <a href="mailto:{{ $contact['email'] }}" class="underline decoration-brand/40 decoration-2 underline-offset-4">{{ $contact['email'] }}</a>
                    </p>
                    <p class="mt-2 text-[16px] leading-[1.5] text-ink-soft">
                        Consultas académicas e institucionales.
                    </p>
                </div>

                {{-- Redes sociales --}}
                <div class="border-l-[3px] border-brand pl-[26px] max-md:pl-[20px]">
                    <p class="text-[13px] font-bold uppercase tracking-[2px] text-ink-soft">Redes sociales</p>
                    <ul class="mt-3 flex flex-col gap-[10px]">
                        <li>
                            <a href="{{ $contact['facebook'] }}" target="_blank" rel="noopener"
                                class="text-[17px] font-semibold text-ink underline decoration-brand/40 decoration-2 underline-offset-4">Facebook</a>
                        </li>
                        <li>
                            <a href="{{ $contact['instagram'] }}" target="_blank" rel="noopener"
                                class="text-[17px] font-semibold text-ink underline decoration-brand/40 decoration-2 underline-offset-4">Instagram</a>
                        </li>
                        <li>
                            <a href="{{ $contact['tiktok'] }}" target="_blank" rel="noopener"
                                class="text-[17px] font-semibold text-ink underline decoration-brand/40 decoration-2 underline-offset-4">TikTok</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Cómo llegar --}}
    <section class="bg-white py-[60px] max-md:py-[45px]">
        <div class="ebim-container" data-reveal>
            <div class="mx-auto max-w-[680px] text-center">
                <span class="text-[14px] font-bold uppercase tracking-[3px] text-brand">Cómo llegar</span>

                <h2 class="mt-3 text-[40px] font-bold leading-[1.4] text-ink max-md:text-[30px]">
                    Nuestra ubicación
                </h2>

                {{-- Adorno: dos líneas y un punto central --}}
                <span class="mx-auto mt-5 flex items-center justify-center gap-3" aria-hidden="true">
                    <span class="block h-[1px] w-[70px] bg-ink/20"></span>
                    <span class="block h-[8px] w-[8px] rotate-45 bg-brand"></span>
                    <span class="block h-[1px] w-[70px] bg-ink/20"></span>
                </span>
            </div>

            <div class="mt-12 overflow-hidden rounded-[25px] border border-ink/10 max-md:mt-9">
                <iframe title="Ubicación de la EBIM en Oruro"
                    src="https://maps.google.com/maps?q=Escuela%20Boliviana%20Intercultural%20de%20M%C3%BAsica%20Oruro&t=&z=12&ie=UTF8&iwloc=&output=embed"
                    class="h-[460px] w-full border-0 max-md:h-[330px]" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

@endsection
