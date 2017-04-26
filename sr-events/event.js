jQuery(document).ready(function($) {
    $.datepicker.regional['de'] = {
		closeText: 'Done',
		prevText: 'Prev',
		nextText: 'Next',
		currentText: 'heute',
		monthNames: ['Januar','Februar','März','April','Mai','Juni',
		'Juli','August','September','Oktober','November','Dezember'],
		monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
		'Jul','Aug','Sep','Okt','Nov','Dez'],
		dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
		dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
		dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
		weekHeader: 'KW',
		dateFormat: 'dd.mm.yy',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
        $(".custom_date").datepicker( $.datepicker.regional[ "de" ] );
        $('.custom_date').datepicker('option', 'dateFormat', 'dd-M-yy');
  
    });