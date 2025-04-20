<form action="{{ route('save') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Penjualan</label>
                    <input type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" value="{{ $penjualan_kode }}" readonly>
                    <small id="error-kode_penjualan" class="error-text form-text text-danger"></small>
                </div>                
                <div class="form-group">
                    <label>Kategori Barang</label>
                    <select name="kategori_id" id="kategori_id" class="form-control" required>
                        <option value="">- Pilih Kategori -</option>
                        @foreach ($kategori as $l)
                            <option value="{{ $l->kategori_id }}">{{ $l->kategori_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-kategori_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Barang</label>
                    <select name="barang_id" id="barang_id" class="form-control" required>
                        <option value="">- Pilih Barang -</option>
                        @foreach ($barang as $l)
                            <option value="{{ $l->barang_id }}">{{ $l->barang_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-barang_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" id="harga" class="form-control" required readonly>
                    <small id="error-harga" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="text" name="jumlah" id="jumlah" class="form-control" required>
                    <small id="error-jumlah" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Pembeli</label>
                    <input type="text" name="pembeli" id="pembeli" class="form-control" required>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Ketika kategori dipilih
        $('#kategori_id').on('change', function() {
            var kategoriId = $(this).val();

            // Kosongkan dropdown barang dan harga
            $('#barang_id').empty().append('<option value="">- Pilih Barang -</option>');
            $('#harga').val('');

            if (kategoriId) {
                $.ajax({
                    url: 'transaksi/get-barang-by-kategori/' + kategoriId, // URL untuk mendapatkan barang berdasarkan kategori
                    type: 'GET',
                    success: function(response) {
                        if (response.status) {
                            // Isi dropdown barang dengan data yang diperoleh
                            $.each(response.barang, function(index, barang) {
                                $('#barang_id').append('<option value="' + barang.barang_id + '">' + barang.barang_nama + '</option>');
                            });
                        } else {
                            // Jika tidak ada barang ditemukan
                            $('#barang_id').append('<option value="">Barang tidak ditemukan</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error: ' + status + ' - ' + error);
                    }
                });
            }
        });

        // Ketika barang dipilih
        $('#barang_id').on('change', function() {
            var barangId = $(this).val();

            if (barangId) {
                $.ajax({
                    url: 'transaksi/get-harga-barang/' + barangId, // URL untuk mendapatkan harga barang
                    type: 'GET',
                    success: function(response) {
                        if (response.status) {
                            // Isi field harga dengan harga barang yang dipilih
                            $('#harga').val(response.harga);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error: ' + status + ' - ' + error);
                    }
                });
            }
        });

        // Validasi form
        $("#form-tambah").validate({
            rules: {
                penjualan_kode: { required: true },
                kategori_id: { required: true },
                barang_id: { required: true },
                harga: { required: true },
                jumlah: { required: true },
                pembeli: { required: true }
            },
            submitHandler: function(form) {
                console.log("Form valid, mengirim...");
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                    console.log(response); // Tambahkan ini
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataTransaksi.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
