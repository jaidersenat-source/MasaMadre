<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <title>@yield('title', 'Sistema Masa Madre') — SENA</title>
  <link rel="alternate icon" type="image/png" href="{{ asset('image/logo.png') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @vite('resources/css/layut.css')
</head>
<body class="app-body h-full">

<div class="app-shell" id="appShell">

  {{-- ── Backdrop móvil ── --}}
  <div id="sidebarBackdrop"
       class="sidebar-backdrop hidden lg:hidden"
       onclick="closeSidebar()"></div>

  {{-- ── SIDEBAR ── --}}
  <aside id="appSidebar" class="app-sidebar">

    {{-- Logo --}}
    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('panaderia.dashboard') }}"
       class="sidebar-logo">
      <div class="sidebar-logo-icon">
        <img src="{{ asset('image/logo1.png') }}" alt="Logo MasaMadre"/>
      </div>
      <div class="sidebar-brand-wrap">
        <div class="sidebar-brand">
          Masa<em>Madre</em>
        </div>
        <div class="sidebar-tagline">SENA</div>
      </div>
      <button type="button" onclick="closeSidebar()" class="sidebar-close-btn lg:hidden">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </a>

    {{-- Nav --}}
    <nav class="sidebar-nav">

      @if(auth()->user()->isAdmin())
        <span class="app-nav-section">Principal</span>
        <a href="{{ route('admin.dashboard') }}"
           class="app-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
          </svg>
          Panel general
        </a>
        <a href="{{ route('admin.panaderias.index') }}"
           class="app-nav-link {{ request()->routeIs('admin.panaderias.*') ? 'is-active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
          </svg>
          Panaderías
        </a>
        <a href="{{ route('admin.registros.index') }}"
           class="app-nav-link {{ request()->routeIs('admin.registros.*') ? 'is-active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
          </svg>
          Registros
        </a>

        <span class="app-nav-section app-nav-section--spaced">Reportes</span>
        <a href="{{ route('admin.exportar.excel') }}" class="app-nav-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
            <line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
          </svg>
          Exportar Excel
        </a>
        <a href="{{ route('admin.exportar.pdf') }}" class="app-nav-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
          </svg>
          Exportar PDF
        </a>

      @else
        <span class="app-nav-section">Mi proceso</span>
        <a href="{{ route('panaderia.dashboard') }}"
           class="app-nav-link {{ request()->routeIs('panaderia.dashboard') ? 'is-active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
          </svg>
          Inicio
        </a>
        <a href="{{ route('panaderia.historial') }}"
           class="app-nav-link {{ request()->routeIs('panaderia.historial') ? 'is-active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
          Historial
        </a>
      @endif

    </nav>

    {{-- Usuario + logout --}}
    <div class="sidebar-user-zone">
      <div class="sidebar-user-row">
        <div class="sidebar-user-avatar">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="sidebar-user-info">
          <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
          <div class="sidebar-user-role">{{ auth()->user()->role }}</div>
        </div>
      </div>
      <a href="{{ route('perfil.show') }}"
         class="app-nav-link {{ request()->routeIs('perfil.*') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
        </svg>
        Mi perfil
      </a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="app-nav-link w-full text-left">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Cerrar sesión
        </button>
      </form>
    </div>

  </aside>

  {{-- ── ÁREA PRINCIPAL ── --}}
  <div class="app-main">

    {{-- Topbar --}}
    <header class="app-topbar">
      <div class="topbar-left">
        <button id="sidebarToggle" class="topbar-menu-btn lg:hidden" onclick="openSidebar()">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>
        <span class="topbar-breadcrumb">@yield('breadcrumb', 'Panel')</span>
      </div>
      <div class="topbar-right">
        <span class="topbar-date">{{ now()->locale('es')->isoFormat('D MMM YYYY') }}</span>
        <span class="topbar-role {{ auth()->user()->isAdmin() ? 'topbar-role--admin' : 'topbar-role--baker' }}">
          {{ auth()->user()->isAdmin() ? 'Coordinador' : 'Panadero' }}
        </span>
      </div>
    </header>

    {{-- Flash messages --}}
    @foreach([
      ['success', session('success'), 'flash flash--success', 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'],
      ['warning', session('warning'), 'flash flash--warning', 'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z'],
      ['error',   session('error'),   'flash flash--error',   'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z'],
      ['info',    session('info'),    'flash flash--info',    'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z'],
    ] as [$type, $msg, $cls, $icon])
      @if($msg)
        <div class="{{ $cls }}">
          <svg class="flash-icon" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="{{ $icon }}" clip-rule="evenodd"/>
          </svg>
          {{ $msg }}
        </div>
      @endif
    @endforeach

    {{-- Content --}}
    <main class="app-content">
      @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="app-footer">
      <span>MasaMadre · SENA</span>
      <span>{{ date('Y') }}</span>
    </footer>

  </div>
</div>


<script>
  function openSidebar() {
    document.getElementById('appSidebar').classList.add('sidebar-open');
    document.getElementById('sidebarBackdrop').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar() {
    document.getElementById('appSidebar').classList.remove('sidebar-open');
    document.getElementById('sidebarBackdrop').classList.add('hidden');
    document.body.style.overflow = '';
  }
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeSidebar();
  });
</script>

</body>
</html>