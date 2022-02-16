import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['table'];
    static values = {
        locale: String
    };

    connect() {
        console.log(this.localeValue);
//        this.sortTable('table', 0);
    }

    sortTable(table_id, sortColumn){
        var tableData = document.getElementById(table_id).getElementsByTagName('tbody').item(0);
        var rowData = tableData.getElementsByTagName('tr');            
        for(var i = 0; i < rowData.length - 1; i++){
            for(var j = 0; j < rowData.length - (i + 1); j++){
                if(Number(rowData.item(j).getElementsByTagName('td').item(sortColumn).innerHTML.replace(/[^0-9\.]+/g, "")) < Number(rowData.item(j+1).getElementsByTagName('td').item(sortColumn).innerHTML.replace(/[^0-9\.]+/g, ""))){
                    tableData.insertBefore(rowData.item(j+1),rowData.item(j));
                }
            }
        }
    }    
}
