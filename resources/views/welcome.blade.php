<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Assistant Dépenses') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body antialiased" style="background-color: var(--color-page-bg); color: var(--color-ink);">
    <div class="min-h-screen">
        @guest
            <nav style="background-color: var(--color-paper); border-bottom: 1px solid var(--color-line);">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6" style="color: var(--color-teal);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="font-display font-bold text-sm" style="color: var(--color-ink);">Assistant Dépenses</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}" class="btn-ghost">Connexion</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-outline">Créer un compte</a>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>
        @else
            @include('layouts.navigation')
        @endguest

        @if (session('success'))
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="banner-info flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="banner-red">
                    <ul class="list-none m-0 p-0 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Hero --}}
        <header class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-14 text-center">
            <p class="text-xs tracking-widest uppercase mb-4" style="font-family: var(--font-mono); color: var(--color-ink-soft);">
                Pour les commerçants qui n'ont pas le temps de tout noter
            </p>
            <h1 class="font-display font-semibold text-4xl md:text-5xl leading-tight mb-5">
                Vos tickets de caisse,<br class="hidden md:block">
                transformés en dépenses claires.
            </h1>
            <p class="text-base md:text-lg max-w-xl mx-auto mb-8" style="color: var(--color-ink-soft);">
                Collez le texte d'un reçu fournisseur — même griffonné, même en darija —
                et l'IA en extrait automatiquement chaque article, sa quantité, son prix
                et sa catégorie.
            </p>
            <div class="flex items-center justify-center gap-4">
                @guest
                    <a href="{{ route('register') }}" class="btn-ink">Créer un compte gratuit</a>
                    <a href="{{ route('login') }}" class="btn-ghost">J'ai déjà un compte →</a>
                @else
                    <a href="{{ route('Receipt.create') }}" class="btn-ink">Nouveau reçu</a>
                    <a href="{{ route('Receipt.index') }}" class="btn-ghost">Voir mes reçus →</a>
                @endguest
            </div>
        </header>

        {{-- Before / After demo --}}
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="grid md:grid-cols-[1fr_auto_1fr] gap-6 items-stretch">
                <div class="ticket">
                    <p class="text-xs tracking-widest uppercase mb-3" style="font-family: var(--font-mono); color: var(--color-red);">Avant</p>
                    <div class="lined-paper text-xs px-3 py-1 rounded-sm border" style="border-color: var(--color-line); color: var(--color-ink-soft);">
                        coca 12 bx 6.50<br>
                        pain mie 2x 8dh<br>
                        javel 5 bts 4.20<br>
                        lben 20l 3.50<br>
                        chips bimo 24 2.80
                    </div>
                    <p class="text-xs mt-3" style="color: var(--color-ink-soft);">
                        Du texte brut, sans structure, sans catégories.
                    </p>
                </div>

                <div class="hidden md:flex items-center justify-center py-2" style="color: var(--color-ink-soft);">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                        <path d="M4 12h15M13 5l7 7-7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="flex md:hidden items-center justify-center py-2" style="color: var(--color-ink-soft);">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                        <path d="M12 4v15M5 13l7 7 7-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <div class="ticket">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs tracking-widest uppercase" style="font-family: var(--font-mono); color: var(--color-green);">Après</p>
                        <span class="stamp stamp-processed stamp-rotate" style="font-size:.6rem">Traité</span>
                    </div>
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between text-sm">
                            <span>Coca-Cola <span class="text-xs" style="font-family: var(--font-mono); color: var(--color-ink-soft);">×12</span></span>
                            <span class="stamp stamp-drink" style="font-size:.55rem">Boissons</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span>Pain de mie <span class="text-xs" style="font-family: var(--font-mono); color: var(--color-ink-soft);">×2</span></span>
                            <span class="stamp stamp-food" style="font-size:.55rem">Alimentaire</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span>Javel <span class="text-xs" style="font-family: var(--font-mono); color: var(--color-ink-soft);">×5</span></span>
                            <span class="stamp stamp-cleaning" style="font-size:.55rem">Entretien</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span>Lben <span class="text-xs" style="font-family: var(--font-mono); color: var(--color-ink-soft);">×20</span></span>
                            <span class="stamp stamp-drink" style="font-size:.55rem">Boissons</span>
                        </div>
                    </div>
                    <div class="divider-dashed mt-4 pt-3 flex items-center justify-between">
                        <p class="font-display font-semibold text-sm">Total</p>
                        <p class="font-semibold" style="font-family: var(--font-mono);">230.00 MAD</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- How it works --}}
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <h2 class="font-display font-semibold text-2xl text-center mb-10">Comment ça marche</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="ticket ticket-hover">
                    <p class="font-display font-semibold text-3xl mb-3" style="color: var(--color-line);">01</p>
                    <p class="font-display text-base mb-2">Collez le reçu</p>
                    <p class="text-sm" style="color: var(--color-ink-soft);">
                        Copiez le texte d'un reçu fournisseur tel qu'il est —
                        abréviations, darija, écriture désordonnée. Aucun formatage requis.
                    </p>
                </div>
                <div class="ticket ticket-hover">
                    <p class="font-display font-semibold text-3xl mb-3" style="color: var(--color-line);">02</p>
                    <p class="font-display text-base mb-2">L'IA classe tout</p>
                    <p class="text-sm" style="color: var(--color-ink-soft);">
                        En arrière-plan, l'IA identifie chaque article, sa quantité,
                        son prix et sa catégorie — sans bloquer votre page.
                    </p>
                </div>
                <div class="ticket ticket-hover">
                    <p class="font-display font-semibold text-3xl mb-3" style="color: var(--color-line);">03</p>
                    <p class="font-display text-base mb-2">Suivez vos dépenses</p>
                    <p class="text-sm" style="color: var(--color-ink-soft);">
                        Consultez vos reçus traités et filtrez vos dépenses par
                        catégorie : alimentaire, boissons, hygiène, entretien.
                    </p>
                </div>
            </div>
        </section>

        {{-- Final CTA --}}
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="ticket text-center py-12">
                <p class="font-display font-semibold text-2xl mb-3">
                    Arrêtez de deviner où part votre argent.
                </p>
                <p class="text-sm mb-7" style="color: var(--color-ink-soft);">
                    Gratuit. Vos reçus restent privés et liés à votre compte.
                </p>
                @guest
                    <a href="{{ route('register') }}" class="btn-ink">Créer un compte gratuit</a>
                @else
                    <a href="{{ route('Receipt.create') }}" class="btn-ink">Commencer maintenant</a>
                @endguest
            </div>
        </section>

        {{-- Footer --}}
        <footer class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-10 text-center">
            <p class="text-xs" style="font-family: var(--font-mono); color: var(--color-ink-soft);">
                Assistant Dépenses — un registre numérique pour commerçants de quartier
            </p>
        </footer>
    </div>
</body>
</html>
