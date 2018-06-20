$(".addcourse").on('click', function (event) {
    event.preventDefault();
    $('#programCreateForm')[0].reset();
    $('#programCreateForm').attr('action', $(this).attr('data-href'));
    $('#programCreateModal').modal('show');
  });

  $("#programCreateForm").on("submit", function (event) {
    event.preventDefault();
    var userForm = $(this).serialize();
    $.post(getUri + $(this).attr('action'), userForm).done(function (r) {
      var response = JSON.parse(r);
      if (response.message == 1) {
        table.row.add(response.course).draw(false);
        toastr.success('Accion completada correctamente.', 'Estupendo!!!', { timeOut: 3000 });
      } else if (response.message == 2) {
        console.log(_td);
        table.row(_td.parents('tr')).data(response.course);
        toastr.success('Accion completada correctamente.', 'Estupendo!!!', { timeOut: 3000 });
        $('#programCreateModal').modal('hide');
      } else {
        console.log('0');
        toastr.error('No sé pudo registrar correctamente.', 'Error!!', { timeOut: 3000 });
      }

    }).
      fail(function (response) {
        toastr.error('Servicio no disponible intentalo luego.', 'Error!!', { timeOut: 3000 });
      });

  });

  $("#tb_courses").on('click', '.coursesEliminar', function(event) {
    event.preventDefault();
      var r = confirm("¿Está seguro que desea eliminar este registro de forma permanente?");
      if (r == true) {
        _td = $(this);
        var _data = functions.getDataTable(_td);
        var url = _td.attr('href');
        $.get(url).done( function(response){
        if (response == 1){
          toastr.success('Instancia eliminado correctamente.', 'Estupendo!!!', {timeOut: 3000});
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

  $("#tb_courses").on('click', '.courseshow', function (event) {
    event.preventDefault();
    _td = $(this);
    var codigo = null;
    var _data = functions.getDataTable(_td);
    var url = _td.attr('href');
    $.get(url).done(function (response) {
      $('#programCreateForm')[0].reset();
      console.log(JSON.parse(response));
      $.each(JSON.parse(response), function (key, value) {
        if (key == 'codigo') {
          codigo = value;
          $('select#programa option[value='+value.toString().substr(0, 5)+']').attr('selected','selected');
          console.log(value.toString().substr(5));
          $('input[name="' + key + '"]').val(value.toString().substr(5));
        } else {
          $('input[name="' + key + '"]').val(value);
        }
        $('input[name="codigo_forma"]').val(codigo);
      });
      $('#programCreateForm').attr('action', _td.attr('data-href') + _data.id);
      $('#programCreateForm').removeClass();
      $('#programCreateForm').addClass('update');
      $('#programCreateModal').modal('show');
    });
  });
  $("#programa").change(function(e){
    console.log('S');
    let codigo = $(this).val() + $('input[name="codigo"]').val();
    $('input[name="codigo_forma"]').val(codigo);
  });

  $('input[name="codigo"]').blur(function(e){
    let codigo =  $('#programa').val() + $(this).val();
    $('input[name="codigo_forma"]').val(codigo);
  });