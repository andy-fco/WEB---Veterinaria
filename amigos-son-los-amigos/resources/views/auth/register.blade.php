<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!--nombre y apellido-->

        <div class=" ">
            <x-input-label for="nombre" :value="__('Nombre')" />
            <x-text-input id="nombre" class="col-10 p-2 mx-auto form-control" type="text" name="nombre"
                :value="old('nombre')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="apellido" :value="__('Apellido')" />
            <x-text-input id="apellido" class="col-10 p-2 mx-auto form-control" type="text" name="apellido"
                :value="old('apellido')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('apellido')" class="mt-2" />
        </div>



        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="col-10 p-2 mx-auto form-control" type="email" name="email"
                :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <!-- telefono -->
        <div class="mt-4">
            <x-input-label for="telefono" :value="__('Teléfono')" />
            <x-text-input id="telefono" class="col-10 p-2 mx-auto form-control" type="text" name="telefono"
                :value="old('telefono')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
        </div>

        <!-- direccion -->
        <div class="mt-4">
            <x-input-label for="direccion" :value="__('Dirección')" />
            <x-text-input id="direccion" class="col-10 p-2 mx-auto form-control" type="text" name="direccion"
                :value="old('direccion')" required autocomplete="address-line1" />
            <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
        </div>

        <!-- username -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Usuario')" />
            <x-text-input id="name" class="col-10 p-2 mx-auto form-control" type="text" name="name" :value="old('name')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="col-10 p-2 mx-auto form-control" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirme contraseña')" />

            <x-text-input id="password_confirmation" class="col-10 p-2 mx-auto form-control" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4 text-end">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('¿Ya tenés una cuenta?') }}
            </a>

            <x-primary-button class="btn btn-v text-light">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>