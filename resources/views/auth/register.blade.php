<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <div class="flex justify-center mb-8">
                <x-application-logo class="w-40 h-20 fill-current" />
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Nome')" class="text-gray-300" />
                    <x-text-input id="name" class="block mt-1 w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" class="text-gray-300" />
                    <x-text-input id="email" class="block mt-1 w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Role Selection -->
                <div class="mt-4">
                    <x-input-label for="role" :value="__('Tipo de Usuário')" class="text-gray-300" />
                    <select id="role" name="role" class="block mt-1 w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="student">Aluno</option>
                        <option value="teacher">Professor</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Senha')" class="text-gray-300" />

                    <x-text-input id="password" class="block mt-1 w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" class="text-gray-300" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-300 hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-green-500" href="{{ route('login') }}">
                        {{ __('Já possui cadastro?') }}
                    </a>

                    <x-primary-button class="ml-4">
                        {{ __('Registrar') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
