$(function(){
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
    }
});