
$("#box-search").keypress(function(e){
    let param = $(this).val();
    if (param == '') {
        param = '%';
    }
    let result = functions.search(param);
    //console.log(result);
    $("#load").removeClass('none').addClass('block');
    functions.render("#list_result", result);
    $("#load").removeClass('block').addClass('none');
});
