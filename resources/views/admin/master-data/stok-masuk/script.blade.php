<script src="https://cdn.tiny.cloud/1/vb2vvtudwse9w7o4ctyv2wwcfafrv1zuvfyt0adf9ym452ju/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    const table = $('#tableDataStokMasuk').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: '{{ route("masterdata.stokMasuk.apiData") }}',
            type: 'GET'
        },
        columns: [
            {data: null},
            {data: 'getProduk', name: 'getProduk', orderable: true, searchable: true, defaultContent: ''},
            {data: 'qty', name: 'qty', orderable: true, searchable: true, defaultContent: ''},
            {data: 'changeDate', name: 'changeDate', orderable: true, searchable: true, defaultContent: ''},
            {data: 'action', name: 'action', orderable: false, searchable: false, defaultContent: ''},
        ],
        columnDefs: [
            {targets: 0, searchable: false, orderable: false},
        ],
    });

    table.on('draw.dt', function () {
        var PageInfo = $("#tableDataStokMasuk").DataTable().page.info();
        table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
            cell.innerHTML = i + 1 + PageInfo.start;
        });
    })

    $('#supplier').select2();

    $(document).ready(function() {
        $('#tableProduk').DataTable();

        $(document).on('click', '#select', function(){
            var idProduk = $(this).data('id');
            var barcode = $(this).data('barcode');
            var nama_produk = $(this).data('name');
            var unit = $(this).data('unit');
            var stock = $(this).data('stock');
            $('#idProduk').val(idProduk);
            $('#barcode').val(barcode);
            $('#nama_produk').val(nama_produk);
            $('#unit').val(unit);
            $('#stok').val(stock);
            $('#modal_produk').modal('hide');
        })

        $('#close-button').click(function() {
            $('#supplier').html('');
            $('#idProduk').val('');
            $('#nama_produk').val('');
            $('#unit').val('');
            $('#qty').val('');
            $('#date').val('');
        });

        $('#form-save').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('masterdata.stokMasuk.save') }}",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#modal_add').modal('hide');
                },
                success: function(data) {
                    Swal.fire({
                        title: `${data.title}`,
                        icon: `${data.status}`,
                        html: `${data.message}`,
                        timer: `${data.timer}`,
                        showConfirmButton: false,
                    });
                    $('#modal_add').modal('hide');
                    table.ajax.reload();
                },
                error: function(data) {
                }
            })
        })

        $('.supplier').select2({
            dropdownParent: $("#modal_add"),
            ajax: {
                url: "{{ route('masterdata.stokMasuk.apiSupplier') }}",
                type: "GET",
                dataType: 'json',
                delay: 100,
                data: function (params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term,
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            },
            placeholder: '-- Pilih Supplier --',
            allowClear: false
        });
    });

    function addData() {
        $('#modal_add').modal('show');
        $('#btnProduk').attr('disabled', false).css("cursor", "auto");
        $('.supplier').val(0).attr('disabled', false).change().css("cursor", "auto");
        $('#idProduk').val('').css("cursor", "auto");
        $('#modal-title').text('Tambah Data Stok');
        $('#nama_produk').val('-').attr('disabled', true).css("cursor", "auto");
        $('#barcode').val('').attr('disabled', true).css("cursor", "auto");
        $('#unit').val('-').attr('disabled', true).css("cursor", "auto");
        $('#qty').val('-').attr('disabled', false).css("cursor", "auto");
        $('#stok').val('-').attr('disabled', true).css("cursor", "auto");
        $('#date').val('').attr('disabled', false).css("cursor", "auto");
        $('#save').text('Tambah').show();
    }

    function modalProduk(){
        $('#modal_produk').modal('show');
        $('#modal_add').modal({backdrop: 'static', keyboard: false, show: true});
    }

    function detailData(id) {
        $('#supplier').html('');
        $.ajax({
            type: 'GET',
            url: '{{ route("masterdata.stokMasuk.detail") }}',
            data: {
                id: id
            },
            success: function(data) {
                $('#btnProduk').attr("disabled", 'disabled');
                $('#idSupplier').val(data.idSupplier);
                $('#modal_add').modal('show');
                $('#modal-title').text('Detail Data Stok');
                $('#date').val(data.dataStok.date).attr("disabled", 'disabled').css("cursor", "not-allowed");
                $('#barcode').val(data.dataProduk.barcode).attr("disabled", 'disabled').css("cursor", "not-allowed");
                $('#nama_produk').val(data.dataProduk.nama_produk).attr('readonly', true).css("cursor", "not-allowed");
                $('#unit').val(data.dataUnit.unit).attr('readonly', true).css("cursor", "not-allowed");
                $('#stok').val(data.dataProduk.stock).attr('readonly', true).css("cursor", "not-allowed");
                $('.supplier').append($('<option>').val(data.dataSupplier.idSupplier).text(data.dataSupplier.nama_supplier)).attr("disabled", 'disabled').change();
                $('#qty').val(data.dataStok.qty).attr("disabled", 'disabled').css("cursor", "not-allowed");
                $('#save').text('Ubah').hide();
            },
            error: function(data) {
            }
        })
    }

    function deleteData(id) {
        Swal.fire({
            title: 'Apa kamu yakin?',
            text: "Anda Ingin Menghapus Stok Data Ini ?",
            icon: 'warning',
            cancelButtonText: "Batal",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: `Ya, saya yakin!`
        }).then((result) => {
            if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '{{ route("masterdata.stokMasuk.delete") }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id : id
                },
                success: function(data) {
                    Swal.fire({
                        title: `${data.title}`,
                        icon: `${data.status}`,
                        text: `${data.message}`,
                        timer: `${data.timer}`,
                        showConfirmButton: false,
                    });
                    table.ajax.reload();
                },
                error: function (data) {
                }
            })
            }
        })
    }
</script>