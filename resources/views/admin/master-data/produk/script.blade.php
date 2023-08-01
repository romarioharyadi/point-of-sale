<script src="https://cdn.tiny.cloud/1/vb2vvtudwse9w7o4ctyv2wwcfafrv1zuvfyt0adf9ym452ju/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    const table = $('#tableDataProduk').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: '{{ route("masterdata.itemProduk.apiData") }}',
            type: 'GET'
        },
        columns: [
            {data: null},
            {data: 'getKategori', name: 'getKategori', orderable: true, searchable: true, defaultContent: ''},
            {data: 'getUnit', name: 'getUnit', orderable: true, searchable: true, defaultContent: ''},
            {data: 'getBarcode', name: 'getBarcode', orderable: true, searchable: true, defaultContent: ''},
            {data: 'nama_produk', name: 'nama_produk', orderable: true, searchable: true, defaultContent: ''},
            {data: 'harga_produk', name: 'harga_produk', orderable: true, searchable: true, defaultContent: ''},
            {data: 'stock', name: 'stock', orderable: true, searchable: true, defaultContent: ''},
            {data: 'action', name: 'action', orderable: false, searchable: false, defaultContent: ''},
        ],
        columnDefs: [
            {targets: 0, searchable: false, orderable: false},
        ],
    });

    table.on('draw.dt', function () {
        var PageInfo = $("#tableDataProduk").DataTable().page.info();
        table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
            cell.innerHTML = i + 1 + PageInfo.start;
        });
    })

    $('#kategori').select2();
    $('#unit').select2();

    $(document).ready(function() {
        $('#close-button').click(function() {
            $('.kategori').val(0).attr('disabled', false).change();
            $('.unit').val(0).attr('disabled', false).change();
            $('.nama_produk').val('').change();
            $('.harga_produk').val('').change();
            $('.stock').val('').change();
        });

        $('#form-save').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('masterdata.itemProduk.save') }}",
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

        $('.kategori').select2({
            dropdownParent: $("#modal_add"),
            ajax: {
                url: "{{ route('masterdata.itemProduk.apiKategoriProduk') }}",
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
            placeholder: '-- Pilih Kategori Produk --',
            allowClear: false
        });

        $('.unit').select2({
            dropdownParent: $("#modal_add"),
            ajax: {
                url: "{{ route('masterdata.itemProduk.apiUnitProduk') }}",
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
            placeholder: '-- Pilih Unit Produk --',
            allowClear: false
        });
    });

    function addData() {
        $('#idProduk').val('');
        $('#modal_add').modal('show');
        $('#modal-title').text('Tambah Data Produk');
        $('#kategori').val(0).attr('disabled', false).change();
        $('#unit').val(0).attr('disabled', false).change();
        $('#nama_produk').val('').attr('readonly', false);
        $('#harga_produk').val('').attr('readonly', false);
        $('#stock').val('').attr('readonly', false);
        $('#save').text('Tambah').show();
    }

    function editData(id) {
        $.ajax({
            type: 'GET',
            url: '{{ route("masterdata.itemProduk.edit") }}',
            data: {
                id: id
            },
            success: function(data) {
                $('#idProduk').val(data.dataProduk.idProduk);
                $('#modal_add').modal('show');
                $('#modal-title').text('Edit Data Produk');
                $('#barcode').val(data.unit).attr('readonly', false);
                $('.kategori').append($('<option>').val(data.dataProduk.idKategori).text(data.dataKategori.nama_kategori)).attr('disabled', false).change();
                $('.unit').append($('<option>').val(data.dataProduk.idUnit).text(data.dataUnit.unit)).attr('disabled', false).change();
                $('#nama_produk').val(data.dataProduk.nama_produk).attr('readonly', false);
                $('#harga_produk').val(data.dataProduk.harga_produk).attr('readonly', false);
                $('#stock').val(data.dataProduk.stock).attr('readonly', false);
                $('#save').text('Ubah').show();
            },
            error: function(data) {
            }
        })
    }

    function deleteData(id) {
        Swal.fire({
            title: 'Apa kamu yakin?',
            text: "Anda Ingin Menghapus Produk Data Ini ?",
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
                url: '{{ route("masterdata.itemProduk.delete") }}',
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