@extends('layouts.app')
@section('title', "Día $dia — Masa Madre")

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-8">
        <a href="{{ route('panaderia.proceso.show', $proceso->id) }}"
           class="inline-flex items-center gap-1.5 text-sm text-corteza/50 hover:text-corteza mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Ver proceso
        </a>

        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 rounded-2xl bg-trigo flex items-center justify-center font-display font-bold text-corteza-dark text-lg">
                {{ $dia }}
            </div>
            <div>
                <h1 class="font-display text-3xl font-bold text-corteza">Día {{ $dia }}</h1>
                <p class="text-corteza/50 text-sm">Masa madre — Proceso #{{ $proceso->id }}</p>
            </div>
        </div>
    </div>

        <form method="POST"
            action="{{ route('panaderia.proceso.dia.store', [$proceso->id, $dia]) }}"
            class="space-y-6"
            enctype="multipart/form-data">
        @csrf

        {{-- Harinas --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-4">Harinas</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Harina de trigo (%)</label>
                    <input type="number" name="pct_harina_trigo" step="1" min="0" max="100"
                           value="{{ old('pct_harina_trigo', 100) }}"
                           class="input @error('pct_harina_trigo') border-red-400 @enderror">
                    @error('pct_harina_trigo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Otras harinas (si aplica)</label>
                    <input type="text" name="otras_harinas"
                           value="{{ old('otras_harinas', 'NA') }}"
                           placeholder="Ej: CENTENO 30, INTEGRAL 20"
                           class="input @error('otras_harinas') border-red-400 @enderror">
                    <p class="text-xs text-corteza/40 mt-1">Escribe NA si no aplica</p>
                </div>
            </div>
            <div class="mt-4">
                <label class="label">Porcentaje de agua (%)</label>
                <input type="number" name="pct_agua" step="1" min="0" max="200"
                       value="{{ old('pct_agua', 100) }}"
                       class="input max-w-xs @error('pct_agua') border-red-400 @enderror">
                @error('pct_agua') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Temperaturas --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-4">Temperaturas</h2>
            <div class="grid grid-cols-3 gap-4">
                @foreach([
                    ['temp_agua',     'Agua (°C)',     '28'],
                    ['temp_ambiente', 'Ambiente (°C)', '25'],
                    ['temp_mezcla',   'Mezcla (°C)',   '27'],
                ] as [$name, $label, $placeholder])
                <div>
                    <label class="label">{{ $label }}</label>
                    <input type="number" name="{{ $name }}" step="0.1"
                           value="{{ old($name) }}"
                           placeholder="{{ $placeholder }}"
                           class="input @error($name) border-red-400 @enderror">
                    @error($name) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endforeach
            </div>
        </div>

        {{-- pH y maduración --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-4">Control de fermentación</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">pH inicial (después de alimentar)</label>
                    <input type="number" name="ph_inicial" step="0.01" min="1" max="14"
                           value="{{ old('ph_inicial') }}"
                           placeholder="Ej: 5.9"
                           class="input @error('ph_inicial') border-red-400 @enderror">
                    @if($dia >= 3)
                        <p class="text-xs text-amber-600 mt-1">Día {{ $dia }}: el pH debería ir bajando (ideal &lt; 4.5)</p>
                    @endif
                    @error('ph_inicial') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Tiempo de maduración (horas)</label>
                    <input type="number" name="tiempo_maduracion_horas" min="1" max="72"
                           value="{{ old('tiempo_maduracion_horas', 24) }}"
                           class="input @error('tiempo_maduracion_horas') border-red-400 @enderror">
                    @error('tiempo_maduracion_horas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Observaciones y responsable --}}
        <div class="card p-6">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="label">Observaciones</label>
                    <textarea name="observaciones" rows="3"
                              placeholder="Ej: BURBUJAS, AROMA LÁCTICO — escribe NA si no hay observaciones"
                              class="input resize-none">{{ old('observaciones') }}</textarea>
                </div>
                <div>
                    <label class="label">Responsable</label>
                    <input type="text" name="responsable"
                           value="{{ old('responsable') }}"
                           placeholder="Iniciales o nombre del responsable"
                           class="input @error('responsable') border-red-400 @enderror">
                    @error('responsable') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        @if(in_array($dia, [1,3,5]))
<div class="card p-6">
    <div class="flex items-center justify-between mb-1">
        <h2 class="font-semibold text-corteza">Fotos del proceso</h2>
        <span class="text-xs font-medium bg-trigo/60 text-corteza-dark px-2 py-0.5 rounded-full">Día {{ $dia }}</span>
    </div>
    <p class="text-sm text-corteza/50 mb-5">Mínimo 3 · máximo 6 fotos · JPG o PNG · hasta 5 MB c/u</p>

    {{-- Zona de drop --}}
    <label id="dropZone"
           for="fotos_proceso"
           class="group relative flex flex-col items-center justify-center gap-3 w-full min-h-[160px]
                  border-2 border-dashed border-trigo hover:border-corteza/40
                  rounded-2xl cursor-pointer transition-all duration-200
                  bg-trigo/10 hover:bg-trigo/20">

        {{-- Icono cámara --}}
        <div class="w-12 h-12 rounded-xl bg-trigo/60 flex items-center justify-center
                    group-hover:scale-110 transition-transform duration-200">
            <svg class="w-6 h-6 text-corteza/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>

        <div class="text-center">
            <p class="text-sm font-medium text-corteza">Arrastra fotos aquí</p>
            <p class="text-xs text-corteza/40 mt-0.5">o haz clic para seleccionar</p>
        </div>

        <input type="file" id="fotos_proceso" name="fotos_proceso[]"
               multiple accept="image/*" class="sr-only" />
    </label>

    {{-- Errores --}}
    @error('fotos_proceso') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
    @error('fotos_proceso.*') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror

    {{-- Contador + previews --}}
    <div class="mt-4 space-y-3">
        <p id="fotosHelp" class="text-xs text-corteza/40">0 archivos seleccionados</p>

        {{-- Grid de previews --}}
        <div id="fotosPreview" class="grid grid-cols-3 gap-2 hidden"></div>
    </div>
</div>
@endif

        <div class="flex justify-end">
            <button type="submit" class="btn-verde">
                {{ $dia < 5 ? "Guardar y continuar al Día " . ($dia + 1) : 'Guardar Día 5' }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </form>
</div>

@section('scripts')
@if(in_array($dia, [1,3,5]))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input    = document.getElementById('fotos_proceso');
    const help     = document.getElementById('fotosHelp');
    const preview  = document.getElementById('fotosPreview');
    const dropZone = document.getElementById('dropZone');
    const form     = input?.closest('form');
    if (!input || !help || !form) return;

    // Drag & drop visual feedback
    ['dragenter','dragover'].forEach(ev =>
        dropZone.addEventListener(ev, e => {
            e.preventDefault();
            dropZone.classList.add('border-corteza/60', 'bg-trigo/30');
        })
    );
    ['dragleave','drop'].forEach(ev =>
        dropZone.addEventListener(ev, e => {
            e.preventDefault();
            dropZone.classList.remove('border-corteza/60', 'bg-trigo/30');
        })
    );
    dropZone.addEventListener('drop', e => {
        const dt = new DataTransfer();
        Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
        input.files = dt.files;
        renderPreview();
    });

    input.addEventListener('change', renderPreview);

    function renderPreview() {
        const files = Array.from(input.files);
        const n = files.length;

        // Contador con color según validez
        help.textContent = `${n} archivo${n !== 1 ? 's' : ''} seleccionado${n !== 1 ? 's' : ''}`;
        help.className = n >= 3 && n <= 6
            ? 'text-xs text-green-600 font-medium'
            : 'text-xs text-red-500 font-medium';

        // Previews
        preview.innerHTML = '';
        if (n === 0) { preview.classList.add('hidden'); return; }
        preview.classList.remove('hidden');

        files.forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrap = document.createElement('div');
                wrap.className = 'relative aspect-square rounded-xl overflow-hidden bg-trigo/20 ring-1 ring-corteza/10';
                wrap.innerHTML = `
                    <img src="${e.target.result}"
                         class="w-full h-full object-cover"
                         alt="Foto ${i+1}">
                    <span class="absolute bottom-1 right-1 bg-corteza/70 text-white text-[10px] rounded px-1">${i+1}</span>
                `;
                preview.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });
    }

    form.addEventListener('submit', function (e) {
        const n = input.files.length;
        if (n < 3) {
            e.preventDefault();
            alert('Debes seleccionar al menos 3 fotos para el Día {{ $dia }}.');
        } else if (n > 6) {
            e.preventDefault();
            alert('Puedes subir como máximo 6 fotos.');
        }
    });
});
</script>
@endif
@endsection
@endsection