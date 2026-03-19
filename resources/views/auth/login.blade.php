@extends('layouts.auth')

@section('title', 'Iniciar sesión')

@section('form')

{{-- ── Encabezado ── --}}
<div class="mb-7">
  <div class="flex items-center gap-3 mb-4">
    <div class="h-px w-5" style="background:var(--color-trigo);opacity:.45"></div>
    <span class="text-xs tracking-widest uppercase font-mono" style="color:var(--color-trigo);opacity:.55">
      Acceso al sistema
    </span>
  </div>
  <h1 class="font-display font-black leading-tight mb-2"
      style="font-size:2.1rem;color:var(--color-masa)">
    Bienvenido<br>
    <em class="italic" style="color:var(--color-trigo)">de vuelta</em>
  </h1>
  <p class="text-sm" style="color:var(--color-masa);opacity:.35;line-height:1.6">
    Ingresa con tu correo y contraseña para continuar
  </p>
</div>

{{-- ── Selector de rol ── --}}
<div class="auth-role-grid" id="rolePills">
  <div class="auth-role-pill is-active" onclick="authSelectRole(this)">
    <div class="auth-role-icon baker">
      <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 2C8 2 4 5.5 4 9c0 2.4 1.2 4.5 3 5.7V17h10v-2.3c1.8-1.2 3-3.3 3-5.7 0-3.5-4-7-8-7zm-2 13h4v1a2 2 0 01-4 0v-1z"/>
      </svg>
    </div>
    <div class="auth-role-text">
      <strong>Panadero</strong>
      <small>Mi proceso</small>
    </div>
  </div>
  <div class="auth-role-pill" onclick="authSelectRole(this)">
    <div class="auth-role-icon coord">
      <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
      </svg>
    </div>
    <div class="auth-role-text">
      <strong>Coordinador</strong>
      <small>SENA · Admin</small>
    </div>
  </div>
</div>

{{-- ── Errores ── --}}
@if ($errors->any())
  <div class="auth-alert-error mb-5">
    <svg class="auth-alert-icon" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    <div class="auth-alert-body">
      @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
      @endforeach
    </div>
  </div>
@endif

{{-- ── Formulario ── --}}
<form method="POST" action="{{ route('login') }}" class="space-y-5" id="loginForm">
  @csrf

  {{-- Email --}}
  <div>
    <label for="email" class="auth-label">Correo electrónico</label>
    <input
      id="email"
      type="email"
      name="email"
      value="{{ old('email') }}"
      autocomplete="email"
      autofocus
      required
      placeholder="correo@panaderia.co"
      class="auth-input @error('email') is-error @enderror"
    />
  </div>

  {{-- Contraseña --}}
  <div>
    <label for="password" class="auth-label">Contraseña</label>
    <div class="relative">
      <input
        id="password"
        type="password"
        name="password"
        autocomplete="current-password"
        required
        placeholder="••••••••"
        class="auth-input auth-input--password"
      />
      <button type="button" class="auth-eye-btn" onclick="authTogglePassword()" title="Mostrar contraseña">
        <svg id="authEyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
          <circle cx="12" cy="12" r="3"/>
        </svg>
      </button>
    </div>
  </div>

  {{-- Recordar sesión --}}
  <div class="flex items-center gap-2.5">
    <input type="checkbox" name="remember" id="remember" class="auth-checkbox"/>
    <label for="remember" class="text-sm cursor-pointer select-none"
           style="color:var(--color-masa);opacity:.4">
      Recordar sesión
    </label>
  </div>

  {{-- Submit --}}
  <button type="submit" class="auth-btn-submit" id="authSubmitBtn">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
    </svg>
    <span id="authSubmitText">Ingresar al sistema</span>
  </button>

</form>

{{-- ── Pie ── --}}
<p class="text-center mt-7 font-mono text-xs uppercase tracking-widest"
   style="color:var(--color-masa);opacity:.18">
  Sistema de Control de Masa Madre · SENA {{ date('Y') }}
</p>

{{-- ── Scripts ── --}}
<script>
  // Toggle password visibility
  const _eyeOpen   = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
  const _eyeClosed = `<line x1="1" y1="1" x2="23" y2="23"/><path d="M10.73 5.08A10.43 10.43 0 0112 5c7 0 11 7 11 7a13.16 13.16 0 01-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 001 12s4 7 11 7a9.74 9.74 0 005.39-1.61"/><line x1="9.88" y1="9.88" x2="14.12" y2="14.12"/>`;

  function authTogglePassword() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('authEyeIcon');
    if (input.type === 'password') {
      input.type = 'text';
      icon.innerHTML = _eyeClosed;
    } else {
      input.type = 'password';
      icon.innerHTML = _eyeOpen;
    }
  }

  // Role pill selection
  function authSelectRole(el) {
    document.querySelectorAll('.auth-role-pill')
            .forEach(p => p.classList.remove('is-active'));
    el.classList.add('is-active');
  }

  // Loading state en submit
  document.getElementById('loginForm').addEventListener('submit', function() {
    const btn  = document.getElementById('authSubmitBtn');
    const text = document.getElementById('authSubmitText');
    btn.disabled = true;
    text.textContent = 'Verificando…';
  });
</script>

@endsection