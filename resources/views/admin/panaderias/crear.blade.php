@extends('layouts.app')
@section('title', 'Nueva panadería')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-8">
        <a href="{{ route('admin.panaderias.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-corteza/50 hover:text-corteza mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al listado
        </a>
        <h1 class="font-display text-3xl font-bold text-corteza">Nueva panadería</h1>
        <p class="text-corteza/50 text-sm mt-1">Se creará la panadería y su usuario de acceso</p>
    </div>

    @if($errors->any())
        <div class="alert-error mb-6">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.panaderias.store') }}" class="space-y-6">
        @csrf

        {{-- Datos panadería --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-4 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-trigo text-corteza-dark text-xs font-bold flex items-center justify-center">1</span>
                Datos de la panadería
            </h2>

            <div class="space-y-4">
                <div>
                    <label class="label">Nombre de la panadería</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                           placeholder="PANADERÍA EJEMPLO"
                           class="input @error('nombre') border-red-400 @enderror">
                    @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Ciudad / Municipio</label>
                        <input type="text" name="ciudad" value="{{ old('ciudad') }}"
                               placeholder="NEIVA"
                               class="input @error('ciudad') border-red-400 @enderror">
                        @error('ciudad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label">Dirección</label>
                        <input type="text" name="direccion" value="{{ old('direccion') }}"
                               placeholder="CALLE 16 SUR # 17-21"
                               class="input @error('direccion') border-red-400 @enderror">
                        @error('direccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="label">Regional</label>
                    <select name="regional"
                            class="input @error('regional') border-red-400 @enderror">
                        <option value="">Selecciona una regional</option>
                        @foreach($regionales as $r)
                            <option value="{{ $r }}" {{ old('regional') === $r ? 'selected' : '' }}>
                                {{ $r }}
                            </option>
                        @endforeach
                    </select>
                    @error('regional') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label">Centro de formación</label>
                    <input type="text" name="centro_formacion" value="{{ old('centro_formacion') }}"
                           placeholder="CENTRO DE LA INDUSTRIA, LA EMPRESA Y LOS SERVICIOS"
                           class="input @error('centro_formacion') border-red-400 @enderror">
                    @error('centro_formacion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label">Nombre del extensionista</label>
                    <input type="text" name="extensionista" value="{{ old('extensionista') }}"
                           placeholder="APELLIDO NOMBRE COMPLETO"
                           class="input @error('extensionista') border-red-400 @enderror">
                    @error('extensionista') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Datos de acceso --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-1 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-trigo text-corteza-dark text-xs font-bold flex items-center justify-center">2</span>
                Credenciales de acceso
            </h2>
            <p class="text-xs text-corteza/50 mb-4 ml-8">
                Con estos datos la panadería ingresará al sistema
            </p>

            <div class="space-y-4">
                <div>
                    <label class="label">Nombre del usuario</label>
                    <input type="text" name="user_name" value="{{ old('user_name') }}"
                           placeholder="Nombre completo o nombre de la panadería"
                           class="input @error('user_name') border-red-400 @enderror">
                    @error('user_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label">Correo electrónico</label>
                    <input type="email" name="user_email" value="{{ old('user_email') }}"
                           placeholder="correo@panaderia.co"
                           class="input @error('user_email') border-red-400 @enderror">
                    @error('user_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Contraseña</label>
                        <input type="password" name="user_password"
                               placeholder="Mínimo 8 caracteres"
                               class="input @error('user_password') border-red-400 @enderror">
                        @error('user_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label">Confirmar contraseña</label>
                        <input type="password" name="user_password_confirmation"
                               placeholder="Repetir contraseña"
                               class="input">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.panaderias.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-verde">
                Crear panadería y usuario
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </button>
        </div>
    </form>
</div>

@endsection