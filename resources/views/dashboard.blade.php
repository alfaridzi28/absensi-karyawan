@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if($user->isAdmin())
<!-- Admin -->
  <div class="rounded-4 p-5 mb-3 text-center text-white" style="background: linear-gradient(135deg, #4a90e2, #50e3c2); box-shadow: 0 4px 15px rgb(80 227 194 / 0.4);">
    <h1 class="display-4 fw-bold text-shadow">👋 Selamat Datang, {{ Auth::user()->username ?? 'Pengguna' }}!</h1>
    <p class="fs-4 fw-semibold" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
      Senang melihatmu kembali. Semoga harimu menyenangkan!
    </p>
  </div>
  <div class="container py-4">
    <div class="row text-center mb-4">
      <div class="col-md-4 d-flex flex-column align-items-center justify-content-center">
        <h6 class="text-muted mb-1">Total User</h6>
        <h2 class="fw-bold text-dark">10</h2>
      </div>

      <div class="col-md-4">
        <div class="chart-wrapper mx-auto">
          <canvas id="absenChart"></canvas>
        </div>
        <div class="d-flex justify-content-center mt-2" id="absenLegend" class="legend-container">
          <div class="legend-item me-3" data-chart="absenChart" data-index="0">
            <span class="badge bg-success"></span>
            <small>Sudah Absen</small>
          </div>
          <div class="legend-item" data-chart="absenChart" data-index="1">
            <span class="badge bg-secondary"></span>
            <small>Belum Absen</small>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="chart-wrapper mx-auto">
          <canvas id="izinChart"></canvas>
        </div>
        <div class="d-flex justify-content-center mt-2" id="izinLegend" class="legend-container">
          <div class="legend-item me-3" data-chart="izinChart" data-index="0">
            <span class="badge bg-primary"></span>
            <small>Izin</small>
          </div>
          <div class="legend-item" data-chart="izinChart" data-index="1">
            <span class="badge bg-light text-dark"></span>
            <small>Tidak Izin</small>
          </div>
        </div>
      </div>

    </div>
  </div>

@push('styles')
  <link href="{{ asset('assets/css/admin_dashboard.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const totalUsers = {{ $totalUsers }};
    const usersAbsenToday = {{ $usersAbsenToday }};
    const totalIzinToday = {{ $totalIzinToday }};

    function createCenterTextPlugin(labelColor, getCenterText) {
      return {
        id: 'centerText',
        beforeDraw(chart) {
          const { width, height } = chart;
          const ctx = chart.ctx;
          ctx.restore();

          const fontSize = (height / 114).toFixed(2);
          ctx.font = fontSize + "em sans-serif";
          ctx.textBaseline = "middle";

          const text = getCenterText(chart);
          const textX = Math.round((width - ctx.measureText(text).width) / 2);
          const textY = height / 2;

          ctx.fillStyle = labelColor;
          ctx.fillText(text, textX, textY);
          ctx.save();
        }
      };
    }

    // Chart Absen
    const absenChart = new Chart(document.getElementById('absenChart'), {
      type: 'doughnut',
      data: {
        labels: ['Sudah Absen', 'Belum Absen'],
        datasets: [{
          data: [usersAbsenToday, totalUsers - usersAbsenToday],
          backgroundColor: ['#28a745', '#e0e0e0'],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
          tooltip: { enabled: false },
          legend: { display: false }
        }
      },
      plugins: [
        createCenterTextPlugin('#28a745', (chart) => {
          const meta = chart.getDatasetMeta(0);
          const dataset = chart.data.datasets[0];
          let sum = 0;
          meta.data.forEach((segment, i) => {
            if (!segment.hidden) sum += dataset.data[i];
          });
          return `${sum}/${totalUsers}`;
        })
      ]
    });

    // Chart Izin
    const izinChart = new Chart(document.getElementById('izinChart'), {
      type: 'doughnut',
      data: {
        labels: ['Izin', 'Tidak Izin'],
        datasets: [{
          data: [totalIzinToday, totalUsers - totalIzinToday],
          backgroundColor: ['#ffc107', '#e0e0e0'],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
          tooltip: { enabled: false },
          legend: { display: false }
        }
      },
      plugins: [
        createCenterTextPlugin('#0d6efd', (chart) => {
          const meta = chart.getDatasetMeta(0);
          const dataset = chart.data.datasets[0];
          let sum = 0;
          meta.data.forEach((segment, i) => {
            if (!segment.hidden) sum += dataset.data[i];
          });
          return `${sum}/${totalUsers}`;
        })
      ]
    });

    const chartMap = {
      absenChart,
      izinChart,
    };

    function setupLegendToggle() {
      document.querySelectorAll('.legend-item').forEach(item => {
        const chartId = item.dataset.chart;
        const index = parseInt(item.dataset.index);
        const chart = chartMap[chartId];

        if (!chart) {
          console.warn(`Chart not found: ${chartId}`);
          return;
        }

        item.style.cursor = 'pointer';

        item.addEventListener('click', () => {
          const meta = chart.getDatasetMeta(0);
          const segment = meta.data[index];
          if (!segment) return;

          segment.hidden = !segment.hidden;

          item.classList.toggle('opacity-50');

          chart.update();
        });
      });
    }

    setupLegendToggle();
  </script>
@endpush

@else
  <!-- User -->
  <div class="rounded-4 p-5 mb-5 text-center text-white" 
    style="background: linear-gradient(135deg, #4a90e2, #50e3c2); box-shadow: 0 4px 15px rgb(80 227 194 / 0.4);">

    <h1 class="display-4 fw-bold" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
        👋 Selamat Datang, {{ auth()->user()->username ?? 'Pengguna' }}!
    </h1>

    @if($userHasAbsenMasuk && $userHasAbsenPulang)
        <p class="fs-4 fw-semibold mb-4" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
          Kamu sudah <strong>absen masuk dan pulang</strong> hari ini. Sampai jumpa besok!
        </p>
    @elseif($userHasAbsenMasuk)
        <p class="fs-4 fw-semibold mb-4" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
            Kamu sudah <strong>absen masuk</strong> hari ini. Terima kasih!
        </p>
    @elseif($userHasAbsenIzin)
        <p class="fs-4 fw-semibold mb-4" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
            Kamu telah mengajukan <strong>izin</strong> hari ini. Semoga semuanya baik-baik saja!
        </p>
    @else
      <p class="fs-4 fw-semibold mb-4" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
        Silakan absen masuk untuk memulai hari ini.
      </p>
    @endif

    <form action="{{ route('absen.post') }}" method="POST" class="d-inline">
        @csrf
        @if(!$userHasAbsenMasuk && !$userHasAbsenIzin)
            <input type="hidden" name="type" value="masuk">
            <button type="submit" class="btn btn-light btn-lg me-3">✅ Absen Masuk</button>
            <button type="button" class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#modalIzin">
              📝 Izin
            </button>
        @elseif($userHasAbsenMasuk && !$userHasAbsenPulang)
            <input type="hidden" name="type" value="pulang">
            <button type="submit" class="btn btn-light btn-lg me-3">🏁 Absen Pulang</button>
        @endif
    </form>
  </div>

  <!-- Modal input notes untuk Izin -->
  <div class="modal fade" id="modalIzin" tabindex="-1" aria-labelledby="modalIzinLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="{{ route('absen.post') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="izin">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalIzinLabel">Ajukan Izin</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="notes" class="form-label">Alasan Izin <small class="text-danger">*</small></label>
              <textarea name="notes" id="notes" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="bukti" class="form-label">Upload Bukti (PNG, JPG, PDF)</label>
              <input type="file" name="bukti" id="bukti" class="form-control" accept=".png,.jpg,.jpeg,.pdf">
              <small class="text-muted">Opsional. Maks 2MB.</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning">Kirim Izin</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endif

@endsection