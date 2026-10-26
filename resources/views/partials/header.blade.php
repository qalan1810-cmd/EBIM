@php($nav = config('ebim.navigation'))
<header id="site-header" class="sticky top-0 z-[100] w-full">
    <div class="ebim-container flex items-center py-[14px] max-lg:py-[10px] max-md:py-[5px]">

        {{-- Logo --}}
        <div class="shrink-0 max-md:w-[70%]">
            <a href="{{ url('/') }}" class="block">
                <img src="/assets/ebim/logo-ebim.png" alt="EBIM — Escuela Boliviana Intercultural de Música"
                    class="h-[50px] w-auto max-w-none lg:h-[64px]">
            </a>
        </div>

        {{-- Navegación de escritorio --}}
        <nav class="hidden min-w-0 flex-1 items-center justify-end lg:flex" aria-label="Menú principal">
            <ul class="flex flex-nowrap items-center whitespace-nowrap">
                @foreach ($nav as $item)
                    <li class="group relative shrink-0 {{ $item['class'] ?? '' }}">
                        <a href="{{ url($item['url']) }}" @class(['nav-item', 'is-active' => request()->is(ltrim($item['url'], '/')) || ($item['url'] === '/' && request()->is('/'))])>
                            {{ $item['label'] }}
                            @isset($item['children'])
                                <span class="ml-[6px] inline-flex">
                                    <svg class="h-[10px] w-[10px]"
                                        viewBox="0 0 448 512" fill="currentColor" aria-hidden="true">
                                        <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/>
                                    </svg>
                                </span>
                            @endisset
                        </a>

                        @isset($item['children'])
                            <ul class="invisible absolute right-0 top-full z-50 min-w-[220px] overflow-hidden rounded-[20px] bg-[#f9f9f9] py-1 opacity-0 shadow-lg group-hover:visible group-hover:opacity-100">
                                @foreach ($item['children'] as $child)
                                    <li>
                                        <a href="{{ url($child['url']) }}"
                                            class="block whitespace-nowrap px-5 py-3 text-[18px] font-semibold text-ink hover:bg-brand hover:text-white">
                                            {{ $child['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endisset
                    </li>
                @endforeach

                {{-- Acceso al portal / administración --}}
                <li class="group relative shrink-0">
                    @auth
                        <span class="nav-item cursor-default">
                            {{ auth()->user()->name }}
                            <span class="ml-[6px] inline-flex">
                                <svg class="h-[10px] w-[10px]" viewBox="0 0 448 512" fill="currentColor" aria-hidden="true">
                                    <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/>
                                </svg>
                            </span>
                        </span>
                        <ul class="invisible absolute right-0 top-full z-50 min-w-[230px] overflow-hidden rounded-[20px] bg-[#f9f9f9] py-1 opacity-0 shadow-lg group-hover:visible group-hover:opacity-100">
                            @if (auth()->user()->esPersonal())
                                <li>
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block whitespace-nowrap px-5 py-3 text-[18px] font-semibold text-ink hover:bg-brand hover:text-white">
                                        Panel de administración
                                    </a>
                                </li>
                                @if (auth()->user()->esDirector())
                                    <li>
                                        <a href="{{ route('admin.horarios.index') }}"
                                            class="block whitespace-nowrap px-5 py-3 text-[18px] font-semibold text-ink hover:bg-brand hover:text-white">
                                            Horarios
                                        </a>
                                    </li>
                                @endif
                            @else
                                <li>
                                    <a href="{{ route('portal.index') }}#perfil"
                                        class="block whitespace-nowrap px-5 py-3 text-[18px] font-semibold text-ink hover:bg-brand hover:text-white">
                                        Mi perfil
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('portal.index') }}#notas"
                                        class="block whitespace-nowrap px-5 py-3 text-[18px] font-semibold text-ink hover:bg-brand hover:text-white">
                                        Mis notas
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('portal.index') }}#horario"
                                        class="block whitespace-nowrap px-5 py-3 text-[18px] font-semibold text-ink hover:bg-brand hover:text-white">
                                        Mis horarios
                                    </a>
                                </li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full whitespace-nowrap px-5 py-3 text-left text-[18px] font-semibold text-ink hover:bg-brand hover:text-white">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    @else
                        <a href="{{ route('login') }}" @class(['nav-item', 'is-active' => request()->routeIs('login')])>
                            Ingresar
                        </a>
                    @endauth
                </li>
            </ul>
        </nav>

        {{-- Botón móvil --}}
        <div class="flex w-[30%] justify-end lg:hidden">
            <button type="button" id="menu-toggle"
                class="flex h-11 w-11 items-center justify-center text-white" aria-label="Menú" aria-expanded="false">
                <svg id="menu-icon-open" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="menu-icon-close" class="hidden h-7 w-7" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" d="M6 6l12 12M6 18L18 6" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Panel móvil --}}
    <div id="mobile-menu" class="hidden bg-[#1d1d1d] pb-6 lg:hidden">
        <ul class="ebim-container flex flex-col pt-2">
            @foreach ($nav as $item)
                <li class="border-b border-white/10">
                    <div class="flex items-center justify-between">
                        <a href="{{ url($item['url']) }}" class="block py-3 text-[18px] font-semibold text-white">
                            {{ $item['label'] }}
                        </a>
                        @isset($item['children'])
                            <button type="button" class="mobile-submenu-toggle p-2 text-white" aria-label="Expandir {{ $item['label'] }}">
                                <svg class="h-4 w-4" viewBox="0 0 448 512" fill="currentColor" aria-hidden="true">
                                    <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/>
                                </svg>
                            </button>
                        @endisset
                    </div>
                    @isset($item['children'])
                        <ul class="mobile-submenu hidden pb-3">
                            @foreach ($item['children'] as $child)
                                <li>
                                    <a href="{{ url($child['url']) }}" class="block py-2 pl-4 text-[16px] font-medium text-white/80">
                                        {{ $child['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endisset
                </li>
            @endforeach

            @auth
                <li class="border-b border-white/10">
                    @if (auth()->user()->esPersonal())
                        <a href="{{ route('admin.dashboard') }}" class="block py-3 text-[18px] font-semibold text-white">
                            Panel de administración
                        </a>
                    @else
                        <a href="{{ route('portal.index') }}#perfil" class="block py-3 text-[18px] font-semibold text-white">
                            Mi perfil
                        </a>
                    @endif
                </li>
                @if (auth()->user()->esDirector())
                    <li class="border-b border-white/10">
                        <a href="{{ route('admin.horarios.index') }}" class="block py-3 text-[18px] font-semibold text-white">
                            Horarios
                        </a>
                    </li>
                @endif
                @unless (auth()->user()->esPersonal())
                    <li class="border-b border-white/10">
                        <a href="{{ route('portal.index') }}#notas" class="block py-3 text-[18px] font-semibold text-white">
                            Mis notas
                        </a>
                    </li>
                    <li class="border-b border-white/10">
                        <a href="{{ route('portal.index') }}#horario" class="block py-3 text-[18px] font-semibold text-white">
                            Mis horarios
                        </a>
                    </li>
                @endunless
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full py-3 text-left text-[18px] font-semibold text-white">
                            Cerrar sesión
                        </button>
                    </form>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}" class="block py-3 text-[18px] font-semibold text-white">
                        Ingresar
                    </a>
                </li>
            @endauth
        </ul>
    </div>
</header>
