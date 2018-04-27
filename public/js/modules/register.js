
$(".addregister").on('click', function(event){
    event.preventDefault();
    $('#registerCreateForm')[0].reset();
    $('#registerCreateForm').addClass('create');
    $('#registerCreateForm').attr('action',$(this).attr('data-href'));
    $('#registerCreateModal').modal('show');
});

$("#registerCreateForm").on( "submit", function( event ) {
    event.preventDefault();
    var userForm = $(this).serialize();
    var _this = $(this);
    var flag = false;

    if(_this.hasClass('create')) {
      var usuario = $("#registerCreateForm input[name='usuario']").val();
      $.get(getUri + '/panel/users/check', {'usuario' : usuario}).done( function(r) {
        var response = JSON.parse(r);
        if( response.message == 0 || response.message == '0') {
          console.log(response.message);
          $("#userCreateModal input[name='usuario']").val(usuario).attr('disabled', true);
          $("#userCreateModal").modal('show');
          flag = true;
        }
      });
    }
    if (! flag) {
      $.post(getUri + $(this).attr('action'), userForm)
      .done( function(r){
        var response = JSON.parse(r);
        if (response.message == 1){
          console.log(response);
          table.row.add(response.register).draw(false);
          toastr.success('Accion completada correctamente.', 'Estupendo!!!', {timeOut: 3000});
        } else if(response.message == 2) {
          console.log(_td);
          table.row(_td.parents('tr')).data(response.register);
          toastr.success('Accion completada correctamente.', 'Estupendo!!!', {timeOut: 3000});
        } else if(response.message == 3) {
          toastr.info('Ya se esta matriculado', 'Info', {timeOut: 3000});
        }else {
          console.log('0');
        }
      })
      .fail(function(response){
        toastr.error('Servicio no disponible intentalo luego.', 'Error!!', {timeOut: 3000});
      });

    } else {
      return false;
    }
  });

  $("#tb_register").on('click', '.registerShow', function(event){
    event.preventDefault();
    _td = $(this);
    var _data = getDataTable(_td);
    var url = _td.attr('href') + _data.id
    $.get(url).done(function(response){
      console.log(JSON.parse(response));
      $.each( JSON.parse(response), function( key, value ) {
        $('input[name="'+key+'"]').val(value);
        $('#registerCreateForm').attr('action', _td.attr('data-href') + _data.id);
        $('#registerCreateForm').removeClass();
        $('#registerCreateForm').addClass('update');
        $('#registerCreateModal').modal('show');
      });
    });
});

$("#tb_register").on('click', '.registertEliminar', function(event) {
  event.preventDefault();
  _td = $(this);
  var _data = getDataTable(_td);
  var url = _td.attr('href') + _data.id
  $.get(url).done( function(response){
  if (response == 1){
    toastr.success('Matricula eliminado correctamente.', 'Estupendo!!!', {timeOut: 3000});
    table
      .row( _td.parents('tr') )
      .remove()
      .draw();    }
  }).
  fail(function(response){
    toastr.error('Servicio no disponible intentalo luego.', 'Error!!', {timeOut: 3000});
  });
});

function getDataTable(tr) {
  return table.row(tr.parents('tr')).data();
}