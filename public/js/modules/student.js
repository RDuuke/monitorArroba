
$("#userCreateForm").on( "submit", function( event ) {
  event.preventDefault();
  var userForm = $(this).serialize();
  $.post($(this).attr('action'), userForm).done( function(response){
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
    toastr.error('Servicio no disponible intentalo luego.' + response.responseText, 'Error!!', {timeOut: 3000});
  });

});
$("#tb_user").on('click', '.studentEliminar', function(event) {
    event.preventDefault();
    let url = null, r = null;
    let _td = $(this);
    let _data = functions.getDataTable(_td);
    if ($("#isA").val() === "05") {
        r = confirm("Recuerda seleccionar la institución \n" +
            "¿Está seguro que desea archivar este registro de forma permanente?");
            if ($("#codigo_institucion").val() === "") {
                alert("Recuerda seleccionar la institución");
                return false;
            }
        url = _td.attr('href') + _data.id + "/" + $("#codigo_institucion").val();
    } else {
        r = confirm("¿Está seguro que desea archivar este registro de forma permanente?");
    }
    if (r === true) {
        $.get(url).done( function(response){
        if (response.response === 1){
          toastr.success('Estudiante eliminado correctamente.', 'Estupendo!!!', {timeOut: 3000});
          table
            .row( _td.parents('tr') )
            .remove()
            .draw();
        } else if ( response.response === 2) {
            toastr.info(
                response.message,
            {timeOut: 3000});
        }
        }).
        fail(function(response){
            toastr.error(response.responseText, 'Error!!', {timeOut: 3000});
        });
      } else {
        return false;
      }
});
$("#tb_user").on('click', '.studentShow', function(event){
    event.preventDefault();
    _td = $(this);
    var _data = functions.getDataTable(_td);
    var url = _td.attr('href') + _data.id
    $('#userCreateForm')[0].reset();
    $('.checkbox').attr("checked", false);
    $.get(url).done(function(response){
      $.each(response, function( key, value ) {
          if (key == 'institucion_id' && value != ''){
              $('#institucion_user option[value='+value+']').attr('selected','selected');
          } else if ( key == 'genero' && value != "") {
              $("select#genero option[value="+value+"]").prop("selected", true);
          } else if (key == 'usuario') {
              $('input[name="'+key+'"]').val(value);
              $('input[name="'+key+'"]').attr('readonly', 'readonly');
          } else if (key == 'pertenece') {
              console.log(value);
              if(value.length > 0) {
                  value.forEach( function (i) {
                      $('.checkbox[value="'+i.codigo+'"]').attr("checked", true);
                  });
              }
          }
          else {
              $('input[name="'+key+'"]').val(value);
          }
          $('#userCreateForm').attr('action', _td.attr('data-href') + _data.id);
        $('#userCreateModal').modal('show');
      });
    });
});

$("#tb_user").on('click', '.studentChangePaswword', function(event){
    var r = confirm("Ten encuenta que si el usuario esta en otras instituciones en el campus, la contraseña se actualizara para todas \n" +
        "¿Está seguro que desea restablecer la contraseña?");
    if (r == true) {

        event.preventDefault();
        _td = $(this);
        var _data = functions.getDataTable(_td);
        var url = _td.attr('href') + _data.id
        $.get(url).done(function(response){
            functions.removeToast();
            if (response.response == 1) {
                toastr.success(response.message, 'Finalizado', {timeOut : 3000});
            } else {
                toastr.error(response.message, 'Error', {timeOut : 3000});
            }
        });
    } else {
        return false;
    }
});

$("#tb_user").on('click', '.studentArchive', function(event){
    let url = null, r = null;
    let _td = $(this);
    let _data = functions.getDataTable(_td);
    if ($("#isA").val() === "05") {
        r = confirm("Recuerda seleccionar la institución \n" +
            "¿Está seguro que desea archivar este registro de forma permanente?");
        url = _td.attr('href') + _data.id + "/" + $("#codigo_institucion").val();
    } else {
        r = confirm("¿Está seguro que desea archivar este registro de forma permanente?");
        url = _td.attr('href') + _data.id;
    }
    if (r == true) {
        event.preventDefault();
        $.get(url).done(function(response){
            functions.removeToast();
            console.log(response);
            if (response.response == 1) {
                toastr.success(response.message, 'Finalizado', {timeOut : 3000});
                table
                    .row( _td.parents('tr') )
                    .remove()
                    .draw();
            } else if(response.response == 2) {
                toastr.info(response.message, 'Info', {timeOut : 3000});

            }
            else {
                toastr.error(response.message, 'Error', {timeOut : 3000});
            }
        });
    } else {
        return false;
    }
});
$("#tb_user").on('click', '.searchRegister', function(event){
  let n = 0;
  event.preventDefault();
  _td = $(this);
  var _data = functions.getDataTable(_td);
  $("#name_student").html(_data.nombres + ' ' + _data.apellidos);
  var url = _td.attr('href') + _data.id
  $.get(url).done(function(response){
      $("#content_student_register").html("");
      $("#content_student_register").html(response);
      $("#studentRegister").modal('show');
  });

});
$("#content_student_register").on("click", ".archive_register", function(event){
  event.preventDefault();
  var r = confirm("¿Está seguro que quiere desmatricular?");
  if (r == true) {
    _this = $(this);
    //$("#name_student").html(data.nombres + ' ' + data.apellidos);
    $.get($(this).attr('href')).done(function (r){

      if (r.message == 1 || r.message == '1') {
        _this.parent().parent().remove();
        toastr.success("Matricula archivada", "¡¡ Estupendo!!", {timeOut: 3000});
      }
    });
  } else {
    return false;
  }
});
$(".addstudent").on('click', function(event){
    event.preventDefault();
    $('#userCreateForm')[0].reset();
    $('#userCreateForm').attr('action',$(this).attr('data-href'));
    $('#userCreateForm input[name="usuario"]').removeAttr('readonly');
    $('#userCreateModal').modal('show');
});
