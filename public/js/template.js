$(".parent").on('click', function (e) {
   e.preventDefault();
    if($(this).parent('.dropdown-1') != false) {
        $(".dropdown-2").removeClass('active-2');
        $(this).next().addClass('active-2').slideToggle();
        $(".dropdown-2:not(.active-2)").slideUp();
    } else {
        $(".dropdown-1").removeClass('active');
        $(this).next().addClass('active').slideToggle();
        $(".dropdown-1:not(.active)").slideUp();
    }
});
$(".download-data").on('click', function (e) {
   e.preventDefault();
   $(".dt-buttons > .buttons-excel").trigger('click');
});

$("#codigo_institucion").change(function(e){
    console.log('S');
    $('input[name="codigo_forma"]').html("");
    let codigo = $("#instance").val() + $(this).val() + $('input[name="codigo_program"]').val();
    $('input[name="codigo_forma"]').val(codigo);
});

$("#instance").change(function(e){
    console.log('S');
    $('input[name="codigo_forma"]').html("");
    let codigo = $(this).val() + $("#codigo_institucion").val() + $('input[name="codigo_program"]').val();
    $('input[name="codigo_forma"]').val(codigo);
});

$('input[name="codigo_program"]').blur(function(e){
    $('input[name="codigo_forma"]').html("");
    let codigo =  $("#instance").val() + $('#codigo_institucion').val() + $(this).val();
    $('input[name="codigo_forma"]').val(codigo);
});

$("#id_programa").change(function(e){
    console.log('S');
    let codigo = $(this).val() + $('input[name="codigo"]').val();
    $('input[name="codigo_forma"]').val(codigo);
});

$('input[name="codigo"]').blur(function(e){
    let codigo =  $('#id_programa').val() + $(this).val();
    $('input[name="codigo_forma"]').val(codigo);
});
