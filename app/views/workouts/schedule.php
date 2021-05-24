<?php require APPROOT . '/views/inc/header.php'; ?>
<?php

// get array of dates in that week
$weekArray = array();
for ($i = 0; $i <= 6; $i++) {
    array_push($weekArray, date('Y-m-d', strtotime('+' . $i . ' day', strtotime($data['startingDate']))));
}

// array of objects turned to array of arrays for ease of access when cycling through individual 'set data' sets
$weekDataArray = array();
foreach ($data['weekData'] as $dataObject) {
    $json  = json_encode($dataObject);
    $array = json_decode($json, true);
    array_push($weekDataArray, $array);
}

// get mod select arrays
$modSelects = $data['modSelects'];

?>
<div class="container mt-5">
    <?php flash('assign_mod_success'); ?>
    <div class="row">
        <div class="offset-lg-3 offset-xs-0 col-xs-12 col-sm-12 col-lg-6 text-center">
            <h2 class="titleFont">Schedule</h2>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2 text-center">
                            <a class="link-dark" href="<?php echo URLROOT ?>/workouts/schedule/<?php echo date('Y-m-d', strtotime('-7 days', strtotime($data['startingDate']))) ?>">
                                <h1 id="numSelectCaret"><i class="fas fa-caret-left"></i></h1>
                            </a>
                        </div>
                        <div class="col-8 text-center">
                            <h2 id="exerciseNumDisplay" class="titleFont">Week of <?php echo date('F jS Y', strtotime($data['startingDate'])) ?></h2>
                        </div>
                        <div class="col-2 text-center">
                            <a class="link-dark" href="<?php echo URLROOT ?>/workouts/schedule/<?php echo date('Y-m-d', strtotime('+7 days', strtotime($data['startingDate']))) ?>">
                                <h1 id="numSelectCaret"><i class="fas fa-caret-right"></i></h1>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-lg-4 col-lg-4 offset-md-0 col-md-12 text-center">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <a href="<?php echo URLROOT ?>/workouts/results/<?php echo date('Y-m-d', strtotime($data['startingDate'])) ?>" class="btn btn-primary">&nbsp;<i class="fas fa-file-alt"></i>&nbsp;</a>
                                </div>
                                <input class="form-control" id="dateInput" type="date" value="<?php echo date('Y-m-d', strtotime($data['startingDate'])) ?>">
                                <div class="input-group-append">
                                    <a href="<?php echo URLROOT ?>/workouts/schedule/<?php echo date('Y-m-d', strtotime($data['startingDate'])) ?>" id="dateInputLink" class="btn btn-outline-secondary"><strong>Go</strong></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <br>
                    <div class="row">
                        <div class="offset-md-0 offset-xs-0"></div>
                        <div class="col-md-12 col-xs-12">
                            <div class="table-responsive table-wrap">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="table-dark titleFont">
                                            <!-- Populate table headers with dates -->
                                            <?php foreach ($weekArray as $date) : ?>
                                                <th>
                                                    <?php echo date('D', strtotime($date)) ?>
                                                    <br>
                                                    <?php echo date('m/d', strtotime($date)) ?>
                                                </th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <!-- Populate table with data for each day -->
                                            <?php foreach ($weekArray as $key => $date) : ?>
                                                <!-- Get data from DB call if there is any for that day -->
                                                <?php $index = array_search(date('Y-m-d', strtotime($date)), array_column($data['weekData'], 'workout_date')); ?>
                                                <?php if ($index != '') : ?>
                                                    <!-- MOD ID OF ZERO SIGNIFIES A REST DAY -->
                                                    <?php if ($data['weekData'][$index]->mod_id == 0) : ?>
                                                        <td class="calendar-table align-middle text-center" style="min-width:250px; <?php echo ($data['weekData'][$index]->status != 'open') ? 'background-color:#f0f0f0;' : '' ?>">
                                                            <div id="date<?php echo $key ?>" class="align-middle text-center position-relative" style="width:233px;">
                                                                <div class="text-center position-absolute top-50 start-50 translate-middle">
                                                                    <h3><i class="fas fa-mug-hot"></i></h3>
                                                                    <h6 class="titleFont text-nowrap"> Rest Day</h6>
                                                                </div>
                                                                <?php if ($data['weekData'][$index]->status == 'open') : ?>
                                                                    <div class="text-center position-absolute bottom-0 start-50 translate-middle-x">
                                                                        <button class="btn btn-warning text-nowrap scheduleBtn" id="<?php echo date('m-d-Y', strtotime($date)); ?>" data-mod-id="0" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fas fa-undo-alt"></i> <strong>Swap</strong></button>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                    <?php elseif ($data['weekData'][$index]->status == 'open') : ?>
                                                        <!-- FOR WORKOUTS THAT HAVE NOT BEEN STARTED YET -->
                                                        <?php require APPROOT . '/views/partials/openWorkoutDate.partial.php'; ?>

                                                    <?php else : ?>
                                                        <!-- FOR WORKOUTS THAT HAVE BEEN STARTED OR CLOSED -->
                                                        <?php require APPROOT . '/views/partials/closedWorkoutDate.partial.php'; ?>
                                                    <?php endif; ?>

                                                    <!-- Create Add button if nothing has been set for that day -->
                                                <?php else : ?>
                                                    <td class="calendar-table align-middle text-center" style="min-width:250px; <?php echo (date('Y-m-d', strtotime($date)) < date('Y-m-d', strtotime('now'))) ? 'background-color:#f0f0f0;' : '' ?>" >
                                                        <div id="date<?php echo $key ?>" class="d-table-cell align-middle text-center" style="width:233px;">
                                                            <?php if (date('Y-m-d', strtotime($date)) >= date('Y-m-d', strtotime('now'))) : ?>
                                                                <button class="btn btn-success text-nowrap scheduleBtn" id="<?php echo date('m-d-Y', strtotime($date)); ?>" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fas fa-plus"></i> <strong>Add</strong></button>
                                                            <?php else : ?>
                                                                <button class="btn btn-secondary text-nowrap" disabled><i class="fas fa-plus"></i><strong>Add</strong></button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title titleFont" id="staticBackdropLabel">Add Mod</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="d-flex justify-content-center mb-3">
                                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" name="modSelect" value="recent" id="recent" autocomplete="off" <?php echo (empty($modSelects['recent']) == true) ? 'disabled' : '' ?>>
                                                    <label class="btn btn-outline-secondary" for="recent">Recent Mods</label>

                                                    <input type="radio" class="btn-check" name="modSelect" value="user" id="user" autocomplete="off" <?php echo (empty($modSelects['user']) == true) ? 'disabled' : '' ?>>
                                                    <label class="btn btn-outline-secondary" for="user">My Mods</label>

                                                    <input type="radio" class="btn-check" name="modSelect" value="saved" id="saved" autocomplete="off" <?php echo (empty($modSelects['saved']) == true) ? 'disabled' : '' ?>>
                                                    <label class="btn btn-outline-secondary" for="saved">Liked Mods</label>

                                                    <input type="radio" class="btn-check" name="modSelect" value="all" id="all" autocomplete="off" <?php echo (empty($modSelects['all']) == true) ? 'disabled' : '' ?>>
                                                    <label class="btn btn-outline-secondary" for="all">All Mods</label>
                                                </div>
                                            </div>

                                            <select class="form-select" id="modSelect" aria-label="Workout mod select">
                                                <option selected disabled value="">Select a workout mod</option>
                                            </select>
                                            <div class="invalid-feedback" id="modSelectError">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" id="modalRestDayBtn"><i class="fas fa-mug-hot"></i> <strong>Rest Day</strong></button>
                                            <button type="button" class="btn btn-success" id="modalConfirmBtn"><i class="fas fa-check"></i> <strong>Confirm</strong></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // funtion to auto-check radios if any are disabled before it
    function autoCheckRadio() {
        if ($('#recent').is(':checked') == false && $('#recent').is(':enabled')) {
            $('#recent').prop("checked", true);
        } else if ($('#recent').is(':checked') == false && $('#user').is(':enabled')) {
            $('#user').prop("checked", true);
        } else if ($('#user').is(':checked') == false && $('#saved').is(':enabled')) {
            $('#saved').prop("checked", true);
        }
    };

    // function to check if a radio is checked and return the value
    function checkForCheckedRadio() {
        if ($('input:radio[name="modSelect"]').is(':checked')) {
            return $('input:radio[name="modSelect"]:checked').val();
        } else {
            return false;
        }
    };

    // function to swap out workouts in select based off radio checked
    function switchSelect(modSelect, weekday, modSelectName) {
        // empty mod select element
        $('#modSelect')
            .empty()
            .append('<option selected disabled value="">Select a workout mod</option>');
        // convert weekday from forward slashes to dashes
        weekdayDash = weekday.replace(/\//g, '-');
        // get current mod id if there is already one assigned to that day
        $currentModId = $('#' + weekdayDash + '').data('mod-id');
        // populate select with mod options
        if(modSelectName == 'saved'){
            modSelect.forEach(function(mod) {
                if(mod.created_by_id != <?php echo $_SESSION['user_id'] ?>){
                    if(mod.mod_id == $currentModId){
                        $('#modSelect').append(`<option value="${mod.mod_id}" disabled>${mod.mod_title} (Currently assigned)</option>`);
                    }else{
                        $('#modSelect').append(`<option value="${mod.mod_id}">${mod.mod_title}</option>`);
                    }
                }
            }); 
        }else{
            modSelect.forEach(function(mod) {
            if(mod.mod_id == $currentModId){
                $('#modSelect').append(`<option value="${mod.mod_id}" disabled>${mod.mod_title} (Currently assigned)</option>`);
            }else{
                $('#modSelect').append(`<option value="${mod.mod_id}">${mod.mod_title}</option>`);
            }
            }); 
        }

    };

    // function to disable/enable mod select based off if there are any records in array
    function checkSelect() {
        if ($('#modSelect > option').length == 1) {
            $('#modSelect').prop('disabled', true);
            $('#modalConfirmBtn').prop('disabled', true);
            $('#modSelect').attr('class', 'form-select is-invalid');
            $('#modSelectError').html('No Workouts - Create one <a href="<?php echo URLROOT; ?>/mods/add">here</a> or save some made by the community!');
        } else {
            $('#modSelect').prop('disabled', false);
            $('#modalConfirmBtn').prop('disabled', false);
            $('#modSelect').attr('class', 'form-select');
            $('#modSelectError').html('');
        }
    };

    // set up mod selects
    var arrayOfModObjects = [];
    <?php
    foreach ($modSelects as $key => $select) {
        echo 'arrayOfModObjects["' . $key . '"] = ';
        print_r(json_encode($select));
        echo ';';
    }
    ?>

    // ---DOCUMENT READY--- //
    $(document).ready(function() {

        // set TD content to same height
        var arrOfTds = [];
        for (var i = 0; i <= 6; i++) {
            arrOfTds.push($('#date' + i).height());
        }
        var heighestDiv = Math.max(...arrOfTds);
        for (var i = 0; i <= 6; i++) {
            $('#date' + i).height(500);
        };

        // auto-check radio btn if any are disabled before it
        autoCheckRadio();

        // when date input is changed . . .
        $(document.body).on('change', '#dateInput', function(){
            // get url from date link
            var url = $('#dateInputLink').attr('href');
            // get date in input
            var inputDate = $('#dateInput').val();
            // split the url
            urlSplit = url.split('/')
            // replace url parameter with new date
            urlSplit[urlSplit.length - 1] = formatDate(inputDate);
            // join array into new url
            url = urlSplit.join('/');
            $('#dateInputLink').attr('href', url);
        });

        // when a schedule button is clicked . . . 
        $(document.body).on('click', '.scheduleBtn', function() {
            // get mod id from data-mod-id
            var modId = $(this).data('mod-id');
            // disable rest day if already assigned
            if(modId == 0){
                $('#modalRestDayBtn').prop('disabled', true);
            }else{
                $('#modalRestDayBtn').prop('disabled', false);
            }

            // get date from id
            var dateId = $(this).attr('id');
            // replace dashes with slashes in date for compatibility
            dateId = dateId.replace(/-/g, '/');
            // make it a JS date
            var weekday = new Date(dateId);
            // get month name
            var options = {
                month: 'long'
            };
            var month = new Intl.DateTimeFormat('en-US', options).format(weekday);
            // add date to modal header
            $("#staticBackdropLabel").text(`Workout for ${month} ${weekday.getDate()}${nth(weekday.getDate())}`);
            // add date to confirmation button
            $('#modalConfirmBtn').data('weekday', dateId);

            // call function to get mods in select
            var radioCheck = checkForCheckedRadio();
            if (radioCheck !== false) {
                switchSelect(arrayOfModObjects[radioCheck], dateId, radioCheck);
            };

            // call function to disable select if no mods
            checkSelect();
        });

        // when a radio button is clicked . . .
        $('input:radio[name="modSelect"]').change(function() {
            // check which radio btn was clicked
            var radioCheck = checkForCheckedRadio();
            // get weekday from confirm btn
            var weekday = $('#modalConfirmBtn').data('weekday');
            // switch selects
            if (radioCheck !== false) {
                switchSelect(arrayOfModObjects[radioCheck], weekday, radioCheck);
            };
            // check if select has any options
            checkSelect();
        });

        // when the confirm button is clicked . . .
        $(document.body).on('click', '#modalConfirmBtn', function() {

            var weekday = $('#modalConfirmBtn').data('weekday');
            var modId = $('#modSelect').find(":selected").val();

            // validating date
            var timestamp = Date.parse(weekday);
            if(isNaN(timestamp)){
                $('#modSelect').attr('class', 'form-select is-invalid');
                $('#modSelectError').html('Invalid date');
                return;
            }

            // validating mod id
            if ($('#modSelect').find(":selected").val() == '') {
                $('#modSelect').attr('class', 'form-select is-invalid');
                $('#modSelectError').html('No Mod Selected');
                return;
            }

            $.post('<?php echo URLROOT; ?>/workouts/assignModAjax', {
                    weekday: weekday,
                    modId: modId
                },
                function(res, status) {
                    var data = JSON.parse(res);
                    location.reload();
                }
            );
        });

        // rest day button click
        $(document.body).on('click', '#modalRestDayBtn', function(){
            var weekday = $('#modalConfirmBtn').data('weekday');

            // validating date
            var timestamp = Date.parse(weekday);
            if(isNaN(timestamp)){
                $('#modSelect').attr('class', 'form-select is-invalid');
                $('#modSelectError').html('Invalid date');
                return;
            }

            $.post('<?php echo URLROOT; ?>/workouts/assignModAjax', {
                    weekday: weekday,
                    modId: 0
                },
                function(res, status) {
                    var data = JSON.parse(res);
                    location.reload();
                }
            );
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>