<header
    class="fixed top-0 z-50 w-full bg-foreground backdrop-blur supports-[backdrop-filter]:bg-foreground/85 dark:bg-background supports-[backdrop-filter]:dark:bg-background/85"
>
    <nav x-data="{ open: false }">
        <!-- Primary Navigation Menu -->
        <div class="container flex h-16 justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-background dark:fill-foreground" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link
                        :href="route('rooms')"
                        :active="request()->routeIs('rooms')"
                    >
                        Our Rooms
                    </x-nav-link>
                    <x-nav-link
                        :href="route('onsen')"
                        :active="request()->routeIs('onsen')"
                    >
                        Riva Waree Onsen
                    </x-nav-link>
                    <x-nav-link
                        :href="route('facilities')"
                        :active="request()->routeIs('facilities')"
                    >
                        Facilities
                    </x-nav-link>
                    <x-nav-link
                        :href="route('memberships')"
                        :active="request()->routeIs('memberships')"
                    >
                        Memberships
                    </x-nav-link>
                    <x-nav-link
                        :href="route('faq')"
                        :active="request()->routeIs('faq')"
                    >
                        FAQ
                    </x-nav-link>
                    <x-nav-link
                        :href="route('about')"
                        :active="request()->routeIs('about')"
                    >
                        About
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown or Login Button -->
            @auth
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex sm:items-center">
                    <x-dropdown
                        align="right"
                        width="48"
                    >
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300"
                            >
                                <!-- TODO: replace with avatar -->
                                <div>{{ auth()->user()->name }}</div>

                                <div class="ms-1">
                                    <svg
                                        class="h-4 w-4 fill-current"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profile
                            </x-dropdown-link>
                            <x-dropdown-link href="/admin">
                                Admin
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                            >
                                @csrf

                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                >
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('login')">
                        Log In
                    </x-nav-link>
                </div>
            @endauth

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button
                    @click="open = ! open"
                    aria-label="Toggle navigation"
                    type="button"
                    class="inline-flex items-center justify-center rounded-md p-2 text-background/60 transition duration-150 ease-in-out hover:text-background/80 focus:text-background/80 focus:outline-none focus:ring-2 focus:ring-background/80 dark:text-foreground/60 dark:hover:text-foreground/80 dark:focus:text-foreground/80 dark:focus:ring-foreground/80"
                >
                    <svg
                        class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
            :class="{ 'block': open, 'hidden': !open }"
            class="hidden sm:hidden"
        >
            <div class="space-y-1 pb-3 pt-2">
                <x-responsive-nav-link
                    :href="route('rooms')"
                    :active="request()->routeIs('rooms')"
                >
                    Our Rooms
                </x-responsive-nav-link>
                <x-responsive-nav-link
                    :href="route('onsen')"
                    :active="request()->routeIs('onsen')"
                >
                    Riva Waree Onsen
                </x-responsive-nav-link>
                <x-responsive-nav-link
                    :href="route('facilities')"
                    :active="request()->routeIs('facilities')"
                >
                    Facilities
                </x-responsive-nav-link>
                <x-responsive-nav-link
                    :href="route('memberships')"
                    :active="request()->routeIs('memberships')"
                >
                    Memberships
                </x-responsive-nav-link>
                <x-responsive-nav-link
                    :href="route('faq')"
                    :active="request()->routeIs('faq')"
                >
                    FAQ
                </x-responsive-nav-link>
                <x-responsive-nav-link
                    :href="route('about')"
                    :active="request()->routeIs('about')"
                >
                    About
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            @auth
                <div class="border-t border-border pb-1 pt-4">
                    <div class="px-4 font-medium">
                        <div class="text-background dark:text-foreground">{{ auth()->user()->name }}
                        </div>
                        <div class="text-sm text-background/60 dark:text-foreground/60">
                            {{ auth()->user()->email }}
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            Profile
                        </x-responsive-nav-link>

                        <!-- Authentication -->
                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >
                            @csrf

                            <x-responsive-nav-link
                                :href="route('logout')"
                                onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            >
                                Log Out
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            @else
                <div class="border-t border-border pb-1 pt-4">
                    <x-responsive-nav-link :href="route('login')">
                        Log In
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </nav>
</header>
