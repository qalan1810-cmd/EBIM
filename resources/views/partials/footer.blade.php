@php
    $contact = config('ebim.contact');
    $footer = config('ebim.footer');
@endphp

<footer class="mt-0 bg-ink">

    {{-- Escudo + tres columnas --}}
    <div class="ebim-container py-[45px] max-md:py-[34px]">
        <div class="flex flex-row flex-wrap gap-x-[40px] gap-y-[30px] max-lg:gap-x-[30px] max-md:flex-col">

            {{-- Escudo institucional --}}
            <div class="w-[210px] shrink-0 max-md:w-full">
                <img src="/assets/ebim/escudo-ebim.png"
                    alt="EBIM — Escuela Boliviana Intercultural de Música"
                    class="h-[104px] w-auto max-w-none max-md:mx-auto max-md:h-[88px]">
            </div>

            {{-- Enlaces Rápidos --}}
            <div class="min-w-[150px] flex-1 max-md:min-w-0">
                <h4 class="text-[15px] font-bold uppercase tracking-[0.9px] text-white">Enlaces Rápidos</h4>
                <ul class="mt-3 space-y-[7px]">
                    @foreach ($footer['quick_links'] as $link)
                        <li>
                            <a href="{{ url($link['url']) }}"
                                class="text-[14px] font-medium tracking-[0.2px] text-white/85 hover:text-white">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contáctanos --}}
            <div class="min-w-[215px] flex-1 max-md:min-w-0">
                <h4 class="text-[15px] font-bold uppercase tracking-[0.9px] text-white">Contáctanos</h4>
                <ul class="mt-3 space-y-[10px] text-[14px] font-medium tracking-[0.2px] text-white/85">
                    <li>
                        <a href="{{ $contact['phone_href'] }}" class="flex items-start gap-[9px] text-white/85 hover:text-white">
                            <svg class="mt-[3px] h-[13px] w-[13px] shrink-0 fill-current" viewBox="0 0 512 512" aria-hidden="true">
                                <path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64c0 247.4 200.6 448 448 448 18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368c-70.4-33.3-127.4-90.3-160.7-160.7l52.3-40.3c13.7-11.1 18.4-30 11.6-46.3l-40-96z"/>
                            </svg>
                            <span>{{ $contact['phone'] }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:{{ $contact['email'] }}" class="flex items-start gap-[9px] text-white/85 hover:text-white">
                            <svg class="mt-[3px] h-[13px] w-[13px] shrink-0 fill-current" viewBox="0 0 512 512" aria-hidden="true">
                                <path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4 0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/>
                            </svg>
                            <span>{{ $contact['email'] }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $contact['maps'] }}" target="_blank" rel="noopener" class="flex items-start gap-[9px] text-white/85 hover:text-white">
                            <svg class="mt-[3px] h-[13px] w-[13px] shrink-0 fill-current" viewBox="0 0 384 512" aria-hidden="true">
                                <path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/>
                            </svg>
                            <span>{{ $contact['address'] }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Síguenos --}}
            <div class="min-w-[135px] flex-1 max-md:min-w-0">
                <h4 class="text-[15px] font-bold uppercase tracking-[0.9px] text-white">Síguenos</h4>
                <ul class="mt-3 space-y-[10px] text-[14px] font-medium tracking-[0.2px] text-white/85">
                    <li>
                        <a href="{{ $contact['facebook'] }}" target="_blank" rel="noopener"
                            class="flex items-center gap-[9px] text-white/85 hover:text-white">
                            <svg class="h-[14px] w-[14px] shrink-0 fill-current" viewBox="0 0 320 512" aria-hidden="true">
                                <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/>
                            </svg>
                            <span>Facebook</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $contact['instagram'] }}" target="_blank" rel="noopener"
                            class="flex items-center gap-[9px] text-white/85 hover:text-white">
                            <svg class="h-[14px] w-[14px] shrink-0 fill-current" viewBox="0 0 448 512" aria-hidden="true">
                                <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/>
                            </svg>
                            <span>Instagram</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $contact['tiktok'] }}" target="_blank" rel="noopener"
                            class="flex items-center gap-[9px] text-white/85 hover:text-white">
                            <svg class="h-[14px] w-[14px] shrink-0 fill-current" viewBox="0 0 448 512" aria-hidden="true">
                                <path d="M448 209.9a210.1 210.1 0 0 1 -122.8-39.3V349.4A162.6 162.6 0 1 1 185 188.3V278.2a74.6 74.6 0 1 0 52.2 71.2V0l88 0a121.2 121.2 0 0 0 1.9 22.2h0A122.2 122.2 0 0 0 381 102.4a121.4 121.4 0 0 0 67 20.1z"/>
                            </svg>
                            <span>TikTok</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Barra legal: enlaces + derechos --}}
    <div class="border-t border-white/15">
        <div class="ebim-container flex flex-col items-center gap-[8px] py-[16px]">
            <ul class="flex flex-row flex-wrap items-center justify-center gap-x-[24px] gap-y-[6px]">
                @foreach ($footer['legal'] as $link)
                    <li>
                        <a href="{{ url($link['url']) }}"
                            class="text-[12px] font-medium uppercase tracking-[0.7px] text-white/75 hover:text-white">
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <p class="text-center text-[12px] font-medium uppercase tracking-[0.7px] text-white/75">
                &copy; {{ date('Y') }} {{ config('ebim.full_name') }} &middot; Oruro, Bolivia
            </p>
        </div>
    </div>
</footer>
