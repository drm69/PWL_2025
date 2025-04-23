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
                            <a class="dropdown-item" href="{{ url('/kategori/export_pdf') }}">
                                <i class="fa fa-file-pdf text-danger"></i> Export PDF
                            </a>
                            <a class="dropdown-item" href="{{ url('/kategori/export_excel') }}">
                                <i class="fa fa-file-excel text-success"></i> Export Excel
                            </a>
                        </div>
                    </div>
            
                    <!-- Tombol Import -->
                    <button onclick="modalAction('{{ url('kategori/import') }}')" class="btn btn-success">
                        <i class="fa fa-upload"></i> Import Kategori
                    </button>
            
                    <!-- Tombol Tambah Ajax -->
                    <button onclick="modalAction('{{ url('kategori/create_ajax') }}')" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Tambah Kategori
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{-- Alert untuk Success --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Alert untuk Error --}}
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static"data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

            var dataKategori;
        $(document).ready(function() {
            dataKategori = $('#table_kategori').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('kategori/list') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}"
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
                        data: "kategori_kode",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "kategori_nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush