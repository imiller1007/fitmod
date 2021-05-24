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
<div class="container mt-5">
    <?php flash('assign_mod_success'); ?>
    <div class="row">
        <div class="offset-lg-3 offset-xs-0 col-xs-12 col-sm-12 col-lg-6 text-center">
            <h2 class="titleFont">Results</h2>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2 text-center">
                            <a class="link-dark" href="<?php echo URLROOT ?>/workouts/results/<?php echo date('Y-m-d', strtotime('-7 days', strtotime($data['startingDate']))) ?>">
                                <h1 id="numSelectCaret"><i class="fas fa-caret-left"></i></h1>
                            </a>
                        </div>
                        <div class="col-8 text-center">
                            <h2 id="exerciseNumDisplay" class="titleFont">Week of <?php echo date('F jS Y', strtotime($data['startingDate'])) ?></h2>
                        </div>
                        <div class="col-2 text-center">
                            <a class="link-dark" href="<?php echo URLROOT ?>/workouts/results/<?php echo date('Y-m-d', strtotime('+7 days', strtotime($data['startingDate']))) ?>">
                                <h1 id="numSelectCaret"><i class="fas fa-caret-right"></i></h1>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-lg-4 col-lg-4 offset-md-0 col-md-12 text-center">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <a href="<?php echo URLROOT ?>/workouts/schedule/<?php echo date('Y-m-d', strtotime($data['startingDate'])) ?>" class="btn btn-success">&nbsp;<i class="fas fa-pencil-alt"></i>&nbsp;</a>
                                </div>
                                <input class="form-control" id="dateInput" type="date" value="<?php echo date('Y-m-d', strtotime($data['startingDate'])) ?>">
                                <div class="input-group-append">
                                    <a href="<?php echo URLROOT ?>/workouts/results/<?php echo date('Y-m-d', strtotime($data['startingDate'])) ?>" id="dateInputLink" class="btn btn-outline-secondary">Go</a>
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
                                                        <td class="calendar-table" style="min-width:250px;">
                                                            <div id="date<?php echo $key ?>" class="align-middle text-center" style="width:233px;">
                                                                <div class="titleFont text-center">
                                                                    <strong>Rest Day</strong>
                                                                    <hr class="my-1">
                                                                </div>
                                                                <div class="text-center pt-1">
                                                                    <h4><i class="fas fa-mug-hot"></i></h4> 
                                                                </div>
                                                            </div>
                                                        </td>
                                                    <?php elseif ($data['weekData'][$index]->status != 'open') : ?>
                                                        <!-- FOR WORKOUTS THAT HAVE NOT BEEN STARTED YET -->
                                                        <?php require APPROOT . '/views/partials/workoutResult.partial.php'; ?>
                                                    <?php else : ?>
                                                        <?php require APPROOT . '/views/partials/workoutResult.partial.php'; ?>
                                                    <?php endif; ?>
                                                    <!-- Create Add button if nothing has been set for that day -->
                                                <?php else : ?>
                                                    <td class="calendar-table align-middle text-center" style="min-width:250px; background-color:#f0f0f0;">
                                                        <div id="date<?php echo $key ?>" class="" style="width:233px;"></div>
                                                    </td>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    // ---DOCUMENT READY--- //
    $(document).ready(function() {

        // set TD content to same height
        var arrOfTds = [];
        for (var i = 0; i <= 6; i++) {
            arrOfTds.push($('#date' + i).height());
        }
        var heighestDiv = Math.max(...arrOfTds);
        for (var i = 0; i <= 6; i++) {
            $('#date' + i).height(heighestDiv);
        };

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
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>