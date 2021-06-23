<?php require APPROOT . '/views/inc/header.php'; ?>
<?php
$modUsed = $data['modUsed'];
$setInfo = $data['setInfo'];
?>
<div class="container mt-5">
    <div class="row">
        <div class="offset-lg-3 offset-xs-0 col-xs-12 col-sm-12 col-lg-6 text-center">
            <h2 class="titleFont">Workout for <?php echo date('m/d/Y') ?></h2>
            <hr>
        </div>
    </div>

    <?php for ($i = 1; $i <= EXERCISEMAX; $i++) : ?>
        <?php $exerId = 'exer' . $i . 'Id'; ?>
        <?php $exerName = 'exer' . $i . 'Name'; ?>
        <?php $exerNum = 'exer' . $i . '_num'; ?>
        <?php $exerTarget = 'exer' . $i . 'Target'; ?>
        <?php $exerType = 'exer' . $i . 'Type'; ?>

        <?php if ($modUsed->$exerId != null) : ?>

            <div class="row mb-3">
                <div class="col-sm-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h3 class="titleFont">
                                    <?php
                                    $setCount = 0;
                                    foreach ($setInfo as $set) {
                                        if ($set->exercise_id == $modUsed->$exerId) {
                                            $setCount++;
                                        }
                                    }
                                    if ($modUsed->$exerType != 'cardio') {
                                        switch ($modUsed->$exerNum - $setCount) {
                                            case 0:
                                                echo '<i class="fas fa-check-circle text-success"></i>';
                                                break;
                                            case $modUsed->$exerNum:
                                                echo '<i class="far fa-circle text-danger"></i>';
                                                break;
                                            default:
                                                echo '<i class="fas fa-adjust text-warning"></i>';
                                        }
                                    } elseif ($setCount > 0) {
                                        echo '<i class="fas fa-check-circle text-success"></i>';
                                    } else {
                                        echo '<i class="far fa-circle text-danger"></i>';
                                    }

                                    ?>
                                    &nbsp;
                                    <?php echo $modUsed->$exerName ?></h3>
                                <h3><a class="link-dark" href="https://www.google.com/search?q=<?php echo str_replace(' ', '+', $modUsed->$exerName); ?>+exercise" target="_blank" rel="noopener noreferrer"><i class="far fa-question-circle"></i></a></h3>
                            </div>
                            <hr class="mt-1">
                            <?php if ($modUsed->$exerType != 'cardio') : ?>
                                <?php if ($modUsed->$exerNum - $setCount != 0) : ?>
                                    <div class="row mx-auto mb-3">
                                        <button class="btn btn-outline-dark py-3 logBtn" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-exername="<?php echo $modUsed->$exerName ?>" data-exerid="<?php echo $modUsed->$exerId ?>" data-exertype="<?php echo $modUsed->$exerType ?>">
                                            <h4><i class="fas fa-pencil-alt"></i> <strong>Log Set</strong></h4>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php if ($setCount == 0) : ?>
                                    <div class="row mx-auto mb-3">
                                        <button class="btn btn-outline-dark py-3 logBtn" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-exername="<?php echo $modUsed->$exerName ?>" data-exerid="<?php echo $modUsed->$exerId ?>" data-exertype="<?php echo $modUsed->$exerType ?>">
                                            <h4><i class="fas fa-pencil-alt"></i> <strong>Log Time</strong></h4>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="table-responsive table-wrap">
                                <table class="table table-bordered">
                                    <?php switch ($modUsed->$exerType):
                                        case 'adjWt': ?>
                                            <?php $setCount = 0; ?>
                                            <tbody>
                                                <?php foreach ($setInfo as $set) : ?>
                                                    <?php if ($set->exercise_id == $modUsed->$exerId) : $setCount++; ?>
                                                        <tr>
                                                            <th class="table-dark titleFont text-nowrap" style="width: 33%;"><a href="#a" data-bs-toggle="modal" data-bs-target="#editModal" class="link-light editLink" style="text-decoration: none;" data-setid="<?php echo $set->set_id ?>" data-setnum="<?php echo $setCount ?>" data-setval="<?php echo $set->set_value ?>" data-weight="<?php echo $set->set_weight ?>" data-exername="<?php echo $modUsed->$exerName ?>" data-exertype="<?php echo $modUsed->$exerType ?>">
                                                                    <h4 class="my-2">Set <?php echo $setCount ?> <i class="fas fa-pencil-alt"></i></h4>
                                                                </a></th>
                                                            <td class="text-nowrap text-center" style="width: 33%;"><b><?php echo $set->set_weight ?></b> lb</td>
                                                            <td class="text-nowrap text-center" style="width: 33%;"><b><?php echo $set->set_value ?></b> reps</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <?php while ($setCount != $modUsed->$exerNum) : $setCount++ ?>
                                                    <tr>
                                                        <th class="table-dark titleFont text-nowrap" style="width: 33%;">
                                                            <h4 class="my-2">Set <?php echo $setCount ?></h4>
                                                        </th>
                                                        <td class="text-nowrap text-center" style="width: 33%;"></td>
                                                        <td class="text-nowrap text-center" style="width: 33%;"></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                            <?php break; ?>

                                        <?php
                                        case 'bodyWt': ?>
                                            <?php $setCount = 0; ?>
                                            <tbody>
                                                <?php foreach ($setInfo as $set) : ?>
                                                    <?php if ($set->exercise_id == $modUsed->$exerId) : $setCount++; ?>
                                                        <tr>
                                                            <th class="table-dark titleFont text-nowrap" style="width: 33%;"><a href="#a" data-bs-toggle="modal" data-bs-target="#editModal" class="link-light editLink" style="text-decoration: none;" data-setid="<?php echo $set->set_id ?>" data-setnum="<?php echo $setCount ?>" data-setval="<?php echo $set->set_value ?>" data-exername="<?php echo $modUsed->$exerName ?>" data-exertype="<?php echo $modUsed->$exerType ?>">
                                                                    <h4 class="my-2">Set <?php echo $setCount ?> <i class="fas fa-pencil-alt"></i></h4>
                                                                </a></th>
                                                            <td class="text-nowrap text-center"><b><?php echo $set->set_value ?></b> reps</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <?php while ($setCount != $modUsed->$exerNum) : $setCount++ ?>
                                                    <tr>
                                                        <th class="table-dark titleFont text-nowrap" style="width: 33%;">
                                                            <h4 class="my-2"> Set <?php echo $setCount ?></h4>
                                                        </th>
                                                        <td class="text-nowrap text-center"></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                            <?php break; ?>

                                        <?php
                                        case 'cardio': ?>
                                            <?php $setCount = 0; ?>
                                            <tbody>
                                                <?php foreach ($setInfo as $set) : ?>
                                                    <?php if ($set->exercise_id == $modUsed->$exerId) : $setCount++; ?>
                                                        <tr>
                                                            <th class="table-dark titleFont text-nowrap" style="width: 33%;"><a href="#a" data-bs-toggle="modal" data-bs-target="#editModal" class="link-light editLink" style="text-decoration: none;" data-setid="<?php echo $set->set_id ?>" data-setval="<?php echo $set->set_value ?>" data-exername="<?php echo $modUsed->$exerName ?>" data-exertype="<?php echo $modUsed->$exerType ?>">
                                                                    <h5 class="my-2">Duration <i class="fas fa-pencil-alt"></i></h5>
                                                                </a></th>
                                                            <td class="text-nowrap text-center"><b><?php echo $set->set_value ?> / <?php echo $modUsed->$exerNum ?></b> min</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <?php if ($setCount == 0) : ?>
                                                    <tr>
                                                        <th class="table-dark titleFont text-nowrap" style="width: 33%;">
                                                            <h5 class="my-2">Duration</h5>
                                                        </th>
                                                        <td class="text-nowrap text-center"><b>0 / <?php echo $modUsed->$exerNum ?></b> min</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <?php break; ?>

                                    <?php endswitch; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endfor; ?>

    <!-- NEW SET MODAL -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title titleFont" id="modalTitle">Log Rep</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3" id="setValueDiv">
                        <label for="set_value" class="form-label" id="setValueLabel">Reps</label>
                        <input type="number" class="form-control" id="set_value" name="set_value" required>
                        <span class="invalid-feedback" id="setValueErr"></span>
                    </div>
                    <div class="mb-3" id="setWeightDiv">
                        <label for="set_weight" class="form-label">Weight (lb)</label>
                        <input type="number" class="form-control" id="set_weight" name="set_weight">
                        <span class="invalid-feedback" id="setWeightErr"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" id="submitBtn"><i class="fas fa-pencil-alt"></i> <strong>Log Rep</strong></button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT SET MODAL -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title titleFont" id="editModalTitle">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3" id="setValueDiv">
                        <label for="set_value" class="form-label" id="editSetValueLabel">Reps</label>
                        <input type="number" class="form-control" id="edit_set_value" name="edit_set_value" required>
                        <span class="invalid-feedback" id="editSetValueErr"></span>
                    </div>
                    <div class="mb-3" id="editSetWeightDiv">
                        <label for="set_weight" class="form-label">Weight (lb)</label>
                        <input type="number" class="form-control" id="edit_set_weight" name="edit_set_weight">
                        <span class="invalid-feedback" id="editSetWeightErr"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" id="editSubmitBtn"><i class="fas fa-pencil-alt"></i> <strong>Confirm Edit</strong></button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        var workoutId = <?php echo $data['workoutId'] ?>;
        var currentExer = '';

        $('.logBtn').on('click', function() {
            var exerName = $(this).data('exername');
            var exerId = $(this).data('exerid');
            var exerType = $(this).data('exertype');
            $('#set_value').removeClass('is-invalid');
            $('#setValueErr').text('');
            $('#set_weight').removeClass('is-invalid');
            $('#setWeightErr').text('');
            $('#modalTitle').text(exerName);
            $('#submitBtn').data('exertype', exerType);
            $('#submitBtn').data('exerid', exerId);
            if (currentExer != exerName) {
                $('#set_value').val('');
                $('#set_weight').val('');
            }
            currentExer = exerName;
            if (exerType != 'adjWt') {
                $('#setWeightDiv').hide();
                if (exerType == 'cardio') {
                    $('#setValueLabel').text('Minutes');
                    $('#submitBtn').html('<button type="button" class="btn btn-dark" id="submitBtn"><i class="fas fa-pencil-alt"></i> <strong>Log Time</strong></button>');
                } else {
                    $('#setValueLabel').text('Reps');
                    $('#submitBtn').html('<button type="button" class="btn btn-dark" id="submitBtn"><i class="fas fa-pencil-alt"></i> <strong>Log Rep</strong></button>');
                }
            } else {
                $('#setValueLabel').text('Reps');
                $('#submitBtn').html('<button type="button" class="btn btn-dark" id="submitBtn"><i class="fas fa-pencil-alt"></i> <strong>Log Rep</strong></button>');
                $('#setWeightDiv').show();
            }
        });

        $('#submitBtn').on('click', function() {
            $('#set_value').removeClass('is-invalid');
            $('#setValueErr').text('');
            $('#set_weight').removeClass('is-invalid');
            $('#setWeightErr').text('');
            var valid = true;
            var exerId = $(this).data('exerid');
            var exerType = $(this).data('exertype');
            var setValue = $('#set_value').val();
            var setWeight = $('#set_weight').val();

            if (setValue == '') {
                valid = false;
                $('#set_value').addClass('is-invalid');
                $('#setValueErr').text('Must include value for exercise');
            } else {
                if (!isNaN(setValue) == false) {
                    valid = false;
                    $('#set_value').addClass('is-invalid');
                    $('#setValueErr').text('Value must be a number');
                }
            }

            if (exerType == 'adjWt' && setWeight == '') {
                valid = false;
                $('#set_weight').addClass('is-invalid');
                $('#setWeightErr').text('Must include amount of weight used');
            } else {
                if (!isNaN(setWeight) == false) {
                    valid = false;
                    $('#set_weight').addClass('is-invalid');
                    $('#setWeightErr').text('Value must be a number');
                }
            }

            if (valid) {
                $.post('<?php echo URLROOT; ?>/workouts/logExerciseAjax', {
                        workout_id: workoutId,
                        exercise_value: setValue,
                        exercise_weight: setWeight,
                        exercise_id: exerId,
                        exerType: exerType
                    },
                    function(res, status) {
                        var data = JSON.parse(res);
                        if (status != 'success') {
                            console.log(data.message);
                        } else {
                            document.location.reload(true);
                        }

                    }
                );
            }
        });

        $('.editLink').on('click', function() {
            var setId = $(this).data('setid');
            var exerName = $(this).data('exername');
            var exerType = $(this).data('exertype');
            var setVal = $(this).data('setval');
            var setWeight = $(this).data('weight');

            $('#edit_set_value').val(setVal);
            $('#edit_set_weight').val(setWeight);
            $('#edit_set_value').removeClass('is-invalid');
            $('#editSetValueErr').text('');
            $('#edit_set_weight').removeClass('is-invalid');
            $('#editSetWeightErr').text('');

            if (exerType != 'cardio') {
                var setNum = $(this).data('setnum');
                $('#editModalTitle').text('Edit Set ' + setNum + ' for ' + exerName);
            } else {
                $('#editModalTitle').text('Edit Time for ' + exerName);
            }

            if (exerType != 'adjWt') {
                $('#editSetWeightDiv').hide();
                if (exerType == 'cardio') {
                    $('#editSetValueLabel').text('Minutes');
                    $('#editSubmitBtn').html('<button type="button" class="btn btn-dark" data-setid="" id="editSubmitBtn"><i class="fas fa-pencil-alt"></i> <strong>Edit Time</strong></button>');
                } else {
                    $('#editSetValueLabel').text('Reps');
                    $('#editSubmitBtn').html('<button type="button" class="btn btn-dark" data-setid="" id="editSubmitBtn"><i class="fas fa-pencil-alt"></i> <strong>Edit Rep</strong></button>');
                }
            } else {
                $('#editSetWeightDiv').show();
                $('#editSetValueLabel').text('Reps');
                $('#editSubmitBtn').html('<button type="button" class="btn btn-dark" data-setid="" id="editSubmitBtn"><i class="fas fa-pencil-alt"></i> <strong>Edit Rep</strong></button>');
            }

            $('#editSubmitBtn').data('setid', setId);
            $('#editSubmitBtn').data('exertype', exerType);
        });

        $('#editSubmitBtn').on('click', function(){
            $('#edit_set_value').removeClass('is-invalid');
            $('#editSetValueErr').text('');
            $('#edit_set_weight').removeClass('is-invalid');
            $('#editSetWeightErr').text('');
            var valid = true;
            var setValue = $('#edit_set_value').val();
            var setWeight = $('#edit_set_weight').val();
            var setId = $('#editSubmitBtn').data('setid');
            var exerType = $('#editSubmitBtn').data('exertype');

            if (setValue == '') {
                valid = false;
                $('#edit_set_value').addClass('is-invalid');
                $('#editSetValueErr').text('Must include value for exercise');
            } else {
                if (!isNaN(setValue) == false) {
                    valid = false;
                    $('#edit_set_value').addClass('is-invalid');
                    $('#editSetValueErr').text('Value must be a number');
                }
            }

            if (exerType == 'adjWt' && setWeight == '') {
                valid = false;
                $('#edit_set_weight').addClass('is-invalid');
                $('#editSetWeightErr').text('Must include amount of weight used');
            } else {
                if (!isNaN(setWeight) == false) {
                    valid = false;
                    $('#edit_set_weight').addClass('is-invalid');
                    $('#editSetWeightErr').text('Value must be a number');
                }
            }
            
            if (valid) {
                $.post('<?php echo URLROOT; ?>/workouts/editExerciseAjax', {
                        set_id: setId,
                        set_value: setValue,
                        set_weight: setWeight,
                        exerType: exerType
                    },
                    function(res, status) {
                        var data = JSON.parse(res);
                        if (status != 'success') {
                            console.log(data.message);
                        } else {
                            document.location.reload(true);
                        }

                    }
                );
            }

        });

    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>