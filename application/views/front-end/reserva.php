<link href="<?php echo base_url(); ?>assets/components/glDatePicker/styles/glDatePicker.default.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>assets/css/calendar.css" rel="stylesheet" type="text/css"/>

<script src="<?php echo base_url(); ?>assets/components/glDatePicker/glDatePicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/reserva_validar.js"></script>
<script src="<?php echo base_url(); ?>assets/js/reserva_tooltips.js"></script>
<body
    <!-- Formulario -->
    <?php
    echo form_open("reserva/validar", array('id' => 'frm'));

    /*     * ************************************************************************** */
    //echo validation_errors();
    //Nombre
    echo form_label("Nombre: ", "nombre");

    $data = array(
        "name" => 'nombre',
        "id" => 'nombre',
        "value" => "Leonardo"
    );
    echo form_input($data) . "<br/>";
    /*     * ******************************************************************** */
    //Apellido
    echo form_label("Apellido: ", "apellido");

    $data = array(
        "name" => 'apellido',
        "id" => 'apellido',
        "value" => "Collazo"
    );
    echo form_input($data) . "<br/>";
    /*     * ******************************************************************** */
    //Email
    echo form_label("Email: ", "email");

    $data = array(
        "name" => 'email',
        "id" => 'email',
        "value" => "unscathd21@hotmail.com",
        "placeholder" => "ejemplo@servidor.com"
    );
    echo form_input($data) . "<br/>";
    
    //Num personas
    echo form_label("Reserva para ", "num_personas");
    
     $data = array(
        "name" => 'num_personas',
        "id" => 'num_personas',
        "value" => "3",
        "placeholder" => "",
         "maxlength" => "2"
    );
    echo form_input($data)." personas"."<br/>";

    /*     * ******************************************************************** */
    //Observaciones
    echo form_label("Observaciones: ", "observaciones");
    echo form_textarea(
            array(
                "name" => "observaciones",
                "id" => "observaciones",
                "value" => "Mi mnesjae!a010!",
                "placeholder" => "Haznos llegar cualquier indicacion, o sugerencia "
                . "que veas necesario para el dia de la reserva"
            )
    ) . "<br/>";
    /*     * ******************************************************************** */
    // Telefono
    echo form_label("Telefono: ", "telefono");
    echo form_input(
            array(
                "name" => "telefono",
                "id" => "telefono",
                "value" => "93182831"
            )
    ) . "<br/>";
    
    //Hora y tiempo
    ?> 
    <div>
        Hora<select name="hora">
            <option></option>
            <?php 
                for($i =0; $i <= 23; $i++){
                    if($i<10){
                        $valor = "0".$i;
                    }else{$valor = $i; }
                    echo "<option value='".$valor."'>".$valor."</option>"; 
                }
            ?>
        </select>
        :<select name="minuto">
            <option></option>
            <option value="00">00</option>
            <?php
                for($i = 10; $i <= 60; $i += 10){
                    echo "<option value='".$i."'>".$i."</option>";
                }
            ?>
        </select>
        <span><small>*La hora es orientativa</small></span>
    </div>
    
    <!-- Turno -->
    Turno
    <select name="turno" id="turno">
        <?php
            $turnos = $this->reservasmanager->getTurnos();
            echo "<option></option>";
            foreach($turnos as $num => $turno){
                echo "<option value='".($num+1)."'>".ucfirst($turno)."</option>";
            }
        ?>
    </select>
    
    <?php
    //Calendario #calendar
    echo form_label("Fecha: ", "fecha");
    echo form_input(
            array(
                "name" => "fecha",
                "id" => "fecha",
                "value" => "",
                "readonly" => ""
            )
    ) . "<br/>";
    
    ?>
    <!-- BEG Calendario -->
        <script charset="utf-8">
        $(document).ready(function(){
            
        })
    $('#fecha').glDatePicker(
            {
                // Style to use for the calendar.  This name must match the name used in
                // the stylesheet, using the class naming convention "gldp-cssName".
                cssName: 'default',
                // The z-index for the calendar control.
                zIndex: 1000,
                // Thickness of border (in pixels)
                borderSize: 1,
                // The number of pixels to offset the calendar's position on the page.
                calendarOffset: {x: 0, y: 1},
                // Set to true if you want the calendar to be visible at all times.
                // NOTE: If your target element is hidden, the calendar will be hidden as well.
                showAlways: true,
                // Hide the calendar when a date is selected (only if showAlways is set to false).
                hideOnClick: false,
                // Allow selection of months by clicking on the month in the title.
                allowMonthSelect: false,
                // Allow selection of years by clicking on the year in the title.
                allowYearSelect: false,
                // The date that will be treated as 'today'.
                todayDate: new Date(),
                // The date that will appear selected when the calendar renders.
                // By default it will be set to todayDate.
                selectedDate: null,
                // Arrows used for the Previous and Next month buttons on the title.
                // Set these to blank to hide the arrows completely.
                prevArrow: '\u25c4',
                nextArrow: '\u25ba',
                // A collection of dates that can be selectable by the user.
                // The dates can be a one-time selection or made repeatable by setting
                // the repeatYear or repeatMonth flag to true.
                // By default repeatYear and repeatMonth are false.
                //
                // This example creates 4-individual dates that can be selected;
                // The first date will repeat every year, the second date will repeat every
                // month and year, the third date will repeat every month and the fourth date
                // will only be selectable one-time and not repeat:
                //
                //    selectableDates: [
                //        { date: new Date(0, 8, 5), repeatYear: true },
                //        { date: new Date(0, 0, 14), repeatMonth: true, repeatYear: true },
                //        { date: new Date(2013, 0, 24), repeatMonth: true },
                //        { date: new Date(2013, 11, 25) },
                //    ]
                selectableDates: null,
                // A collection of date ranges that are selectable by the user.
                // The ranges can be made to repeat by setting repeatYear to true
                // (repeatMonth is not supported).
                //
                // This example will create 3-sets of selectable date ranges with
                // specific from and to ranges.  The 4th and 5th ranges don't specify
                // the "to" date in which case the "to" date will be the maximum days for
                // the month specified in "from".  The 4th and 5th ranges also repeat every year:
                //
                //     selectableDateRange: [
                //         { from: new Date(2013, 1, 1), to: newDate (2013, 2, 1) },
                //         { from: new Date(2013, 4, 1), to: newDate (2013, 8, 1) },
                //         { from: new Date(2013, 7, 10), to: newDate (2013, 9, 10) },
                //         { from: new Date(0, 8, 10), repeatYear: true }
                //         { from: new Date(0, 9, 1), repeatYear: true }
                //     ]
                selectableDateRange: [
                    {from: new Date(<?php echo $h->format('Y') ?>, <?php echo $h->format('m') - 1 ?>, <?php echo $h->format('d') ?>),
                        to: new Date(<?php echo $d->format("Y") ?>, <?php echo $d->format("m") - 1 ?>, <?php echo $d->format("d") ?>)}
                ],
                // Mark certain dates as special dates.  Similar to selectableDates, this
                // property supports both repeatYear and repeatMonth flags.
                // Each special date can be styled using custom style names and can have
                // data attached to it that will be returned in the onClick callback.
                // The data field can be any custom (JSON style) object.
                //
                // This example creates two (repeatable by year) dates with special data in them.
                // The first date also assigns a special class (which you will have to define).
                //    specialDates: [
                //        {
                //            date: new Date(0, 8, 5),
                //            data: { message: 'Happy Birthday!' },
                //            repeatYear: true,
                //            cssClass: 'special-bday'
                //        },
                //        {
                //            date: new Date(2013, 0, 8),
                //            data: { message: 'Meeting every day 8 of the month' },
                //            repeatMonth: true
                //        }
                //    ]
                specialDates: null,
                // List of months that can be selectable, including when the user clicks
                // on the title to select from the dropdown.
                // This example only makes two months visible; September and December:
                //    selectableMonths: [8, 11]
                selectableMonths: null,
                // List of selectable years.  If not provided, will default to 5-years
                // back and forward.
                // This example only allows selection of dates that have year 2012, 2013, 2015
                //    selectableYears: [2012, 2013, 2015]
                selectableYears: null,
                // List of selectable days of the week.  0 is Sunday, 1 is Monday, and so on.
                // This example allows only Sunday, Tuesday, Thursday:
                //    selectableDOW: [0, 2, 4]
                selectableDOW: [1, 2, 3, 4, 5, 6],
                // Names of the month that will be shown in the title.
                // Will default to long-form names:
                //     January, February, March, April, May, June, July,
                //     August, September, October, November, December
                monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                // Names of the days of the Week that will be shown below the title.
                // Will default to short-form names:
                //     Sun, Mon, Tue, Wed, Thu, Fri, Sat
                dowNames: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
                // The day of the week to start the calendar on.  0 is Sunday, 1 is Monday and so on.
                dowOffset: 1, // Primer dia de la semana: Lunes
                // Callback that will trigger when the user clicks a selectable date.
                // Parameters that are passed to the callback:
                //     el : The input element the date picker is bound to
                //   cell : The cell on the calendar that triggered this event
                //   date : The date associated with the cell
                //   data : Special data associated with the cell (if available, otherwise, null)
                onClick: (function(el, cell, date, data) {
                    el.val(date.toLocaleDateString());
                }),
                // Callback that will trigger when the user hovers over a selectable date.
                // This callback receives the same set of parameters as onClick.
                onHover: function(el, cell, date, data) {
                },
                // Callback that will trigger when the calendar needs to show.
                // You can use this callback to animate the opening of the calendar.
                onShow: function(calendar) {
                    calendar.show();
                },
                // Callback that will trigger when the calendar needs to hide.
                // You can use this callback to animate the hiding of the calendar.
                onHide: function(calendar) {
                    calendar.hide();
                }
            });
    </script> 
    <!-- END Calendario -->

    <?php
    //Final formulario
    //echo form_submit("reservar", "Reservar");
    ?>
    <input id="submit_reservar" type="submit" value="Reservar"/>
    <?php
    echo form_close();
    ?>
</div>


</body>
</html>
