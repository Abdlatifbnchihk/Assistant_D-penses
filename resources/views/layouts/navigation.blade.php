<nav x-data="{ open: false }" style="background-color: var(--color-paper); border-bottom: 1px solid var(--color-line);">
    <!-- Primary Navigation Menu -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-6">
                <!-- Brand Mark -->
                <a href="{{ Auth::check() ? route('Receipt.index') : url('/') }}" class="flex items-center gap-2 text-decoration-none">
                    <svg class="w-6 h-6" style="color: var(--color-teal);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-display font-bold text-sm" style="color: var(--color-ink);">Assistant Dépenses</span>
                </a>

                <!-- Ledger Tabs -->
                @auth
                <div class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('Receipt.index') }}"
                       class="ledger-tab {{ request()->routeIs('Receipt.*') ? 'active' : '' }}">
                        Reçus
                    </a>
                </div>
                @endauth
            </div>

            <!-- Settings Dropdown -->
            @auth
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md focus:outline-none transition ease-in-out duration-150"
                                style="color: var(--color-ink-soft); font-family: var(--font-mono); font-size: 0.75rem; letter-spacing: 0.03em;">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Déconnexion') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @else
            <div class="hidden sm:flex sm:items-center gap-4">
                <a href="{{ route('login') }}" class="btn-ghost">Connexion</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-outline">Créer un compte</a>
                @endif
            </div>
            @endauth

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md focus:outline-none transition duration-150 ease-in-out"
                        style="color: var(--color-ink-soft);">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
            <a href="{{ route('Receipt.index') }}"
               class="block px-4 py-2 text-sm {{ request()->routeIs('Receipt.*') ? 'font-bold' : '' }}"
               style="font-family: var(--font-mono); text-transform: uppercase; letter-spacing: 0.05em; color: {{ request()->routeIs('Receipt.*') ? 'var(--color-teal)' : 'var(--color-ink-soft)' }};">
                Reçus
            </a>
            @endauth
        </div>

        <div class="pt-4 pb-1" style="border-top: 1px solid var(--color-line);">
            @auth
            <div class="px-4">
                <div class="font-medium text-base" style="color: var(--color-ink);">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm" style="color: var(--color-ink-soft);">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm" style="color: var(--color-ink-soft);">
                    {{ __('Profil') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm" style="color: var(--color-ink-soft);">
                        {{ __('Déconnexion') }}
                    </button>
                </form>
            </div>
            @else
            <div class="space-y-1">
                <a href="{{ route('login') }}" class="block px-4 py-2 text-sm" style="color: var(--color-ink-soft);">
                    {{ __('Connexion') }}
                </a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="block px-4 py-2 text-sm" style="color: var(--color-ink-soft);">
                    {{ __('Créer un compte') }}
                </a>
                @endif
            </div>
            @endauth
        </div>
    </div>
</nav>
