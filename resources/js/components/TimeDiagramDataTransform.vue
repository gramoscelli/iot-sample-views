<template>
    
</template>

<script>
    var inputDataTest = {
        cells_count: 20,
        ts_start: 1000,
        ts_end: 2000,
        transitions: [
            {title: 'dIn 4',
                changes: [
                    {time:950, val:0},
                    {time:1135, val:1},
                    {time:1210, val:0},
                    {time:1250, val:1},
                    {time:1315, val:0},
                    {time:1435, val:1},
                    {time:1544, val:0},
                    {time:1578, val:1},
                    {time:1634, val:0},
                    {time:1789, val:1},
                    {time:2150, val:0}]},
            {title: 'Brazo bajo',
                changes: [
                    {time:935, val:0},
                    {time:1557, val:1},
                    {time:1827, val:0},
                    {time:2447, val:0}]}
        ]
    };
    
    export default {
        name: "TimeDiagramImportData",
        methods: {
            // cellsData: evaluate the entire dataset to show in the table
            // structure of return object:
            //          rowData[i]: it contains the data of the (e.g. prop count) samples of the row i
            //          rowData[i].sample[j]: it contains the count of 1's and 0's of the row i sample j
            //          rowData[i].sample[j].oneCount: the count of the ones (e.g. get from the changes)
            //          rowData[i].sample[j].zeroCount: the count of the zeroes (e.g. get from the changes)
            //          rowData[i].sample[j].value: 0,1 or 2
            getCellsData: function(inputData) {
                var tr_row=[];
                var cellsCount = 0;
                inputData.transitions.forEach( function(inp_trans) {
                    var cells=[];
                    inp_trans.changes.forEach( function(change) {
                        if (change.time < start) {
                            if (change.val === 0) {
                                cells.fill({oneCount: 0, zeroCount: 0, state: 0}, 0, this.slots - 1);
                            } else {
                                cells.fill({oneCount: 0, zeroCount: 0, state: 1}, 0, this.slots - 1);
                            }
                        } else {
                            var index = Math.floor(this.slots*(change.time - this.start)/(this.end - this.start));
                            if ((index >= 0) && (index < this.slots)) {
                                if (change.val === 0) {
                                    cells[index].zeroCount++;
                                    if ((cells[index].zeroCount === 1) && (cells[index].oneCount === 0)) {
                                        cells[index].state = 0;
                                    } else {
                                        cells[index].state = 2;
                                    }
                                    cells.fill({oneCount: 0, zeroCount: 0, state: 0}, index+1, this.slots - 1);
                                } else {
                                    cells[index].oneCount++;
                                    if ((cells[index].zeroCount === 0) && (cells[index].oneCount === 1)) {
                                        cells[index].state = 1;
                                    } else {
                                        cells[index].state = 2;
                                    }
                                    cells.fill({oneCount: 0, zeroCount: 0, state: 1}, index+1, this.slots - 1);
                                }
                            }
                        }
                    });
                    tr_row[cellsCount++]=cells;
                });
            }

        }
    }
</script>

<style scoped>

</style>
