{{-- Right panel: login form --}}
<div class="w-full md:w-2/3 md:min-h-screen flex items-center justify-center bg-white">
<div class="w-full max-w-lg px-6 md:px-2 py-10 md:py-0">

        {{-- Heading --}}
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Masuk ke Akun Anda</h1>
        {{-- Error flash --}}
        @if (session('error'))
            <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Username --}}
            <x-ui.input
                id="username"
                type="text"
                name="username"
                label="Username"
                :value="old('username')"
                autocomplete="username"
                class="font-bold"
                required
            />

            {{-- Password --}}
            <x-ui.input
                id="password"
                type="password"
                name="password"
                label="Password"
                autocomplete="current-password"
                class="mb-6 font-bold"
                required
            >
                {{-- <x-slot:hint>
                    <a href="#" class="text-sm text-[#00A3FF] font-bold hover:underline">Lupa Password?</a>
                </x-slot:hint> --}}
            </x-ui.input>

            {{-- Submit --}}
            <x-ui.button type="submit" variant="primary">
                Masuk
            </x-ui.button>
        </form>


    </div>
</div>