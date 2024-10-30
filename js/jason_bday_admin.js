
$ = jQuery;
$(document).ready(function () {
    //alert("hola");

    JasonBDUtils_initDatePicker("#bday_user");
});

function JasonBDUtils_initDatePicker(field)
{
    $(field).datepicker({
        dateFormat: 'dd-mm-yy',
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles',
            'Jueves', 'viernes', 'Sabado'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
            'Agosto', 'Septiembre', 'Otubre', 'Noviembre', 'Diciembre'],
        // onSelect: callback,
        firstDay: 1
    });
}