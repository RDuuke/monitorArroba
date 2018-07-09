$(function(){
    let data = null;
    functions = {
        getDataTable : function (tr) {
            return table.row(tr.parents('tr')).data();
        },
        getCourses : function() {
            $.get(getUri + "/panel/register/courses").done(function(response){
                $.each(JSON.parse(response), function(key, value){
                    $("#curso").append("<option value='"+value.codigo+"'>"+value.nombre+"</option>")
                });
            });
        },
        search : function (param) {
            $.get(getUri + param ).done( function(response){
                data = response;
            });
            return data;
        },
        render : function(tag, data, route="", type="") {
            switch(type){
                case 'list':
                    $(tag).html("");
                    $.each(JSON.parse(data), function(key, value){
                        $(tag).append("<li class='list-group-item list-group'><a href='"+getUri+ route + value.codigo + "'>"+ value.nombre +"</a></li>");
                    });
                    break;
                    case 'table':
                    break;
                    case 'students':
                    $(tag).html("");
                    $.each(JSON.parse(data), function(key, value){
                        $(tag).append("<li class='list-group-item list-group'><a href='"+getUri+ route + value.id + "'>"+ value.nombres + " " + value.apellidos +"  - "+ value.documento +" - "+value.usuario+"</a></li>");
                    });
                break;
            }
        }
    }
});