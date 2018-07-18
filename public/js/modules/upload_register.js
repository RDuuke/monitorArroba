var formOk = new Array();
//var formAlert = new Array();

$("#formFile").on('submit', function(event){
    event.preventDefault();
    var _this = $(this);
    var form = new FormData(_this[0]);
    console.log(form);
    $.ajax({
        type : "POST",
        url : _this.attr('action'),
        cache: false,
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data',
        data: form
    }).done(response => {
        let i = 0, a = 0;
        response = JSON.parse(response);
        console.log(response);
        $("#tableResult tbody").html("");
        $.each(response.creators, function(key, value) {
            $("#tableResult tbody").append("<tr class='creators'>"+
                    "<td>"+value.curso+"</td>"+
                    "<td>"+value.instancia+"</td>"+
                    "<td>"+value.usuario+"</td>"+
                    "<td>"+value.rol+"</td>"+
                    "<td>"+value.message+"</td>"+
                    "</tr>");
            formOk[i] = {'curso' : value.curso, 'instancia' : value.instancia, 'usuario' : value.usuario, 'rol' : value.rol};
            i++;
        });
        $.each(response.alerts, function(key, value) {
            $("#tableResult tbody").append("<tr class='alerts'>"+
                "<td>"+value.curso+"</td>"+
                "<td>"+value.instancia+"</td>"+
                "<td>"+value.usuario+"</td>"+
                "<td>"+value.rol+"</td>"+
                "<td>"+value.message+"</td>"+
                "</tr>");
            //formAlert[a] = {'curso' : value.curso, 'instancia' : value.instancia, 'usuario' : value.usuario, 'rol' : value.rol};
            //a++;
        });

        $.each(response.errors, function(key, value) {
            $("#tableResult tbody").append("<tr class='errors'>"+
                "<td>"+value.curso+"</td>"+
                "<td>"+value.instancia+"</td>"+
                "<td>"+value.usuario+"</td>"+
                "<td>"+value.rol+"</td>"+
                "<td>"+value.message+"</td>"+
                "</tr>");
        });
        $("#registros").html(response.totalR);
        $("#totalR").html(response.totalC);
        $("#totalA").html(response.totalA);
        $("#totalE").html(response.totalE);
        $(".fadein").fadeIn();
        $("form")[0].reset();

    }).fail(error => {

    });
});
$(".download_archive").on('click', function(event){
    event.preventDefault();
    var tbl = document.getElementById("tableResult");
    var wb = XLSX.utils.table_to_book(tbl);
    var wopts = { bookType:'xlsx', bookSST:false, type:'array' };
    var wbout = XLSX.write(wb,wopts);
    saveAs(new Blob([wbout],{type:"application/octet-stream"}), "usuarios_no_registrados.xlsx");

});
$("#cargar").on('click', function(event){
    let _this = $(this);
    event.preventDefault();
    if (formOk.length == 0) {
        toastr.error('No hay registros para cargar.', 'Error', {timeOut: 3000});
        return false;
    }
    functions.proccess(formOk, _this.attr('data-action'));
    return true;
});