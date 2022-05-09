<link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/dataTables.bootstrap4.min.css') }}">

<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
        text-decoration: underline !important;
    }
    .dataTables_wrapper {
        overflow: visible;
    }
    button.dt-button, div.dt-button, a.dt-button {
        background: #FFFFFF;
        border: 1px solid #E7ECEE;
        box-sizing: border-box;
        box-shadow: 0px 4px 4px rgba(51, 51, 51, 0.04), 0px 4px 24px rgba(51, 51, 51, 0.1);
        border-radius: 5px;
    }
    button.dt-button:hover:not(.disabled), div.dt-button:hover:not(.disabled), a.dt-button:hover:not(.disabled) {
        background: #FFFFFF;
        border: 1px solid #E7ECEE;
    }
    button.dt-button:focus:not(.disabled), div.dt-button:focus:not(.disabled), a.dt-button:focus:not(.disabled) {
        background: #FFFFFF;
        border: 1px solid #E7ECEE;
    }
    div.dt-button-collection button.dt-button, div.dt-button-collection div.dt-button, div.dt-button-collection a.dt-button {
        border-radius: 5px;
    }

    .dataTables_info {
        color: #676767 !important;
    }

    .table-support th {
        border: none;
    }
</style>
