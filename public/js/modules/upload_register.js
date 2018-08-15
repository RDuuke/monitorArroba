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
        beforeSend: function () {
            toastr.info("Analizando el archivo... ", "Info", {timeOut: 5000000});
        },
        data: form
    }).done(response => {
        toastr.remove();
        let i = 0, a = 0;
        $("#tableResult tbody").html("");
        renderData(response.creators, 'creators', formOk);
        renderData(response.alerts, 'alerts');
        renderData(response.errors, 'errors');

        $("#registros").html(response.totalR);
        $("#totalR").html(response.totalC);
        $("#totalA").html(response.totalA);
        $("#totalE").html(response.totalE);
        $(".fadein").fadeIn();
        $("form")[0].reset();

    }).fail(error => {
        toastr.remove();
        toastr.error("Error analizando el archivo, vuelve a intentarlo", "Error", {timeOut: 5000000});

    });
});
$(".download_archive").on('click', function(event){
    event.preventDefault();
    var tbl = document.getElementById("tableResult");
    var wb = XLSX.utils.table_to_book(tbl);
    var wopts = { bookType:'xlsx', bookSST:false, type:'array' };
    var wbout = XLSX.write(wb,wopts);
    saveAs(new Blob([wbout],{type:"application/octet-stream"}), "resultado_de_analisis_anexo2.xlsx");

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

function renderData(value, classes, saveData = []) {
    let i = 0;
    $.each(value, function(key, value) {
        $("#tableResult tbody").append("<tr class='"+classes+"'>"+
            "<td>"+value.curso+"</td>"+
            "<td>"+value.instancia+"</td>"+
            "<td>"+value.usuario+"</td>"+
            "<td>"+value.rol+"</td>"+
            "<td>"+value.codigo+"</td>"+
            "<td>"+value.message+"</td>"+
            "</tr>");
        saveData[i] = {'curso' : value.curso, 'instancia' : value.instancia, 'usuario' : value.usuario, 'rol' : value.rol};
        i++;
    });
}