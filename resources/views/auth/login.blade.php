<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
</head>
<body>
<section class="flex justify-center items-center h-screen bg-gray-800">
    <div class="max-w-md w-full bg-gray-900 rounded p-6 space-y-4">
        <div class="mb-4">
            <p class="text-gray-400">Sign In</p>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="nim">Nim</label>
                <input class="w-full p-4 text-sm bg-gray-50 focus:outline-none border border-gray-200 rounded text-gray-600" id="nim" type="nim" name="nim" value="{{ old('nim') }}" required autofocus>
                @error('nim')
                    <span role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password">Password</label>
                <input class="w-full p-4 text-sm bg-gray-50 focus:outline-none border border-gray-200 rounded text-gray-600" id="password" type="password" name="password" required>
                @error('password')
                    <span role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Remember Me</label>
            </div>

            <div>
                <button class="w-full py-4 bg-blue-600 hover:bg-blue-700 rounded text-sm font-bold text-gray-50 transition duration-200" type="submit">Login</button>
            </div>
        </form>
        
    </div>
</section>
</body>
</html>