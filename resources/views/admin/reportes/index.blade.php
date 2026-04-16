@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-2xl font-semibold text-gray-900">Reportes y Exportaciones</h1>
    <p class="mt-1 text-sm text-gray-500">Descarga consolidada de todos los datos del programa Masa Madre SENA.</p>
  </div>

  <div class="flex gap-2 items-center">
    <a href="{{ route('admin.exportar.excel') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-md shadow-sm hover:opacity-95">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/><path d="M7 10l5 5 5-5"/></svg>
      Excel (todo)
    </a>
    <a href="{{ route('admin.exportar.pdf') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-md shadow-sm hover:opacity-95">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/><path d="M7 10l5 5 5-5"/></svg>
      PDF (todo)
    </a>
  </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
  <div class="col-span-1 sm:col-span-1 lg:col-span-1 bg-white shadow-sm rounded-lg p-4 border">
    <div class="text-sm text-gray-500">Panaderías</div>
    <div class="mt-2 text-2xl font-bold text-emerald-700">{{ $stats['panaderias'] }}</div>
  </div>
  <div class="col-span-1 sm:col-span-1 lg:col-span-1 bg-white shadow-sm rounded-lg p-4 border">
    <div class="text-sm text-gray-500">Procesos</div>
    <div class="mt-2 text-2xl font-bold text-gray-800">{{ $stats['procesos'] }}</div>
  </div>
  <div class="col-span-1 sm:col-span-1 lg:col-span-1 bg-white shadow-sm rounded-lg p-4 border">
    <div class="text-sm text-gray-500">Completados</div>
    <div class="mt-2 text-2xl font-bold text-indigo-600">{{ $stats['procesos_completados'] }}</div>
  </div>
  <div class="col-span-1 sm:col-span-1 lg:col-span-1 bg-white shadow-sm rounded-lg p-4 border">
    <div class="text-sm text-gray-500">Días MM</div>
    <div class="mt-2 text-2xl font-bold text-gray-800">{{ $stats['dias_masa_madre'] }}</div>
  </div>
  <div class="col-span-1 sm:col-span-1 lg:col-span-1 bg-white shadow-sm rounded-lg p-4 border">
    <div class="text-sm text-gray-500">Elaboraciones</div>
    <div class="mt-2 text-2xl font-bold text-gray-800">{{ $stats['elaboraciones_pan'] }}</div>
  </div>
  <div class="col-span-1 sm:col-span-1 lg:col-span-1 bg-white shadow-sm rounded-lg p-4 border">
    <div class="text-sm text-gray-500">Caracterizaciones</div>
    <div class="mt-2 text-2xl font-bold text-blue-600">{{ $stats['caracterizaciones'] }}</div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="bg-white rounded-lg shadow p-6 border">
    <div class="flex items-start justify-between">
      <div>
        <h3 class="text-lg font-semibold">Registros (Todas las panaderías)</h3>
        <p class="mt-1 text-sm text-gray-500">Libro Excel con 4 hojas: Resumen, Días MM, Elaboración Pan y Caracterización.</p>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('admin.exportar.excel') }}" class="px-3 py-2 bg-emerald-600 text-white rounded-md text-sm">Descargar Excel</a>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-lg shadow p-6 border">
    <div class="flex items-start justify-between">
      <div>
        <h3 class="text-lg font-semibold">Registros (PDF)</h3>
        <p class="mt-1 text-sm text-gray-500">Informe PDF con el detalle completo de todos los procesos registrados.</p>
      </div>
      <div>
        <a href="{{ route('admin.exportar.pdf') }}" class="px-3 py-2 bg-red-600 text-white rounded-md text-sm">Descargar PDF</a>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-lg shadow p-6 border">
    <div class="flex items-start justify-between">
      <div>
        <h3 class="text-lg font-semibold">Caracterización (51 preguntas)</h3>
        <p class="mt-1 text-sm text-gray-500">Exportación profesional en 3 hojas: identificación, detalle y expectativas/economía.</p>
      </div>
      <div>
        <a href="{{ route('admin.exportar.caracterizacion') }}" class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm">Descargar Caracterización</a>
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<style>
/* Modern card styles */
.border { border: 1px solid #e6eef2; }
.shadow-sm { box-shadow: 0 6px 18px rgba(14,30,37,0.04); }

/* Make buttons consistent */
a.btn, .btn-report { display: inline-flex; align-items:center; gap:.5rem }

/* Responsive tweaks */
@media (max-width: 640px) {
  .grid-cols-1 { grid-template-columns: 1fr; }
}
</style>
@endpush
