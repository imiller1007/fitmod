<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container mt-5" id="card-section">
    <div class="row">
        <div class="offset-lg-3 offset-xs-0 col-xs-12 col-sm-12 col-lg-6 text-center">
            <h2 class="titleFont">Exercises</h2>
            <hr>
        </div>
    </div>
    <?php $paginationLink = '/exercises/'; ?>
    <?php require APPROOT . '/views/partials/pagination.partial.php'; ?>

    <div class="row">
        <div class="offset-md-2 offset-xs-0"></div>
        <div class="col-xs-12 col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Exercise Name</th>
                            <th>Body Group</th>
                            <th>Exercise Type</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['exercises'] as $exercise) : ?>
                            <tr>
                                <td><?php echo $exercise->exercise_id ?></td>
                                <td><a class="link-dark" href="https://www.google.com/search?q=<?php echo str_replace(' ', '+', $exercise->exercise_name); ?>+exercise" target="_blank" rel="noopener noreferrer"><i class="far fa-question-circle"></i></a> <?php echo $exercise->exercise_name ?></td>
                                <td><?php echo $exercise->body_group ?></td>
                                <td><?php echo $exercise->exercise_type ?></td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-lg editBtn" data-bs-toggle="modal" data-exerid="<?php echo $exercise->exercise_id ?>" data-name="<?php echo $exercise->exercise_name ?>" data-bodygroup="<?php echo $exercise->body_group ?>" data-exertype="<?php echo $exercise->exercise_type ?>" data-bs-target="#editBackdrop"><i class="fas fa-edit"></i></button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-danger btn-lg deleteBtn" data-exerid="<?php echo $exercise->exercise_id ?>" data-bs-toggle="modal" data-bs-target="#deleteBackdrop"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mb-5">
                <?php require APPROOT . '/views/partials/pagination.partial.php'; ?>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title titleFont" id="editTitle">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="exerName">
                        <span class="invalid-feedback" id="exerNameErr"></span>
                    </div>
                    <select class="form-select mb-3" name="target" id="exerTarget" aria-label="Default select example">
                        <option disabled>Target</option>
                        <option value="Abs">Abs</option>
                        <option value="Arms">Arms</option>
                        <option value="Back">Back</option>
                        <option value="Calves">Calves</option>
                        <option value="Chest">Chest</option>
                        <option value="Legs">Legs</option>
                        <option value="Shoulders">Shoulders</option>
                    </select>
                    <span class="invalid-feedback" id="targetErr"></span>
                    <select class="form-select mb-3" name="type" id="exerType" aria-label="Default select example">
                        <option disabled>Type of Exercise</option>
                        <option value="adjWt">Adjusted Weight</option>
                        <option value="bodyWt">Body Weight</option>
                        <option value="cardio">Cardio</option>
                    </select>
                    <span class="invalid-feedback" id="typeErr"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><strong><i class="fas fa-times"></i> Cancel</strong></button>
                    <button type="button" class="btn btn-primary" id="editSubmit" data-exerid=""><i class="fas fa-check"></i> <strong>Submit</strong></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title titleFont" id="deleteTitle">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure want to delete?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><strong><i class="fas fa-times"></i> No</strong></button>
                    <button type="button" class="btn btn-danger" id="deleteSubmit" data-exerid=""><i class="fas fa-trash-alt"></i> <strong>Yes, Delete</strong></button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    $(document.body).on('click', '.editBtn', function() {
        $('#exerName').removeClass('is-invalid');
        $('#exerNameErr').text('');
        $('#exerTarget').removeClass('is-invalid');
        $('#targetErr').text('');
        $('#exerType').removeClass('is-invalid');
        $('#typeErr').text('');

        var exerId = $(this).data('exerid');
        var modName = $(this).data('name');
        var bodyGroup = $(this).data('bodygroup');
        var exerType = $(this).data('exertype');

        $('#editTitle').text('Edit Exercise #' + exerId);
        $('#exerName').val(modName);
        $('[name=target]').val(bodyGroup);
        $('[name=type]').val(exerType);
        $('#editSubmit').data('exerid', exerId);
    });

    $(document.body).on('click', '#editSubmit', function() {
        $('#exerName').removeClass('is-invalid');
        $('#exerNameErr').text('');
        $('#exerTarget').removeClass('is-invalid');
        $('#targetErr').text('');
        $('#exerType').removeClass('is-invalid');
        $('#typeErr').text('');

        var valid = true;
        var exerId = $(this).data('exerid');
        var exerName = $('#exerName').val();
        var exerTarget = $('#exerTarget').val();
        var exerType = $('#exerType').val();

        if (exerName == '') {
            valid = false;
            $('#exerName').addClass('is-invalid');
            $('#exerNameErr').text('Must include name of exercise');
        }

        if (valid) {
            $.post('<?php echo URLROOT; ?>/exercises/editAjax', {
                    exercise_id: exerId,
                    exercise_name: exerName,
                    exercise_target: exerTarget,
                    exercise_type: exerType
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

    $(document.body).on('click', '.deleteBtn', function() {
        var exerId = $(this).data('exerid');
        $('#deleteTitle').text('Confirm Deletion #' + exerId);
        $('#deleteSubmit').data('exerid', exerId);
    });

    $(document.body).on('click', '#deleteSubmit', function() {
        var exerId = $(this).data('exerid');
        
        $.post('<?php echo URLROOT; ?>/exercises/deleteAjax', {
                exercise_id: exerId
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
    });

</script>


<?php require APPROOT . '/views/inc/footer.php'; ?>