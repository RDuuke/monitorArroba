var formOk = new Array;
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
        $("#tableResult tbody").html("");
        let i = 0;
        response = JSON.parse(response);
        $.each(response.creators, function(key, value) {
            $("#tableResult tbody").append("<tr class='creators'>"+
                    "<td>"+value.usuario+"</td>"+
                    "<td>"+value.clave+"</td>"+
                    "<td>"+value.nombres+"</td>"+
                    "<td>"+value.apellidos+"</td>"+
                    "<td>"+value.correo+"</td>"+
                    "<td>"+value.documento+"</td>"+
                    "<td>"+value.genero+"</td>"+
                    "<td>"+value.institucion+"</td>"+
                    "<td>"+value.ciudad+"</td>"+
                    "<td>"+value.departamento+"</td>"+
                    "<td>"+value.pais+"</td>"+
                    "<td>"+value.telefono+"</td>"+
                    "<td>"+value.celular+"</td>"+
                    "<td>"+value.direccion+"</td>"+
                    "<td>"+value.message+"</td>"+
                    "</tr>");

                     formOk[i] = {"usuario": value.usuario, "clave": value.clave, "nombres": value.nombres, "apellidos": value.apellidos, "correo": value.correo, "documento": value.documento, "genero": value.genero, "institucion": value.institucion, "ciudad": value.ciudad, "departamento": value.departamento, "pais": value.pais, "telefono": value.telefono, "celular": value.celular, "direccion": value.direccion};
                     i++;
                });
        $.each(response.errors, function(key, value) {
            $("#tableResult tbody").append("<tr class='errors'>"+
                    "<td>"+value.usuario+"</td>"+
                    "<td>"+value.clave+"</td>"+
                    "<td>"+value.nombres+"</td>"+
                    "<td>"+value.apellidos+"</td>"+
                    "<td>"+value.correo+"</td>"+
                    "<td>"+value.documento+"</td>"+
                    "<td>"+value.genero+"</td>"+
                    "<td>"+value.institucion+"</td>"+
                    "<td>"+value.ciudad+"</td>"+
                    "<td>"+value.departamento+"</td>"+
                    "<td>"+value.pais+"</td>"+
                    "<td>"+value.telefono+"</td>"+
                    "<td>"+value.celular+"</td>"+
                    "<td>"+value.direccion+"</td>"+
                    "<td>"+value.message+"</td>"+
                    "</tr>");
        });
        $("#registros").html(response.totalr);
        $("#totalR").html(response.totalc);
        $("#totalE").html(response.totale);
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
    saveAs(new Blob([wbout],{type:"application/octet-stream"}), "usuarios_analizados.xlsx");
});
$("#cargar").on('click', function(event){
    let _this = $(this);
    event.preventDefault();
    console.log(formOk);
    toastr.info('Cargando usuarios.', 'Cargando...', {timeOut: 3000});
    $.ajax({
       method : "POST",
       url : _this.attr('data-action'),
       enctype: 'multipart/form-data',
       data: {data : formOk}
   }).done(function(response){
        toastr.success('Carga terminada.', 'Finalizado', {timeOut: 3000});
   });
});