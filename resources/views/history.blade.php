@extends('layouts.main')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="card shadow-sm border-0">
  <div class="card-body">
    <h4>Riwayat Absensi</h4>

    <div class="row mb-3">
      <div class="col-md-4">
        <!-- <label for="daterange" class="form-label">Filter Tanggal</label> -->
        <input id="daterange" class="form-control" placeholder="Pilih tanggal..." style="cursor:pointer" />
      </div>
    </div>

    <div class="table-responsive">
      <table id="absenTable" class="table table-hover table-bordered align-middle" style="width:100%;">
        <thead class="table-dark">
          <tr>
            <th>Nama Karyawan</th>
            <th>Tanggal</th>
            <th>Waktu Masuk</th>
            <th>Waktu Pulang</th>
            <th>Tipe absensi</th>
            <th>Catatan</th>
            <th>Bukti</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  flatpickr("#daterange", {
    mode: "range",
    dateFormat: "Y-m-d",
    minDate: "2020-01-01",
    maxDate: "2030-12-31",
    monthSelectorType: "static",
    onClose: function(selectedDates) {
      if (selectedDates.length === 2) {
        const jakartaOptions = { timeZone: 'Asia/Jakarta', year: 'numeric', month: '2-digit', day: '2-digit' };

        const toJakartaDate = date => {
          const parts = new Intl.DateTimeFormat('en-CA', jakartaOptions).formatToParts(date);
          const y = parts.find(p => p.type === 'year').value;
          const m = parts.find(p => p.type === 'month').value;
          const d = parts.find(p => p.type === 'day').value;
          return `${y}-${m}-${d}`;
        };

        startDate = toJakartaDate(selectedDates[0]);
        endDate = toJakartaDate(selectedDates[1]);
        table.ajax.reload();
      }
    }
  });

  let startDate = null;
  let endDate = null;

  let table = $('#absenTable').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    ajax: {
      url: "{{ route('absen.riwayat.data') }}",
      data: function(d) {
        d.start_date = startDate;
        d.end_date = endDate;
      }
    },
    columns: [
      { data: 'username' },
      { data: 'tanggal' },
      { data: 'waktu_masuk' },
      { data: 'waktu_pulang' },
      { data: 'type' },
      { data: 'notes' },
      {
        data: 'bukti',
        render: (data, type, row) =>
          data
            ? `<a href="/storage/izin/${row.user_id}/${data}" download>
                <img src="/storage/izin/${row.user_id}/${data}" width="70" height="70">
              </a>`
            : '-'
      }
    ]
  });

</script>
@endpush