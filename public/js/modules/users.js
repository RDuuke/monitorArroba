
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
    }else {
      console.log('0');
    }
  }).
  fail(function(response){
    toastr.error('Servicio no disponible intentalo luego.', 'Error!!', {timeOut: 3000});
  });

});
$("#tb_user").on('click', '.studentEliminar', function(event) {
    event.preventDefault();
    _td = $(this);
    var _data = getDataTable(_td);
    var url = _td.attr('href') + _data.id
    $.get(url).done( function(response){
    if (response == 1){
      toastr.success('Estudiante eliminado correctamente.', 'Estupendo!!!', {timeOut: 3000});
      table
        .row( _td.parents('tr') )
        .remove()
        .draw();    }
  }).
  fail(function(response){
    toastr.error('Servicio no disponible intentalo luego.', 'Error!!', {timeOut: 3000});
  });
});
$("#tb_user").on('click', '.studentShow', function(event){
    event.preventDefault();
    _td = $(this);
    var _data = getDataTable(_td);
    var url = _td.attr('href') + _data.id
    $.get(url).done(function(response){
      console.log(JSON.parse(response));
      $.each( JSON.parse(response), function( key, value ) {
        $('input[name="'+key+'"]').val(value);
        $('#userCreateForm').attr('action', _td.attr('data-href') + _data.id);
        $('#userCreateModal').modal('show');
      });
    });
});
$(".addstudent").on('click', function(event){
    event.preventDefault();
    $('#userCreateForm')[0].reset();
    $('#userCreateForm').attr('action',$(this).attr('data-href'));
    $('#userCreateModal').modal('show');
});
function getDataTable(tr) {
   return table.row(tr.parents('tr')).data();
}