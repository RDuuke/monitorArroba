$(function(){
    functions = {
        getDataTable : function (tr) {
            return table.row(tr.parents('tr')).data();
        }
    }
});