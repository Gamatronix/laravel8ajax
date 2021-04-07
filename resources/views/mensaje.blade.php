<!DOCTYPE html>
<html>
<head>
    <title>Laravel 7 Crud operation using ajax(Real Programmer)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1>Laravel Crud Con Ajax - Práctica Tecnoip</h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewMensaje"> Create New Mensaje</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>NOMBRE</th>
                <th>EMPRESA</th>
	        <th>TELEFONO</th>
	        <th>CORREO</th>
	        <th>ASUNTO</th>
	        <th>MENSAJE</th>
                <th width="300px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="mensajeForm" name="mensajeForm" class="form-horizontal">
                   <input type="hidden" name="mensaje_id" id="mensaje_id">

                    <div class="form-group">
                        <label for="nombre" class="col-sm-2 control-label">Nombre</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Digite el Nombre del Contacto" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Details</label>
                        <div class="col-sm-12">
                            <textarea id="empresa" name="empresa" required="" placeholder="Digite el nombre de la Empresa" class="form-control"></textarea>
                        </div>
                    </div>

		    <div class="form-group">
                        <label class="col-sm-2 control-label">Details</label>
                        <div class="col-sm-12">
                            <textarea id="telefono" name="telefono" required="" placeholder="Digite el Telefono del Contacto" class="form-control"></textarea>
                        </div>
                    </div>

		    <div class="form-group">
                        <label class="col-sm-2 control-label">Details</label>
                        <div class="col-sm-12">
                            <textarea id="correo" name="correo" required="" placeholder="Digite el Correo del Contacto" class="form-control"></textarea>
                        </div>
                    </div>

		    <div class="form-group">
                        <label class="col-sm-2 control-label">Details</label>
                        <div class="col-sm-12">
                            <textarea id="asunto" name="asunto" required="" placeholder="Introduzca el Asunto del Mensaje" class="form-control"></textarea>
                        </div>
                    </div>

		    <div class="form-group">
                        <label class="col-sm-2 control-label">Details</label>
                        <div class="col-sm-12">
                            <textarea id="mensaje" name="mensaje" required="" placeholder="Digite el mensaje" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                     	<button type="submit" class="btn btn-primary" id="saveBtn" value="create">Guardar Cambios
                     	</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('mensajes.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nombre', name: 'nombre'},
            {data: 'empresa', name: 'empresa'},
	    {data: 'telefono', name: 'telefono'},
	    {data: 'correo', name: 'correo'},
	    {data: 'asunto', name: 'asunto'},
	    {data: 'mensaje', name: 'mensaje'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    $('#createNewMensaje').click(function () {
        $('#saveBtn').val("create-mensaje");
        $('#mensaje_id').val('');
        $('#mensajeForm').trigger("reset");
        $('#modelHeading').html("Crear Nuevo Mensaje");
        $('#ajaxModel').modal('show');
    });
    $('body').on('click', '.editMensaje', function () {
      var mensaje_id = $(this).data('id');
      $.get("{{ route('mensajes.index') }}" +'/' + mensaje_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Mensaje");
          $('#saveBtn').val("edit-mensaje");
          $('#ajaxModel').modal('show');
          $('#mensaje_id').val(data.id);
	  $('#nombre').val(data.nombre);
	  $('#empresa').val(data.empresa);
   	  $('#telefono').val(data.telefono);
	  $('#correo').val(data.correo);
	  $('#asunto').val(data.asunto);
	  $('#mensaje').val(data.mensaje);
      })
   });
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Salvar');

        $.ajax({
          data: $('#mensajeForm').serialize(),
          url: "{{ route('mensajes.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {

              $('#mensajeForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();

          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Guardar Cambios');
          }
      });
    });

    $('body').on('click', '.deleteMensaje', function () {

        var mensaje_id = $(this).data("id");
        confirm("Está Seguro de Borrrar el registro? !");

        $.ajax({
            type: "DELETE",
            url: "{{ route('mensajes.store') }}"+'/'+mensaje_id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

  });
</script>
</body>
</html>
