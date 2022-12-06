define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
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
                        {field: 'PLANT', title: __('Plant'), operate: 'LIKE'},
                        {field: 'PLC_ID', title: __('Plc_id'), operate: 'LIKE'},
                        {field: 'WORKCenter', title: __('Workcenter'), operate: 'LIKE'},
                        {field: 'CTDATE', title: __('Ctdate'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'PARTS', title: __('Parts'), operate: 'LIKE'},
                        {field: 'QUANTITY', title: __('Quantity')},
                        {field: 'SHIFT', title: __('Shift'), operate: 'LIKE'},
                        {field: 'Num', title: __('Num')},
                        {field: 'CT', title: __('Ct')},
                        {field: 'SapCT', title: __('Sapct')},
                        {field: 'DiffCT', title: __('Diffct')},
                        {field: 'DiffPer', title: __('Diffper')},
                        {field: 'Status', title: __('Status'), operate: 'LIKE', formatter: Table.api.formatter.status},
                        {field: 'PLC_Name', title: __('Plc_name'), operate: 'LIKE'},
                        {field: 'UAPno', title: __('Uapno'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
