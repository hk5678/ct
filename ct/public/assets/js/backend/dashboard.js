define(['jquery', 'bootstrap', 'backend', 'addtabs', 'table', 'echarts', 'echarts-theme', 'template'], function ($, undefined, Backend, Datatable, Table, Echarts, undefined, Template) {

    var Controller = {
        index: function () {
            // 基于准备好的dom，初始化echarts实例
            var myChart = Echarts.init(document.getElementById('echart'), 'walden');

            // 指定图表的配置项和数据
            var option = {
                title: {
                    text: '',
                    subtext: ''
                },
                color: [
                    "#18d1b1",
                    "#3fb1e3",
                    "#626c91",
                    "#a0a7e6",
                    "#c4ebad",
                    "#96dee8"
                ],
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: [__('Register user')]
                },
                toolbox: {
                    show: false,
                    feature: {
                        magicType: {show: true, type: ['stack', 'tiled']},
                        saveAsImage: {show: true}
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: Config.column
                },
                yAxis: {},
                grid: [{
                    left: 'left',
                    top: 'top',
                    right: '10',
                    bottom: 30
                }],
                series: [{
                    name: __('Register user'),
                    type: 'line',
                    smooth: true,
                    areaStyle: {
                        normal: {}
                    },
                    lineStyle: {
                        normal: {
                            width: 1.5
                        }
                    },
                    data: Config.userdata
                }]
            };

            // 使用刚指定的配置项和数据显示图表。
            myChart.setOption(option);

            $(window).resize(function () {
                myChart.resize();
            });

            $(document).on("click", ".btn-refresh", function () {
                setTimeout(function () {
                    myChart.resize();
                }, 0);
            });

        },
  
        recentweek:function(){
            // Controller.api.bindevent();
        },
        recentmonth:function(){
            // Controller.api.bindevent();
        },
        recentseason:function(){
            // Controller.api.bindevent();
        },
        recentyear:function(){
            // Controller.api.bindevent();
        },
        listyear:function(){
            Table.api.init({
                extend: {
                    index_url: 'tagsyearsum/index' + location.search,
                    add_url: 'tagsyearsum/add',
                    edit_url: 'tagsyearsum/edit',
                    del_url: 'tagsyearsum/del',
                    multi_url: 'tagsyearsum/multi',
                    import_url: 'tagsyearsum/import',
                    table: 'tagsyearsum',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'UAPno', title: __('Uapno'), operate: 'LIKE'},
                        // {field: 'PLANT', title: __('Plant'), operate: 'LIKE'},
                        // {field: 'PLC_ID', title: __('Plc_id'), operate: 'LIKE'},
                        {field: 'PLC_Name', title: __('Plc_name'), operate: 'LIKE'},
                        // {field: 'WORKCenter', title: __('Workcenter'), operate: 'LIKE'},
                        {field: 'CTDATE', title: __('Ctdate'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'PARTS', title: __('Parts'), operate: 'LIKE'},
                        // {field: 'QUANTITY', title: __('Quantity')},
                        // {field: 'SHIFT', title: __('Shift'), operate: 'LIKE'},
                        // {field: 'Num', title: __('Num')},
                        {field: 'CT', title: __('Ct')},
                        {field: 'SapCT', title: __('Sapct')},
                        {field: 'DiffCT', title: __('Diffct')},
                        {field: 'DiffPer', title: __('Diffper')},
                        // {field: 'Status', title: __('Status'), operate: 'LIKE', formatter: Table.api.formatter.status},


                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        listmonth: function (){

            Table.api.init({
                extend: {
                    index_url: 'tagsmonthsum/index' + location.search,
                    add_url: 'tagsmonthsum/add',
                    edit_url: 'tagsmonthsum/edit',
                    del_url: 'tagsmonthsum/del',
                    multi_url: 'tagsmonthsum/multi',
                    import_url: 'tagsmonthsum/import',
                    table: 'tagsmonthsum',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'UAPno', title: __('Uapno'), operate: 'LIKE'},
                        // {field: 'PLANT', title: __('Plant'), operate: 'LIKE'},
                        // {field: 'PLC_ID', title: __('Plc_id'), operate: 'LIKE'},
                        {field: 'PLC_Name', title: __('Plc_name'), operate: 'LIKE'},
                        // {field: 'WORKCenter', title: __('Workcenter'), operate: 'LIKE'},
                        {field: 'CTDATE', title: __('Ctdate'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'PARTS', title: __('Parts'), operate: 'LIKE'},
                        // {field: 'QUANTITY', title: __('Quantity')},
                        // {field: 'SHIFT', title: __('Shift'), operate: 'LIKE'},
                        // {field: 'Num', title: __('Num')},
                        {field: 'CT', title: __('Ct')},
                        {field: 'SapCT', title: __('Sapct')},
                        {field: 'DiffCT', title: __('Diffct')},
                        {field: 'DiffPer', title: __('Diffper')},
                        // {field: 'Status', title: __('Status'), operate: 'LIKE', formatter: Table.api.formatter.status},


                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        listseason: function(){

            Table.api.init({
                extend: {
                    index_url: 'tagsseasonsum/index' + location.search,
                    add_url: 'tagsseasonsum/add',
                    edit_url: 'tagsseasonsum/edit',
                    del_url: 'tagsseasonsum/del',
                    multi_url: 'tagsseasonsum/multi',
                    import_url: 'tagsseasonsum/import',
                    table: 'tagsseasonsum',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'UAPno', title: __('Uapno'), operate: 'LIKE'},
                        // {field: 'PLANT', title: __('Plant'), operate: 'LIKE'},
                        // {field: 'PLC_ID', title: __('Plc_id'), operate: 'LIKE'},
                        {field: 'PLC_Name', title: __('Plc_name'), operate: 'LIKE'},
                        // {field: 'WORKCenter', title: __('Workcenter'), operate: 'LIKE'},
                        {field: 'CTDATE', title: __('Ctdate'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'PARTS', title: __('Parts'), operate: 'LIKE'},
                        // {field: 'QUANTITY', title: __('Quantity')},
                        // {field: 'SHIFT', title: __('Shift'), operate: 'LIKE'},
                        // {field: 'Num', title: __('Num')},
                        {field: 'CT', title: __('Ct')},
                        {field: 'SapCT', title: __('Sapct')},
                        {field: 'DiffCT', title: __('Diffct')},
                        {field: 'DiffPer', title: __('Diffper')},
                        // {field: 'Status', title: __('Status'), operate: 'LIKE', formatter: Table.api.formatter.status},


                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        listsemester:function(){

            Table.api.init({
                extend: {
                    index_url: 'tagssemestersum/index' + location.search,
                    add_url: 'tagssemestersum/add',
                    edit_url: 'tagssemestersum/edit',
                    del_url: 'tagssemestersum/del',
                    multi_url: 'tagssemestersum/multi',
                    import_url: 'tagssemestersum/import',
                    table: 'tagssemestersum',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'UAPno', title: __('Uapno'), operate: 'LIKE'},
                        // {field: 'PLANT', title: __('Plant'), operate: 'LIKE'},
                        // {field: 'PLC_ID', title: __('Plc_id'), operate: 'LIKE'},
                        {field: 'PLC_Name', title: __('Plc_name'), operate: 'LIKE'},
                        // {field: 'WORKCenter', title: __('Workcenter'), operate: 'LIKE'},
                        {field: 'CTDATE', title: __('Ctdate'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'PARTS', title: __('Parts'), operate: 'LIKE'},
                        // {field: 'QUANTITY', title: __('Quantity')},
                        // {field: 'SHIFT', title: __('Shift'), operate: 'LIKE'},
                        // {field: 'Num', title: __('Num')},
                        {field: 'CT', title: __('Ct')},
                        {field: 'SapCT', title: __('Sapct')},
                        {field: 'DiffCT', title: __('Diffct')},
                        {field: 'DiffPer', title: __('Diffper')},
                        // {field: 'Status', title: __('Status'), operate: 'LIKE', formatter: Table.api.formatter.status},


                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        list4week:function(){

            Table.api.init({
                extend: {
                    index_url: 'tags4week/index' + location.search,
                    add_url: 'tags4week/add',
                    edit_url: 'tags4week/edit',
                    del_url: 'tags4week/del',
                    multi_url: 'tags4week/multi',
                    import_url: 'tags4week/import',
                    table: 'tags4week',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'UAPno', title: __('Uapno'), operate: 'LIKE'},
                        // {field: 'PLANT', title: __('Plant'), operate: 'LIKE'},
                        // {field: 'PLC_ID', title: __('Plc_id'), operate: 'LIKE'},
                        {field: 'PLC_Name', title: __('Plc_name'), operate: 'LIKE'},
                        // {field: 'WORKCenter', title: __('Workcenter'), operate: 'LIKE'},
                        {field: 'CTDATE', title: __('Ctdate'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'PARTS', title: __('Parts'), operate: 'LIKE'},
                        // {field: 'QUANTITY', title: __('Quantity')},
                        // {field: 'SHIFT', title: __('Shift'), operate: 'LIKE'},
                        // {field: 'Num', title: __('Num')},
                        {field: 'CT', title: __('Ct')},
                        {field: 'SapCT', title: __('Sapct')},
                        {field: 'DiffCT', title: __('Diffct')},
                        {field: 'DiffPer', title: __('Diffper')},
                        // {field: 'Status', title: __('Status'), operate: 'LIKE', formatter: Table.api.formatter.status},


                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);   
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
  };
    return Controller;
});
