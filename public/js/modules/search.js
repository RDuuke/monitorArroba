
// TODO cambiar evento de keypess a boton de busqueda (colapsa con muchos usarios)
$("#box-search").keypress(function(e){
    let param = $(this).val();
    let route = $(this).attr('route-get');
    let route_item = $(this).attr('base-route-item');
    let type = $(this).attr("tipo");
    if (param == '') {
        $("#table_result tbody").html('');
        return true;
    }
    let result = functions.search(route + param);
    //console.log(result);
    $("#load").removeClass('none').addClass('block');
    functions.render("#table_result tbody", result, route_item, type);
    $("#load").removeClass('block').addClass('none');
    $("#table_result").removeClass("none");
});
