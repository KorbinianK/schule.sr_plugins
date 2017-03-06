jQuery(document).ready(function($) {
    $('.custom_date').datepicker({
         monthNames: ['Januar','Februar','März','April','Mai','Juni',
        'Juli','August','September','Oktober','November','Dezember'],
        monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
        'Jul','Aug','Sep','Okt','Nov','Dez'],
        dateFormat : 'dd-M-yy'
    });
});