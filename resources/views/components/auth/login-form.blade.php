{{-- Right panel: login form --}}
<div class="w-2/3 min-h-screen flex items-center justify-center bg-white">
    <div class="w-full max-w-lg px-2">

        {{-- Heading --}}
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Masuk ke Akun Anda</h1>
        <p class="text-sm text-[#B5B5C3] font-medium mb-7">
            Belum memiliki akun?
            <a href="#" class="text-[#00A3FF] font-medium hover:underline">Daftar di sini</a>
        </p>

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
                <x-slot:hint>
                    <a href="#" class="text-sm text-[#00A3FF] font-bold hover:underline">Lupa Password?</a>
                </x-slot:hint>
            </x-ui.input>

            {{-- Submit --}}
            <x-ui.button type="submit" variant="primary">
                Masuk
            </x-ui.button>
        </form>


    </div>
</div>