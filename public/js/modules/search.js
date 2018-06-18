
$("#box-search").keypress(function(e){
    let param = $(this).val();
    let route = $(this).attr('route-get');
    let route_item = $(this).attr('base-route-item');
    let type = $(this).attr("tipo");
    if (param == '') {
        $("#list_result").html('');
        return true;
    }
    let result = functions.search(route + param);
    //console.log(result);
    $("#load").removeClass('none').addClass('block');
    functions.render("#list_result", result, route_item, type);
    $("#load").removeClass('block').addClass('none');
});
