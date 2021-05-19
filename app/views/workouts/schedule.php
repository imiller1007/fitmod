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

?>
<div class="row mt-5">
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
                        <input class="form-control" type="date" value="<?php echo date('Y-m-d', strtotime($data['startingDate'])) ?>">
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
                                        <?php foreach ($weekArray as $key=>$date) : ?>
                                            <!-- Get data from DB call if there is any for that day -->
                                            <?php $index = array_search(date('Y-m-d', strtotime($date)), array_column($data['weekData'], 'workout_date')); ?>
                                            <?php if ($index != '') : ?>
                                                <!-- MOD ID OF ZERO SIGNIFIES A REST DAY -->
                                                <?php if ($data['weekData'][$index]->mod_id == 0) : ?>
                                                    <td class="calendar-table align-middle text-center" style="min-width:250px;">
                                                        <div id="date<?php echo $key ?>" class="align-middle text-center position-relative" style="width:233px;">
                                                            <div class="text-center position-absolute top-50 start-50 translate-middle">
                                                                <h3><i class="fas fa-mug-hot"></i></h3>
                                                                <h6 class="titleFont text-nowrap"> Rest Day</h6> 
                                                            </div>
                                                            <?php if ($data['weekData'][$index]->status == 'open') : ?>
                                                                <div class="text-center position-absolute bottom-0 start-50 translate-middle-x">
                                                                    <button class="btn btn-warning text-nowrap scheduleBtn" id="<?php echo date('m-d-Y', strtotime($date)); ?>" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fas fa-undo-alt"></i> <strong>Swap</strong></button>
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
                                                <td class="calendar-table align-middle text-center" style="min-width:250px;">
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
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> <strong>Cancel</strong></button>
                                        <button type="button" class="btn btn-success"><i class="fas fa-check"></i> <strong>Confirm</strong></button>
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
    var arr = [];
    for(var i = 0; i <= 6; i++){
        arr.push($('#date' + i).height());
    }
    var heighestDiv = Math.max(...arr);

    for(var i = 0; i <= 6; i++){
        $('#date' + i).height(heighestDiv);
    }

    $(document.body).on('click', '.scheduleBtn' ,function(){
        var dateId = $(this).attr('id');
        date = new Date(dateId);
        var options = { month: 'long'};
        var month = new Intl.DateTimeFormat('en-US', options).format(date);
        $("#staticBackdropLabel").text(`Workout for ${month} ${date.getDate()}${nth(date.getDate())}`);
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>