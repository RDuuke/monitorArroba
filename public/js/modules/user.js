
$("#userCreateForm").on( "submit", function( event ) {
    event.preventDefault();
    var userForm = $(this).serialize();
    $.post(getUri + $(this).attr('action'), userForm).done( function(r){
      var response = JSON.parse(r);
      if (response.message == 1){
        table.row.add(response.user).draw(false);
        toastr.success('Accion completada correctamente.', 'Estupendo!!!', {timeOut: 3000});
      } else if(response.message == 2) {
        console.log(_td);
        table.row(_td.parents('tr')).data(response.user);
        toastr.success('Accion completada correctamente.', 'Estupendo!!!', {timeOut: 3000});
        $('#userCreateModal').modal('hide');
      }else {
        console.log('0');
        toastr.error('No sé pudo registrar correctamente.', 'Error!!', {timeOut: 3000});
      }

    }).
    fail(function(response){
      toastr.error('Servicio no disponible intentalo luego.', 'Error!!', {timeOut: 3000});
    });

});
$("#tb_user").on('click', '.userShow', function(event){
    event.preventDefault();
    _td = $(this);
    var _data = functions.getDataTable(_td);
    var url = _td.attr('href');
    $.get(url).done(function(response){
      $('#userCreateForm')[0].reset();
      console.log(JSON.parse(response));
      $.each( JSON.parse(response), function( key, value ) {
        if ( key == 'id_institucion') {
            $('#institucion option[value='+value+']').attr('selected','selected');
        } else if ( key == 'id_rol') {
            $('select#id_rol option[value='+value+']').attr('selected','selected');
        } else {
            $('input[name="'+key+'"]').val(value);
        }
        $('#userCreateForm').attr('action', _td.attr('data-href') + _data.id);
        $('#userCreateForm').removeClass();
        $('#userCreateForm').addClass('update');
        $('#userCreateModal').modal('show');
      });
    });
});

$("#tb_user").on('click', '.userEliminar', function(event) {
  event.preventDefault();
    var r = confirm("¿Está seguro que desea eliminar este registro de forma permanente?");
    if (r == true) {
      _td = $(this);
      var _data = functions.getDataTable(_td);
      var url = _td.attr('href');
      $.get(url).done( function(response){
      if (response == 1){
        toastr.success('Usuario eliminado correctamente.', 'Estupendo!!!', {timeOut: 3000});
        table
          .row( _td.parents('tr') )
          .remove()
          .draw();    }
      }).
      fail(function(response){
        toastr.error('Servicio no disponible intentalo luego.', 'Error!!', {timeOut: 3000});
      });
    } else {
      return false;
    }
});

$(".addstudent").on('click', function(event){
   event.preventDefault();
    $('#userCreateForm')[0].reset();
    $('#userCreateForm').attr('action',$(this).attr('data-href'));
    $('#userCreateModal').modal('show');
});