@extends('layouts.app')
@section('title', 'Mi perfil')
@section('breadcrumb', 'Mi perfil')

@section('content')

<link rel="stylesheet" href="{{ asset('css/perfil.css') }}">

{{-- Header --}}
<div class="mb-8">
    <h1 class="font-display text-3xl font-bold text-corteza">Mi perfil</h1>
    <p class="text-corteza/50 text-sm mt-1">Actualiza tus datos personales y contraseña</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- ── DATOS BÁSICOS ── --}}
    <div class="card p-6">
        <div class="flex items-center gap-2 mb-6">
            <div class="w-1 h-5 rounded-full bg-trigo"></div>
            <h2 class="font-semibold text-corteza">Datos personales</h2>
        </div>

        {{-- Avatar inicial --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl font-bold shrink-0 perfil-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <div class="font-semibold text-corteza">{{ $user->name }}</div>
                <div class="text-xs text-corteza/40 mt-0.5 capitalize font-mono tracking-wide">
                    {{ $user->role }}
                    @if($user->panaderia)
                        · {{ $user->panaderia->nombre }}
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="flex items-center gap-2 px-4 py-3 rounded-xl text-sm mb-5 alert-success">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('perfil.datos') }}" class="space-y-4">
            @csrf @method('PATCH')

            <div>
                <label for="name" class="label">Nombre completo</label>
                <input id="name" type="text" name="name"
                       value="{{ old('name', $user->name) }}"
                       class="input @error('name') border-red-400 @enderror"
                       required>
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="label">Correo electrónico</label>
                <input id="email" type="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       class="input @error('email') border-red-400 @enderror"
                       required>
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo solo lectura --}}
            <div>
                <label class="label">Rol</label>
                <input type="text" value="{{ ucfirst($user->role) }}"
                       class="input opacity-50 cursor-not-allowed" disabled>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-verde w-full justify-center">
                    Guardar datos
                </button>
            </div>
        </form>
    </div>

    {{-- ── CAMBIAR CONTRASEÑA ── --}}
    <div class="card p-6">
        <div class="flex items-center gap-2 mb-6">
            <div class="w-1 h-5 rounded-full accent-verde"></div>
            <h2 class="font-semibold text-corteza">Cambiar contraseña</h2>
        </div>

        @if(session('success_password'))
        <div class="flex items-center gap-2 px-4 py-3 rounded-xl text-sm mb-5 alert-success">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success_password') }}
        </div>
        @endif

        @if($errors->has('password_actual'))
        <div class="flex items-center gap-2 px-4 py-3 rounded-xl text-sm mb-5 alert-error">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ $errors->first('password_actual') }}
        </div>
        @endif

        <form method="POST" action="{{ route('perfil.password') }}" class="space-y-4">
            @csrf @method('PATCH')

            <div>
                <label for="password_actual" class="label">Contraseña actual</label>
                <div class="relative">
                          <input id="password_actual" type="password" name="password_actual"
                              placeholder="••••••••"
                              class="input has-eye @error('password_actual') border-red-400 @enderror"
                              required>
                    <button type="button" onclick="togglePass('password_actual', 'eye1')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-corteza/30 hover:text-corteza/60 transition-colors">
                        <svg id="eye1" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label for="password" class="label">Nueva contraseña</label>
                <div class="relative">
                          <input id="password" type="password" name="password"
                              placeholder="Mínimo 8 caracteres"
                              class="input has-eye @error('password') border-red-400 @enderror"
                              required>
                    <button type="button" onclick="togglePass('password', 'eye2')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-corteza/30 hover:text-corteza/60 transition-colors">
                        <svg id="eye2" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="label">Confirmar nueva contraseña</label>
                <div class="relative">
                          <input id="password_confirmation" type="password" name="password_confirmation"
                              placeholder="••••••••"
                              class="input has-eye" required>
                    <button type="button" onclick="togglePass('password_confirmation', 'eye3')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-corteza/30 hover:text-corteza/60 transition-colors">
                        <svg id="eye3" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-secondary w-full justify-center">
                    Actualizar contraseña
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    const eyeOpen   = `<path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
    const eyeClosed = `<line x1="1" y1="1" x2="23" y2="23"/><path stroke-linecap="round" stroke-linejoin="round" d="M10.73 5.08A10.43 10.43 0 0112 5c7 0 11 7 11 7a13.16 13.16 0 01-1.67 2.68M6.61 6.61A13.526 13.526 0 001 12s4 7 11 7a9.74 9.74 0 005.39-1.61"/><line x1="9.88" y1="9.88" x2="14.12" y2="14.12"/>`;

    function togglePass(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        input.type  = input.type === 'password' ? 'text' : 'password';
        icon.innerHTML = input.type === 'text' ? eyeClosed : eyeOpen;
    }
</script>

@endsection
