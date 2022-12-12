require('admin-lte');
const Swal = require('sweetalert2');

import bsCustomFileInput from 'bs-custom-file-input';
$(document).ready(function () {
    window.bsCustomFileInput = bsCustomFileInput;
    window.bsCustomFileInput.init()
});
