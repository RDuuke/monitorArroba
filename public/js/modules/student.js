
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
    $('#userCreateModal').modal('hide');

  }).
  fail(function(response){
    toastr.error('Servicio no disponible intentalo luego.', 'Error!!', {timeOut: 3000});
  });

});
$("#tb_user").on('click', '.studentEliminar', function(event) {
    event.preventDefault();
    _td = $(this);
    var _data = functions.getDataTable(_td);
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
    var _data = functions.getDataTable(_td);
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
$("#tb_user").on('click', '.searchRegister', function(event){
  let n = 0;
  event.preventDefault();
  _td = $(this);
  var _data = functions.getDataTable(_td);
  $("#name_student").html(_data.nombres + ' ' + _data.apellidos);
  var url = _td.attr('href') + _data.id
  $.get(url).done(function(response){
      $("#result_student_register_table tbody").html("");
      $("#result_student_register_table tbody").html(response);
      $("#studentRegister").modal('show');
  });

});
$("#result_student_register_table").on("click", ".archive_register", function(event){
  event.preventDefault();
  _this = $(this);
  //$("#name_student").html(data.nombres + ' ' + data.apellidos);
  $.get($(this).attr('href')).done(function (response){
    r = JSON.parse(response);
    console.log(r);
    if (r.message == 1 || r.message == '1') {
      _this.parent().parent().remove();
      toastr.success("Matricula archivada", "¡¡ Estupendo!!", {timeOut: 3000});
    }
  });
});
$(".addstudent").on('click', function(event){
    event.preventDefault();
    $('#userCreateForm')[0].reset();
    $('#userCreateForm').attr('action',$(this).attr('data-href'));
    $('#userCreateModal').modal('show');
});
