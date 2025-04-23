@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h3 class="card-title mb-0">{{ $page->title }}</h3>
                <div class="d-flex align-items-center mt-2 mt-md-0" style="gap: 10px;">
                    <!-- Dropdown Export -->
                    <div class="dropdown">
                        <button class="btn btn-warning dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-download"></i> Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                            <a class="dropdown-item" href="{{ url('/barang/export_pdf') }}">
                                <i class="fa fa-file-pdf text-danger"></i> Export PDF
                            </a>
                            <a class="dropdown-item" href="{{ url('/barang/export_excel') }}">
                                <i class="fa fa-file-excel text-success"></i> Export Excel
                            </a>
                        </div>
                    </div>
            
                    <!-- Tombol Import -->
                    <button onclick="modalAction('{{ url('barang/import') }}')" class="btn btn-success">
                        <i class="fa fa-upload"></i> Import Barang
                    </button>
            
                    <!-- Tombol Tambah Ajax -->
                    <button onclick="modalAction('{{ url('barang/create_ajax') }}')" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Tambah Barang
                    </button>
                </div>
            </div>
        </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-1 control-label col-form-label">Filter:</label>
                <div class="col-3">
                  <select class="form-control" id="kategori_id" name="kategori_id" required>
                    <option value="">- Semua -</option>
                    @foreach($kategori as $item)
                      <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                    @endforeach
                  </select>
                  <small class="form-text text-muted">Kategori Barang</small>
                </div>
              </div>
            </div>
          </div>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Tabel -->
        <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog"
    data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
    $.get(url, function (html) {
        $('#myModal').html(html);
        $('#myModal').modal('show');
    });
}

    var dataBarang;

    $(document).ready(function () {
        dataBarang = $('#table_barang').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ url('barang/list') }}",
                type: "POST",
                dataType: "json",
                data: function (d) {
                    d.kategori_id = $('#kategori_id').val();
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "barang_kode",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "barang_nama",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "kategori_nama",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "harga_beli",
                    className: "text-right",
                    orderable: true,
                    searchable: true,
                    render: function (data, type, row) {
                        return new Intl.NumberFormat('id-ID').format(data);
                    }
                },
                {
                    data: "harga_jual",
                    className: "text-right",
                    orderable: true,
                    searchable: true,
                    render: function (data, type, row) {
                        return new Intl.NumberFormat('id-ID').format(data);
                    }
                },
                {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Filter kategori berubah -> reload tabel
        $('#kategori_id').change(function () {
            dataBarang.draw();
        });

        // Search pakai enter
        $('#table_barang_filter input').unbind().bind().on('keyup', function (e) {
            if (e.keyCode === 13) {
                dataBarang.search(this.value).draw();
            }
        });
    });
</script>
@endpush
