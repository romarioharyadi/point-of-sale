<script src="https://cdn.tiny.cloud/1/vb2vvtudwse9w7o4ctyv2wwcfafrv1zuvfyt0adf9ym452ju/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    const table = $('#tableDataKategori').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: '{{ route("masterdata.kategoriProduk.apiData") }}',
            type: 'GET'
        },
        columns: [
            {data: null},
            {data: 'nama_kategori', name: 'nama_kategori', orderable: true, searchable: true, defaultContent: ''},
            {data: 'action', name: 'action', orderable: false, searchable: false, defaultContent: ''},
        ],
        columnDefs: [
            {targets: 0, searchable: false, orderable: false},
        ],
    });

    table.on('draw.dt', function () {
        var PageInfo = $("#tableDataKategori").DataTable().page.info();
        table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
            cell.innerHTML = i + 1 + PageInfo.start;
        });
    })

    $(document).ready(function() {
        $('#close-button').click(function() {
            $('.nama_kategori').val('').change();
        });

        $('#form-save').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('masterdata.kategoriProduk.save') }}",
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
    });

    function addData() {
        $('#modal_add').modal({backdrop: 'static', keyboard: false});
        $('#idKategori').val('');
        $('#modal_add').modal('show');
        $('#modal-title').text('Tambah Data Kategori');
        $('#nama_kategori').val('').attr('readonly', false);
        $('#save').text('Tambah').show();
    }

    function editData(id) {
        $.ajax({
            type: 'GET',
            url: '{{ route("masterdata.kategoriProduk.edit") }}',
            data: {
                id: id
            },
            beforeSend: function() {
                $('#modal_add').modal('hide');
            },
            success: function(data) {
                $('#idKategori').val(data.idKategori);
                $('#modal_add').modal('show');
                $('#modal-title').text('Edit Data Kategori');
                $('#nama_kategori').val(data.nama_kategori).attr('readonly', false);
                $('#save').text('Ubah').show();
            },
            error: function(data) {
            }
        })
    }

    function deleteData(id) {
        Swal.fire({
            title: 'Apa kamu yakin?',
            text: "Anda Ingin Menghapus Kategori Data Ini ?",
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
                url: '{{ route("masterdata.kategoriProduk.delete") }}',
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