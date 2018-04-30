//column decides which column the sort is based on
//type can either be 'string' or 'date' (if the column stores dates use 'date')
function sortTable(column, type) {
    var ascending = true;
    var table = document.getElementById("eventTable");
    var tbody = document.querySelector("#eventTable tbody");
    // get trs as array for ease of use
    // using two arrays to make it simpler to determine what order the table should be sorted
    var rows = [].slice.call(tbody.querySelectorAll("tr"));
    var newRows = [].slice.call(tbody.querySelectorAll("tr"));

    //sorts table in ascending order
    //format cell to compare strings
    if(type == 'string') {
        newRows.sort(function(a,b) {
            a = a.cells[column].innerHTML.toLowerCase();
            b = b.cells[column].innerHTML.toLowerCase();
            if(a > b) return 1;
            if(a < b) return -1;
            return 0;
        });
    }
    //format cell to compare dates
    else if(type == 'date') {
        newRows.sort(function(a,b) {
            a = new Date(a.cells[column].innerHTML.trim());
            b = new Date(b.cells[column].innerHTML.trim());
            if(a > b) return 1;
            if(a < b) return -1;
            return 0;
        });
    }

    //checks to see if the sorted array is equal to the original array
    //if it isnt equal it is not in ascending order (sorted array is sorted in ascending order)
    for (var i = 0, l = rows.length; i < l; i++) {
        if (rows[i] instanceof Array && newRows[i] instanceof Array) {
            if (!rows[i].compare(newRows[i])) {
                ascending = false;
                break;
            }
        }
        else if (rows[i] !== newRows[i]) {
            ascending = false;
            break;
        }
    }

    //if the array is already in ascending order, reverse the array to get descending order
    if(ascending == true) {
        ascending = !ascending;
        newRows.reverse();
    }
    //ascending == false
    else {
        ascending = !ascending;
    }

    //formats table to be in the sorted order
    for(var i=0; i<newRows.length; i++) {
        tbody.appendChild(newRows[i]);
    }
}
