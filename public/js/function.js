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
            $.get(getUri + "/panel/courses/search/" + param ).done( function(response){
                data = response;
            });
            return data;
        },
        render : function(tag, data, type="list") {
            switch(type){
                case 'list':
                    $(tag).html("");
                    $.each(JSON.parse(data), function(key, value){
                        $(tag).append("<li class='list-group-item list-group'><a href='" + value.id + "'>"+ value.nombre +"</a></li>");
                    });
                break;
                case 'table':
                break;
            }
        }
    }
});