<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="col-12 p-0">
        <div class="login-card login-dark">
            <div>
                <div class="login-main">
                    <form class="theme-form" method="POST" action="{{ route('login') }}">
                        @csrf
                        <h3 class="font-semibold text-4xl text-center">Intra SMII</h3>
                        <p class="mt-10 text-center">Sign in to continue to Intra SMII.</p>
                        <div class="relative w-full mt-4">
                            <label for="input-label" class="block text-sm font-medium mb-2 text-gray-700"> NIK</label>
                            <input type="text" name="nik" id="input-label"
                                class="border-1 py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                placeholder="Masukan Nik">
                        </div>
                        <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                        <label class="font-medium block mb-1 mt-4 text-gray-700" for="password">
                            Password
                        </label>
                        <div class="relative w-full">
                            <input
                                class="border-1 rounded w-full py-3 px-3 leading-tight border-gray-300 bg-gray-100 focus:outline-none focus:border-indigo-700 focus:bg-white text-gray-700 pr-10 font-mono js-password"
                                id="password" type="password" name="password" autocomplete="off"
                                placeholder="*********" required>
                            <span onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 flex items-center px-3 cursor-pointer text-gray-700">
                                <i class="fa fa-eye" id="toggleEye"></i>
                            </span>

                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                        <div class="form-group mt-7">
                            <div class="checkbox p-0">
                                <div class="controls">
                                    <input type="checkbox" id="checkbox_1" value="single">
                                    <label for="checkbox_1">Remember Me</label>
                                <div class="help-block"></div></div>
                            </div>
                            <div class="checkbox p-0">

                                <a class="link text-blue-500" href="{{route('password.request')}}">Forgot password?</a>
                            </div>
                            <div class="text-end mt-6">
                                <button class="btn btn-primary btn-block rounded-md text-white w-full"
                                    type="submit">Sign in</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById('password');
            var toggleIcon = document.getElementById('toggleEye');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</x-guest-layout>
