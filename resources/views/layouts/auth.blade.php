<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Acceso') — MasaMadre SENA</title>
  <link rel="alternate icon" type="image/png" href="{{ asset('image/logo.png') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @vite('resources/css/auth.css')
</head>
<body class="h-full overflow-hidden" style="background:#1A0D08">

{{-- ══════════════════════════════════════════
     LAYOUT: dos columnas — foto | formulario
═══════════════════════════════════════════ --}}
<div class="flex h-full min-h-screen">

  {{-- ── PANEL IZQUIERDO: foto + contenido ── --}}
  <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden flex-col justify-between"
       style="background:#1A0D08">

    {{-- Foto de fondo con zoom suave --}}
    <div class="absolute inset-0 auth-photo-bg"></div>

    {{-- Overlays --}}
    <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(26,13,8,.72) 0%,rgba(61,35,20,.42) 50%,rgba(26,13,8,.82) 100%);z-index:1"></div>
    <div class="absolute inset-0" style="background:radial-gradient(ellipse at 30% 50%,transparent 30%,rgba(10,5,2,.6) 100%);z-index:2"></div>
    {{-- Fade hacia el panel del form --}}
    <div class="absolute top-0 right-0 bottom-0 w-1/3" style="background:linear-gradient(to right,transparent,#1A0D08);z-index:3"></div>

    {{-- Contenido --}}
    <div class="relative z-10 flex flex-col justify-between h-full p-10 xl:p-14">

      {{-- Logo --}}
      <a href="{{ route('home') }}" class="flex items-center gap-3 w-fit">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg"
             style="background:var(--color-trigo)">
            <img src="{{ asset('image/logo1.png') }}" alt="Logo MasaMadre" class="logo-light" />
        </div>
        <span class="font-display text-lg font-bold" style="color:var(--color-trigo-light)">
          Masa<em class="italic" style="color:var(--color-trigo)">Madre</em>
        </span>
      </a>

      {{-- Cita central --}}
      <div class="max-w-sm">
        <div class="flex items-center gap-3 mb-5">
          <div class="h-px w-6" style="background:var(--color-trigo);opacity:.5"></div>
          <span class="text-xs tracking-widest uppercase font-mono" style="color:var(--color-trigo);opacity:.65">
            Programa SENA · Colombia
          </span>
        </div>
        <h2 class="font-display font-bold leading-tight mb-4"
            style="font-size:clamp(1.8rem,2.4vw,2.6rem);color:var(--color-masa)">
          El pan de mañana<br>empieza con datos<br>
          <em class="italic" style="color:var(--color-trigo)">de hoy</em>
        </h2>
        <p class="text-sm leading-relaxed" style="color:var(--color-masa-dark);opacity:.5">
          Registra temperatura, pH y tiempos de fermentación.<br>
          El sistema guía cada paso del proceso artesanal.
        </p>
      </div>

      {{-- Parte inferior: miniaturas + stats --}}
      <div>
        {{-- Miniaturas --}}
        <div class="flex gap-2 mb-5">
          @foreach([
            'https://images.unsplash.com/photo-1568254183919-78a4f43a2877?w=200&q=75',
            'https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=200&q=75',
            'https://images.unsplash.com/photo-1517433670267-08bbd4be890f?w=200&q=75',
          ] as $thumb)
          <div class="rounded-xl overflow-hidden shrink-0 border"
               style="width:72px;height:54px;border-color:rgba(200,169,110,.18)">
            <img src="{{ $thumb }}" alt="Proceso masa madre"
                 class="w-full h-full object-cover"
                 style="filter:brightness(.75) saturate(.8)">
          </div>
          @endforeach
        </div>

        {{-- Stats --}}
        <div class="flex gap-6">
          @foreach([['5','Días de proceso'],['30+','Panaderías activas'],['pH','Control en tiempo real']] as $stat)
          <div>
            <div class="font-display font-bold leading-none mb-1"
                 style="font-size:1.5rem;color:var(--color-trigo)">{{ $stat[0] }}</div>
            <div class="text-xs tracking-wide uppercase font-mono"
                 style="color:var(--color-masa);opacity:.3">{{ $stat[1] }}</div>
          </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>

  {{-- ── PANEL DERECHO: formulario ── --}}
  <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-10 overflow-y-auto"
       style="background:#1A0D08;position:relative">

    {{-- Resplandor cálido detrás del form --}}
    <div class="absolute pointer-events-none"
         style="top:50%;left:50%;transform:translate(-50%,-50%);
                width:420px;height:420px;
                background:radial-gradient(ellipse,rgba(200,169,110,.055) 0%,transparent 70%)">
    </div>

    {{-- Logo móvil --}}
    <div class="absolute top-6 left-6 flex lg:hidden items-center gap-2">
      <div class="w-8 h-8 rounded-lg flex items-center justify-center"
           style="background:var(--color-trigo)">
       <img src="{{ asset('image/logo1.png') }}" alt="Logo MasaMadre" class="logo-light" />
      </div>
      <span class="font-display text-sm font-bold" style="color:var(--color-trigo-light)">MasaMadre</span>
    </div>

    {{-- Caja del form --}}
    <div class="w-full max-w-sm relative z-10 auth-fadein">
      @yield('form')
    </div>

  </div>
</div>

</body>
</html>