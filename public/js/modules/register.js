
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
      $.get(getUri + '/panel/students/check', {'usuario' : usuario}).done( function(r) {
        var response = JSON.parse(r);
        if( response.message == 0 || response.message == '0') {
          console.log(response.message);
          $('#userCreateForm').attr('action', getUri + '/panel/students');
          $('#userCreateForm')[0].reset();
          $("#userCreateModal input[name='usuario']").val(usuario).attr('readonly', true);
          $("#userCreateModal").modal('show');
          flag = true;
          _this.removeClass('create');
        } else {
          $.post(getUri + _this.attr('action'), userForm)
          .done( function(r){
            var response = JSON.parse(r);
            if (response.message == 1){
              console.log(response);
              table.row.add(response.register).draw(false);
              toastr.success('Matricula registrada correctamente.', 'Estupendo!!!', {timeOut: 3000});
              $("#registerCreateModal").modal('hide');
            } else if(response.message == 2) {
              console.log(_td);
              table.row(_td.parents('tr')).data(response.register);
              toastr.success('Matricula registrada correctamente.', 'Estupendo!!!', {timeOut: 3000});
              $("#registerCreateModal").modal('hide');
            } else if(response.message == 3) {
              toastr.info('Ya se esta matriculado', 'Info', {timeOut: 3000});
            }else {
              console.log('0');
            }
          })
          .fail(function(response){
            toastr.error('Servicio no disponible intentalo luego.', 'Error!!', {timeOut: 3000});
          });
        }
      });
    } else {
      $.post(getUri + _this.attr('action'), userForm)
          .done( function(r){
            var response = JSON.parse(r);
            if (response.message == 1){
              console.log(response);
              table.row.add(response.register).draw(false);
              toastr.success('Matricula registrada correctamente.', 'Estupendo!!!', {timeOut: 3000});
              $('#registerCreateModal').modal('hide');
            } else if(response.message == 2) {
              console.log(_td);
              table.row(_td.parents('tr')).data(response.register);
              toastr.success('Matricula actualizada correctamente.', 'Estupendo!!!', {timeOut: 3000});
              $('#registerCreateModal').modal('hide');
            } else if(response.message == 3) {
              toastr.info('Ya se esta matriculado', 'Info', {timeOut: 3000});
            }else {
              console.log('0');
            }
          })
          .fail(function(response){
            toastr.error('Servicio no disponible intentalo luego.', 'Error!!', {timeOut: 3000});
          });
    }
  });

  $("#tb_register").on('click', '.registerShow', function(event){
    event.preventDefault();
    _td = $(this);
    var _data = functions.getDataTable(_td);
    var url = _td.attr('href');
    $.get(url).done(function(response){
      $('#userCreateForm')[0].reset();

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
  var _data = functions.getDataTable(_td);
  var url = _td.attr('href');
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
$("#userCreateForm").on( "submit", function( event ) {
  event.preventDefault();
  var userForm = $(this).serialize();
  $.post($(this).attr('action'), userForm).done( function(r){
    var response = JSON.parse(r);
    if (response.message == 1){
      toastr.success('Usuario registrado correctamente.', 'Estupendo!!!', {timeOut: 3000});
      $("#userCreateModal").modal('hide');
    } else if(response.message == 2) {
      //table.row(_td.parents('tr')).data(response.user);
      toastr.success('Usuario actualizado correctamente.', 'Estupendo!!!', {timeOut: 3000});
      $("#userCreateModal").modal('hide');
    }else {
      console.log('0');
    }
  }).
  fail(function(response){
    toastr.error('Servicio no disponible intentalo luego.', 'Error!!', {timeOut: 3000});
  });

});

$("#tb_register").on('click', '.showUserRegister', function(event){
  event.preventDefault();
  var url = getUri + "/panel/students/email";
  $.get(url, {'usuario' : $(this).attr('data-usuario')}).done(function(response){
    var r = JSON.parse(response);
    $('#userCreateForm').attr('action', getUri + '/panel/students/update/' + r.id);
    $.each( r, function( key, value ) {
      console.log(value);
      $('input[name="'+key+'"]').val(value);
    });
    $("#userCreateForm input[name='usuario']").attr('readonly', true);
    $('#userCreateModal').modal('show');
  });
});
$('#curso').on('change', function(e){
  var a = $(this).val().substring(0, 1);
  console.log(a);
  $("#instancia > option").each(function(){
    if($(this).val() == a) {
      console.log($(this).val());
      $(this).attr("selected","selected");
    }
  });
})
$(function(){
  functions.getCourses();
  //functions.select_search("#curso");

});