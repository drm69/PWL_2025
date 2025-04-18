@extends('layouts.template')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      {{-- Kartu Statistik Kiri --}}
      <div class="col-lg-6">
        {{-- Chart Visitors --}}
        <div class="card">
          <div class="card-header border-0 d-flex justify-content-between">
            <h3 class="card-title">Online Store Visitors</h3>
            <a href="javascript:void(0);">View Report</a>
          </div>
          <div class="card-body">
            <div class="d-flex">
              <p class="d-flex flex-column">
                <span class="text-bold text-lg">820</span>
                <span>Visitors Over Time</span>
              </p>
              <p class="ml-auto d-flex flex-column text-right">
                <span class="text-success">
                  <i class="fas fa-arrow-up"></i> 12.5%
                </span>
                <span class="text-muted">Since last week</span>
              </p>
            </div>
            <div class="position-relative mb-4">
              <canvas id="visitors-chart" height="200"></canvas>
            </div>
            <div class="d-flex justify-content-end">
              <span class="mr-2"><i class="fas fa-square text-primary"></i> This Week</span>
              <span><i class="fas fa-square text-gray"></i> Last Week</span>
            </div>
          </div>
        </div>

        {{-- Tabel Produk --}}
        <div class="card">
          <div class="card-header border-0 d-flex justify-content-between">
            <h3 class="card-title">Products</h3>
            <div class="card-tools">
              <a href="#" class="btn btn-tool btn-sm"><i class="fas fa-download"></i></a>
              <a href="#" class="btn btn-tool btn-sm"><i class="fas fa-bars"></i></a>
            </div>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped table-valign-middle">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Sales</th>
                  <th>More</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><img src="dist/img/default-150x150.png" class="img-circle img-size-32 mr-2"> Some Product</td>
                  <td>$13 USD</td>
                  <td><small class="text-success mr-1"><i class="fas fa-arrow-up"></i> 12%</small> 12,000 Sold</td>
                  <td><a href="#" class="text-muted"><i class="fas fa-search"></i></a></td>
                </tr>
                {{-- Tambah item lainnya di sini --}}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- Kartu Statistik Kanan --}}
      <div class="col-lg-6">
        {{-- Sales Chart --}}
        <div class="card">
          <div class="card-header border-0 d-flex justify-content-between">
            <h3 class="card-title">Sales</h3>
            <a href="javascript:void(0);">View Report</a>
          </div>
          <div class="card-body">
            <div class="d-flex">
              <p class="d-flex flex-column">
                <span class="text-bold text-lg">$18,230.00</span>
                <span>Sales Over Time</span>
              </p>
              <p class="ml-auto d-flex flex-column text-right">
                <span class="text-success">
                  <i class="fas fa-arrow-up"></i> 33.1%
                </span>
                <span class="text-muted">Since last month</span>
              </p>
            </div>
            <div class="position-relative mb-4">
              <canvas id="sales-chart" height="200"></canvas>
            </div>
            <div class="d-flex justify-content-end">
              <span class="mr-2"><i class="fas fa-square text-primary"></i> This year</span>
              <span><i class="fas fa-square text-gray"></i> Last year</span>
            </div>
          </div>
        </div>

        {{-- Store Overview --}}
        <div class="card">
          <div class="card-header border-0">
            <h3 class="card-title">Online Store Overview</h3>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
              <p class="text-success text-xl"><i class="ion ion-ios-refresh-empty"></i></p>
              <p class="d-flex flex-column text-right">
                <span class="font-weight-bold"><i class="ion ion-android-arrow-up text-success"></i> 12%</span>
                <span class="text-muted">CONVERSION RATE</span>
              </p>
            </div>
            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
              <p class="text-warning text-xl"><i class="ion ion-ios-cart-outline"></i></p>
              <p class="d-flex flex-column text-right">
                <span class="font-weight-bold"><i class="ion ion-android-arrow-up text-warning"></i> 0.8%</span>
                <span class="text-muted">SALES RATE</span>
              </p>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <p class="text-danger text-xl"><i class="ion ion-ios-people-outline"></i></p>
              <p class="d-flex flex-column text-right">
                <span class="font-weight-bold"><i class="ion ion-android-arrow-down text-danger"></i> 1%</span>
                <span class="text-muted">REGISTRATION RATE</span>
              </p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
  <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('adminlte/dist/js/pages/dashboard3.js') }}"></script>
@endpush
