$(function(){
    let data = null;
    functions = {
        getDataTable : function (tr) {
            return table.row(tr.parents('tr')).data();
        },
        getCourses : function() {
            $.get(getUri + "/panel/register/courses").done(function(response){
                $.each(response, function(key, value){
                    $("#curso").append("<option value='"+value.codigo+"'>"+value.nombre+"</option>")
                });
            });
        },
        searchRender : function (param, element) {
            $.get(getUri + param ).done( function(response){
                element.append(response);
            });
        },
        render : function(tag, data, route="", type="") {
            let html = "";
            switch(type){
                case 'list':
                    $.each(data, function(key, value){
                        html = html + "<tr><td>"+value.codigo+"</td><td><a href='"+getUri+ route + value.codigo + "'>"+ value.nombre +"</a></td></tr>";
                    });
                    break;
                    case 'table':
                    break;
                    case 'students':
                    $.each(JSON.parse(data), function(key, value){
                        html =  html + "<tr><td>"+value.documento+"</td><td>"+ value.nombres + "</td><td>" + value.apellidos +"</td><td>"+ value.telefono +"</td><td><a href='"+getUri+ route + value.id +"'>"+value.usuario+"</a></td></tr>";
                    });
                    break;
            }
            return html;
        },
        lowercase : function (str) {
            return str.toLowerCase();
        },

        proccess : function (data, url) {
            toastr.info('Cargando usuarios.', 'Cargando...', {timeOut: 500000});
            $.ajax({
                method : "POST",
                url : url,
                enctype: 'multipart/form-data',
                data: {data : data},
                cache: false
            }).done(function(response){
                functions.removeToast();
                toastr.success('Carga terminada.', 'Finalizado', {timeOut: 3000});
            }).fail( function (response) {
                toastr.remove();
                toastr.error("Error cargando los datos, vuelve ha intentarlo", "Error", {timeOut: 5000000});
            });
        },
        sleep : function (ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },

        removeToast : function () {
            toastr.remove();
        }

    }
});