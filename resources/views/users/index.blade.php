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

  <div class="bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between mb-3">
      <h4>Manajemen User</h4>
      <button class="btn btn-primary" id="createUser">Tambah User</button>
    </div>

    <table id="userTable" class="table table-bordered">
      <thead>
        <tr>
          <th>Username</th>
          <th>Roles</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="userForm">
      @csrf
      <input type="hidden" name="id" id="userId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" class="form-control" name="username" id="username">
                <div class="invalid-feedback" id="error-username"></div>
            </div>
            <div class="mb-3">
                <label>password</label>
                <input type="password" class="form-control" name="password" id="password">
                <div class="invalid-feedback" id="error-password"></div>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select class="form-select" name="role" id="role">
                    <option value="">-- Pilih Role --</option>
                    <option value="1">Admin</option>
                    <option value="2">Karyawan</option>
                </select>
                <div class="invalid-feedback" id="error-role"></div>
            </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(function () {
        let table = $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.index') }}",
        columns: [
            { data: 'username' },
            { data: 'role' },
            { data: 'action', orderable: false, searchable: false, width: '140px' }
        ]
        });

        $('#createUser').click(function () {
            $('#userForm')[0].reset();
            $('#userId').val('');
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#userModal').modal('show');
        });

        $('#userTable').on('click', '.edit-user', function () {
            let id = $(this).data('id');
            $.get(`/users/${id}`, function (data) {
                $('#userId').val(data.id);
                $('[name="username"]').val(data.username);
                $('[name="role"]').val(data.role);
                $('.form-control, .form-select').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#userModal').modal('show');
            });
        });

        $('#userTable').on('click', '.btn-delete', function (e) {
            e.preventDefault();
            const url = $(this).data('url');

            Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload(null, false); 
                        Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                    }
                    });
                }
            });
        });

        $('#userForm').submit(function (e) {
            e.preventDefault();
            const form = $(this);
            const isEdit = $('#userId').val() !== '';

            form.find('.form-control, .form-select').removeClass('is-invalid');
            form.find('.invalid-feedback').text('');

            $.post("{{ route('users.store') }}", form.serialize())
                .done(function (response) {
                    $('#userModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message || (isEdit ? 'User berhasil diperbarui.' : 'User berhasil ditambahkan.'),
                        timer: 2000,
                        showConfirmButton: false
                    });
                })
                .fail(function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            $(`[name="${field}"]`).addClass('is-invalid');
                            $(`#error-${field}`).text(errors[field][0]);
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data.',
                        });
                    }
                });
        });
    });
</script>
@endpush