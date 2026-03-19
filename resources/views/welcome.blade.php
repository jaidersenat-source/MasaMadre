<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="alternate icon" type="image/png" href="{{ asset('image/logo.png') }}">
  <title>MasaMadre · SENA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
@vite('resources/css/welcome.css')
</head>
<body>

<!-- NAV -->
<nav>
  <a class="nav-logo" href="#">
    <div class="nav-logo-icon">
      <img src="{{ asset('image/logo1.png') }}" alt="Logo MasaMadre" style="height:30px;width:auto;display:block;object-fit:contain;" />
    </div>
    <div class="nav-brand">Masa<span>Madre</span></div>
  </a>
  <div class="nav-pill">SENA · Colombia</div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-left">
    <div class="hero-day-label anim-1">Sistema de Control · Masa Madre</div>
    <h1 class="hero-title anim-2">
      Del cuaderno<br>al<br>
      <em>panel digital</em>
      <span class="hero-title-small">Fermentación natural, tecnología precisa</span>
    </h1>
    <p class="hero-description anim-3">
      Digitaliza el proceso de 5 días de elaboración de masa madre. Registra pH, temperatura y tiempos de fermentación desde cualquier dispositivo.
    </p>
    <div class="hero-cta-group anim-4">
      <a href="{{ route('login') }}" class="btn-hero">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
        </svg>
        Iniciar sesión
      </a>
      <a href="#proceso" class="btn-ghost">
        Ver el proceso
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" stroke-linecap="round">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </a>
    </div>
  </div>

  <div class="hero-right">
    <div class="hero-visual-bg"></div>
    <div class="hero-visual-overlay"></div>
    <div class="hero-data-cards">
      <div class="data-card">
        <div class="data-card-label"><span class="dot dot-green"></span>pH Actual</div>
        <div class="data-card-value">4.2<span>pH</span></div>
        <div class="data-card-sub">Rango ideal: 3.8 – 4.5</div>
      </div>
      <div class="data-card">
        <div class="data-card-label"><span class="dot dot-amber"></span>Temperatura</div>
        <div class="data-card-value">24°<span>C</span></div>
        <div class="data-card-sub">Panadería El Trigal · Bogotá</div>
      </div>
      <div class="data-card">
        <div class="data-card-label"><span class="dot dot-green"></span>Día del proceso</div>
        <div class="data-card-value">3<span>/ 5</span></div>
        <div class="data-card-sub">Fermentación activa</div>
      </div>
    </div>

    <div class="ferm-ring">
      <svg viewBox="0 0 100 100">
        <circle class="ferm-ring-track" cx="50" cy="50" r="40"/>
        <circle class="ferm-ring-fill" cx="50" cy="50" r="40"/>
      </svg>
      <div class="ferm-ring-label">
        <span>60%</span>
        <span>listo</span>
      </div>
    </div>
  </div>

  <div class="scroll-hint">
    <div class="scroll-line"></div>
    <span>Explorar</span>
  </div>
</section>

<!-- FEATURES STRIP -->
<div class="features-strip">
  <div class="feature-item">
    <div class="feature-icon">
      <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
    </div>
    <div class="feature-text">
      <strong>5 días de registro</strong>
      <small>Formularios simples por jornada</small>
    </div>
  </div>
  <div class="feature-item">
    <div class="feature-icon">
      <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
    </div>
    <div class="feature-text">
      <strong>Alertas en tiempo real</strong>
      <small>Detecta rangos fuera de norma</small>
    </div>
  </div>
  <div class="feature-item">
    <div class="feature-icon">
      <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
    </div>
    <div class="feature-text">
      <strong>Reportes Excel / PDF</strong>
      <small>Exportación con un solo clic</small>
    </div>
  </div>
  <div class="feature-item">
    <div class="feature-icon">
      <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
    </div>
    <div class="feature-text">
      <strong>Múltiples panaderías</strong>
      <small>Vista consolidada por región</small>
    </div>
  </div>
</div>

<!-- DAYS TIMELINE -->
<section class="section-days" id="proceso">
  <div class="section-label">El proceso</div>
  <h2 class="section-title">Cinco días de<br><em>fermentación viva</em></h2>
  <p class="section-subtitle">Cada día del proceso tiene parámetros únicos. El sistema guía al panadero paso a paso y alerta cuando algo se desvía.</p>

  <div class="timeline">
    <div class="timeline-day active">
      <div class="timeline-dot"></div>
      <div class="timeline-card">
        <div class="timeline-day-num">Día 01</div>
        <div class="timeline-day-title">Inoculación</div>
        <div class="timeline-day-desc">Mezcla inicial de harina y agua. Se activan los microorganismos.</div>
        <div class="timeline-metrics">
          <div class="metric-row"><span class="metric-key">pH</span><span class="metric-val">6.0</span></div>
          <div class="metric-bar"><div class="metric-bar-fill" style="width:30%"></div></div>
          <div class="metric-row"><span class="metric-key">Temp</span><span class="metric-val">22°C</span></div>
        </div>
      </div>
    </div>
    <div class="timeline-day">
      <div class="timeline-dot"></div>
      <div class="timeline-card">
        <div class="timeline-day-num">Día 02</div>
        <div class="timeline-day-title">Primera acidez</div>
        <div class="timeline-day-desc">Inicio de producción ácida. Se observan burbujas pequeñas.</div>
        <div class="timeline-metrics">
          <div class="metric-row"><span class="metric-key">pH</span><span class="metric-val">5.2</span></div>
          <div class="metric-bar"><div class="metric-bar-fill" style="width:50%"></div></div>
          <div class="metric-row"><span class="metric-key">Temp</span><span class="metric-val">23°C</span></div>
        </div>
      </div>
    </div>
    <div class="timeline-day">
      <div class="timeline-dot"></div>
      <div class="timeline-card">
        <div class="timeline-day-num">Día 03</div>
        <div class="timeline-day-title">Fermentación activa</div>
        <div class="timeline-day-desc">Actividad visible. La masa dobla volumen en 8 h.</div>
        <div class="timeline-metrics">
          <div class="metric-row"><span class="metric-key">pH</span><span class="metric-val">4.6</span></div>
          <div class="metric-bar"><div class="metric-bar-fill" style="width:68%"></div></div>
          <div class="metric-row"><span class="metric-key">Temp</span><span class="metric-val">24°C</span></div>
        </div>
      </div>
    </div>
    <div class="timeline-day">
      <div class="timeline-dot"></div>
      <div class="timeline-card">
        <div class="timeline-day-num">Día 04</div>
        <div class="timeline-day-title">Maduración</div>
        <div class="timeline-day-desc">Equilibrio entre levaduras y bacterias lácticas.</div>
        <div class="timeline-metrics">
          <div class="metric-row"><span class="metric-key">pH</span><span class="metric-val">4.1</span></div>
          <div class="metric-bar"><div class="metric-bar-fill" style="width:82%"></div></div>
          <div class="metric-row"><span class="metric-key">Temp</span><span class="metric-val">24°C</span></div>
        </div>
      </div>
    </div>
    <div class="timeline-day">
      <div class="timeline-dot"></div>
      <div class="timeline-card">
        <div class="timeline-day-num">Día 05</div>
        <div class="timeline-day-title">Punto ideal</div>
        <div class="timeline-day-desc">pH estable, aroma pronunciado. Lista para hornear.</div>
        <div class="timeline-metrics">
          <div class="metric-row"><span class="metric-key">pH</span><span class="metric-val">3.9</span></div>
          <div class="metric-bar"><div class="metric-bar-fill" style="width:95%"></div></div>
          <div class="metric-row"><span class="metric-key">Temp</span><span class="metric-val">25°C</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PHOTO GALLERY -->
<section class="section-gallery">
  <div class="gallery-inner">
    <div class="gallery-header">
      <div class="section-label">El oficio</div>
      <h2 class="section-title" style="margin-bottom:0">Tradición que<br><em>el sistema preserva</em></h2>
    </div>
    <div class="gallery-grid">

      <div class="gallery-item gallery-tall">
        <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=700&q=85" alt="Pan artesanal masa madre"/>
        <div class="gallery-overlay">
          <span class="gallery-tag">Masa madre · Día 5</span>
          <p>El resultado de 5 días de fermentación natural</p>
        </div>
      </div>

      <div class="gallery-col">
        <div class="gallery-item">
          <img src="https://images.unsplash.com/photo-1568254183919-78a4f43a2877?w=600&q=85" alt="Panadero amasando"/>
          <div class="gallery-overlay">
            <span class="gallery-tag">El proceso</span>
            <p>Técnica y precisión en cada etapa</p>
          </div>
        </div>
        <div class="gallery-item">
          <img src="https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=600&q=85" alt="Pan horneado"/>
          <div class="gallery-overlay">
            <span class="gallery-tag">Fermentación activa</span>
            <p>Control de temperatura y tiempo</p>
          </div>
        </div>
      </div>

      <div class="gallery-item gallery-tall">
        <img src="https://images.unsplash.com/photo-1517433670267-08bbd4be890f?w=700&q=85" alt="Panes artesanales variados"/>
        <div class="gallery-overlay">
          <span class="gallery-tag">Resultado final</span>
          <p>Corteza crujiente, miga abierta y sabor profundo</p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- DASHBOARD PREVIEW -->
<section class="section-dashboard" id="dashboard">
  <div class="section-label">Panel de control</div>
  <h2 class="section-title">Visibilidad total<br><em>para el coordinador</em></h2>
  <p class="section-subtitle">Todas las panaderías, todos los días, en un solo lugar. Filtra por región, revisa alertas y exporta reportes al instante.</p>

  <div class="dashboard-layout">
    <!-- Sidebar -->
    <div class="dash-sidebar">
      <div class="dash-sidebar-logo">
        <div class="dash-sidebar-logo-icon">
          <svg viewBox="0 0 24 24"><path d="M12 2C8 2 4 5.5 4 9c0 2.4 1.2 4.5 3 5.7V17h10v-2.3c1.8-1.2 3-3.3 3-5.7 0-3.5-4-7-8-7zm-2 13h4v1a2 2 0 01-4 0v-1z"/></svg>
        </div>
        <span>MasaMadre</span>
      </div>

      <div class="dash-nav-section">Principal</div>
      <div class="dash-nav-item active">
        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Panel general
      </div>
      <div class="dash-nav-item">
        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        Panaderías
      </div>
      <div class="dash-nav-item">
        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        Registros
      </div>
      <div class="dash-nav-section">Reportes</div>
      <div class="dash-nav-item">
        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Exportar
      </div>
      <div class="dash-nav-item">
        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        Alertas
        <span style="margin-left:auto;background:rgba(251,191,36,0.15);color:#fbbf24;font-family:'DM Mono';font-size:0.55rem;padding:0.1rem 0.4rem;border-radius:100px;">3</span>
      </div>
    </div>

    <!-- Main -->
    <div class="dash-main">
      <div class="dash-topbar">
        <div class="dash-topbar-title">Resumen del programa</div>
        <div class="dash-topbar-pills">
          <div class="dash-pill active">Hoy</div>
          <div class="dash-pill">Semana</div>
          <div class="dash-pill">Mes</div>
        </div>
      </div>
      <div class="dash-grid">
        <!-- Widget 1 -->
        <div class="dash-widget">
          <div class="dash-widget-label">Panaderías activas</div>
          <div class="dash-widget-value">24<sub>/ 30</sub></div>
          <div class="dash-widget-trend">↑ 3 esta semana</div>
          <svg class="sparkline" width="80" height="40" viewBox="0 0 80 40">
            <polyline points="0,35 15,28 25,30 40,15 55,18 70,8 80,10" fill="none" stroke="#C8A96E" stroke-width="1.5"/>
          </svg>
        </div>
        <!-- Widget 2 -->
        <div class="dash-widget">
          <div class="dash-widget-label">Alertas pH</div>
          <div class="dash-widget-value" style="color:#fbbf24">3<sub> alertas</sub></div>
          <div class="dash-widget-trend down">↑ Revisar hoy</div>
        </div>
        <!-- Widget 3 -->
        <div class="dash-widget">
          <div class="dash-widget-label">pH promedio</div>
          <div class="dash-widget-value">4.3</div>
          <div class="ph-bar"><div class="ph-bar-fill"></div></div>
          <div class="ph-range"><span>3.5</span><span>Ideal</span><span>5.0</span></div>
        </div>
        <!-- Widget 4 (span 2) -->
        <div class="dash-widget span-2">
          <div class="dash-widget-label">Panaderías · Estado del día</div>
          <div class="dash-list" style="margin-top:0.6rem">
            <div class="dash-list-item">
              <div class="dash-list-item-left">
                <div class="dash-list-dot" style="background:#4ade80"></div>Panadería El Trigal
              </div>
              <div style="display:flex;gap:0.4rem">
                <span class="dash-badge badge-day">Día 3</span>
                <span class="dash-badge badge-ok">pH 4.2</span>
              </div>
            </div>
            <div class="dash-list-item">
              <div class="dash-list-item-left">
                <div class="dash-list-dot" style="background:#fbbf24"></div>Panadería La Espiga
              </div>
              <div style="display:flex;gap:0.4rem">
                <span class="dash-badge badge-day">Día 2</span>
                <span class="dash-badge badge-warn">pH 5.3 ⚠</span>
              </div>
            </div>
            <div class="dash-list-item">
              <div class="dash-list-item-left">
                <div class="dash-list-dot" style="background:#4ade80"></div>Panadería Masa Viva
              </div>
              <div style="display:flex;gap:0.4rem">
                <span class="dash-badge badge-day">Día 5</span>
                <span class="dash-badge badge-ok">pH 3.9</span>
              </div>
            </div>
          </div>
        </div>
        <!-- Widget 5 -->
        <div class="dash-widget">
          <div class="dash-widget-label">Temp. promedio</div>
          <div class="dash-widget-value">23°<sub>C</sub></div>
          <div class="dash-widget-trend">✓ Rango normal</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ROLES -->
<section class="section-roles">
  <div style="position:relative;">
    <div class="section-label">Accesos</div>
    <h2 class="section-title">Un sistema,<br><em>dos roles</em></h2>
    <p class="section-subtitle" style="margin-bottom:1.5rem">Diseñado para panaderos que no son técnicos y coordinadores que necesitan control total.</p>
    <!-- Baker photo -->
    <!-- Baker photo -->
<div style="border-radius:20px;overflow:hidden;height:340px;
            border:1px solid rgba(200,169,110,0.15);">
  <img src="https://images.unsplash.com/photo-1568254183919-78a4f43a2877?w=600&q=85"
       alt="Panadero amasando masa madre"
       style="width:100%;height:100%;
              object-fit:cover;
              object-position:center top;
              filter:brightness(0.68) saturate(0.8);">
</div>
  </div>
  <div style="display:flex;flex-direction:column;gap:1.25rem">
    <!-- Panadero -->
    <div class="role-card">
      <div class="role-icon baker">
        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2C8 2 4 5.5 4 9c0 2.4 1.2 4.5 3 5.7V17h10v-2.3c1.8-1.2 3-3.3 3-5.7 0-3.5-4-7-8-7zm-2 13h4v1a2 2 0 01-4 0v-1z"/>
        </svg>
      </div>
      <div class="role-sub">Rol · Panadero</div>
      <div class="role-title">El artesano digital</div>
      <div class="role-desc">Registra su proceso diario desde el celular. La app es tan simple como llenar un formulario en papel.</div>
      <div class="role-features">
        <div class="role-feature"><div class="role-feature-dot"></div>Formulario diario guiado (5 pasos)</div>
        <div class="role-feature"><div class="role-feature-dot"></div>Alerta inmediata si el pH se desvía</div>
        <div class="role-feature"><div class="role-feature-dot"></div>Historial de sus procesos anteriores</div>
      </div>
    </div>
    <!-- Coordinador -->
    <div class="role-card">
      <div class="role-icon coord">
        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
          <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
        </svg>
      </div>
      <div class="role-sub">Rol · Coordinador SENA</div>
      <div class="role-title">El centro de control</div>
      <div class="role-desc">Panel completo con todas las panaderías. Detecta problemas antes de que ocurran y genera informes con un clic.</div>
      <div class="role-features">
        <div class="role-feature"><div class="role-feature-dot"></div>Vista en tiempo real de todos los registros</div>
        <div class="role-feature"><div class="role-feature-dot"></div>Filtros por región y estado del proceso</div>
        <div class="role-feature"><div class="role-feature-dot"></div>Exportación a Excel y PDF instantánea</div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-brand">MasaMadre · SENA</div>
  <div class="footer-copy">Sistema de Control de Fermentación · Colombia © 2025</div>
</footer>

</body>
</html>