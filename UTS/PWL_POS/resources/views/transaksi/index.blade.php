@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="card-title mb-0">{{ $page->title }}</h3>
            <div class="d-flex align-items-center mt-2 mt-md-0" style="gap: 10px;">
                <div class="dropdown">
                    <button class="btn btn-warning dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-download"></i> Export
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ url('transaksi/export_pdf') }}">
                            <i class="fa fa-file-pdf text-danger"></i> Export PDF
                        </a>
                        <a class="dropdown-item" href="{{ url('transaksi/export_excel') }}">
                            <i class="fa fa-file-excel text-success"></i> Export Excel
                        </a>
                    </div>
                </div>
                <button onclick="modalAction('{{ route('create') }}')" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Tambah Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan_detail">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Penjualan</th>
                    <th>Kasir</th>
                    <th>Pembeli</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

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

function showDetail(penjualan_id) {
    $.get(`/transaksi/${penjualan_id}/detail`, function(response) {
        console.log(response);  // Log data yang diterima
        let html = '';
        response.data.forEach(item => {
            html += `
                <tr>
                    <td>${item.barang.barang_nama}</td>
                    <td>${item.jumlah_barang}</td>
                    <td>${new Intl.NumberFormat('id-ID').format(item.harga_barang)}</td>
                </tr>
            `;
        });
        $('#detailBody').html(html);
        $('#modalDetail').modal('show');
    }).fail(function() {
        alert("Gagal mengambil data.");
    });
}

var dataTransaksi;

$(document).ready(function () {
    dataTransaksi = $('#table_penjualan_detail').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: "{{ url('transaksi/list') }}",
            type: "POST",
            dataType: "json"
        },
        columns: [
            { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
            { data: "penjualan_kode" },
            { data: "user_nama" },
            { data: "pembeli" },
            { data: "penjualan_tanggal" },
            { data: "aksi", className: "text-center", orderable: false, searchable: false }
        ]
    });

    let searchDelay;
    $('#table_penjualan_detail_filter input').unbind().on('input', function () {
        clearTimeout(searchDelay);
        searchDelay = setTimeout(() => {
            dataTransaksi.search(this.value).draw();
        }, 300); // delay 300ms biar gak spam request
    });
});
</script>
@endpush