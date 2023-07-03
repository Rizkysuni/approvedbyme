<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
</head>
<body>
    
  <h1 class="text-3xl font-bold underline">
    Hello {{ auth()->user()->name }}!
  </h1>

  <!-- Example: logout link -->
  <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      Logout
  </a>

  <!-- Example: logout form -->
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
  </form>
</body>
</html>