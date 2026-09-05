<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        @fluxScripts
    </head>
    <body class="min-h-screen flex flex-col bg-white text-zinc-900 antialiased">
        {{-- Nav --}}
        <header class="sticky top-0 z-40 border-b border-zinc-200 bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3">
                <a href="{{ route('home') }}" class="text-lg font-bold tracking-tight text-zinc-900" wire:navigate>
                    Saren Grup
                </a>

                <nav class="hidden items-center gap-5 text-sm sm:flex">
                    <a href="{{ route('catalog') }}" wire:navigate class="text-zinc-500 transition hover:text-zinc-900">Katalog</a>
                    <a href="{{ route('home') }}#track" wire:navigate class="text-zinc-500 transition hover:text-zinc-900">Tracking</a>
                </nav>

                <div class="flex items-center gap-2 sm:gap-3">
                    <livewire:frontend.cart-drawer />

                    <a href="{{ route('catalog') }}" wire:navigate
                       class="hidden rounded-lg bg-zinc-900 px-3.5 py-2 text-xs font-bold text-white transition hover:bg-zinc-800 md:inline-block">
                        Quick Order
                    </a>

                    @auth
                        <flux:dropdown position="bottom" align="end">
                            <button type="button" class="flex items-center gap-2 rounded-full border border-zinc-200 bg-zinc-50 py-1 pl-1.5 pr-2.5 text-xs font-medium text-zinc-700 transition hover:bg-zinc-100 focus:outline-none">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                    size="xs"
                                />
                                <span class="max-w-[90px] truncate sm:max-w-[120px]">{{ auth()->user()->name }}</span>
                                <flux:icon.chevron-down class="size-3 text-zinc-400" />
                            </button>

                            <flux:menu>
                                <div class="flex items-center gap-2 px-2 py-1.5 text-start text-sm">
                                    <flux:avatar
                                        :name="auth()->user()->name"
                                        :initials="auth()->user()->initials()"
                                    />
                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <flux:heading class="truncate font-semibold">{{ auth()->user()->name }}</flux:heading>
                                        <flux:text class="truncate text-xs text-zinc-500">{{ auth()->user()->email }}</flux:text>
                                    </div>
                                </div>

                                <flux:menu.separator />

                                @if(auth()->user()->hasRole('super_admin'))
                                    <flux:menu.item href="/admin" icon="shield-check" wire:navigate>
                                        Admin Panel
                                    </flux:menu.item>
                                @endif

                                <flux:menu.item :href="route('dashboard')" icon="layout-grid" wire:navigate>
                                    Dashboard
                                </flux:menu.item>

                                <flux:menu.item :href="route('profile.edit')" icon="cog-6-tooth" wire:navigate>
                                    Profil & Pengaturan
                                </flux:menu.item>

                                <flux:menu.separator />

                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer text-red-600 hover:text-red-700">
                                        Keluar
                                    </flux:menu.item>
                                </form>
                            </flux:menu>
                        </flux:dropdown>
                    @else
                        <div class="flex items-center gap-1.5 border-l border-zinc-200 pl-2 sm:pl-3">
                            <a href="{{ route('login') }}" wire:navigate
                               class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-900">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" wire:navigate
                               class="rounded-lg border border-zinc-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-zinc-800 transition hover:bg-zinc-50 hover:border-zinc-300">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        {{-- Main --}}
        <main class="flex-1">
            <div class="mx-auto max-w-6xl px-4 py-6">
                {{ $slot }}
            </div>
        </main>

        {{-- Footer --}}
        <footer class="mt-auto border-t border-zinc-200 bg-zinc-50">
            <div class="mx-auto grid max-w-6xl gap-8 px-4 py-10 text-sm text-zinc-500 sm:grid-cols-2 lg:grid-cols-3">
                <div class="space-y-2">
                    <h3 class="text-base font-bold text-zinc-900">Saren Grup</h3>
                    <p>&copy; {{ date('Y') }} CV. Saren Grup. Sibang Kaja, Bali.</p>
                </div>
                <div class="space-y-2">
                    <a href="https://maps.google.com/?q=Jl+Raya+Rijasa+Sibang+Kaja" target="_blank" rel="noopener" class="block hover:text-zinc-900">Lokasi Workshop</a>
                    <p>Senin – Sabtu, 08.00 – 18.00 WITA</p>
                </div>
                <div class="space-y-2">
                    <a href="https://wa.me/6287860042888" target="_blank" rel="noopener" class="block hover:text-zinc-900">WhatsApp Support</a>
                    <a href="mailto:sarengrup@gmail.com" class="block hover:text-zinc-900">sarengrup@gmail.com</a>
                </div>
            </div>
        </footer>
    </body>
</html>