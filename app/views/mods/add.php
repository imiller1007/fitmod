<?php require APPROOT . '/views/inc/header.php'; ?>
<?php
$inputData = $data['inputData'];
$errors = $data['errors'];

function selectCheck($selectName, $selectValue)
{
    if ($selectName == $selectValue) {
        return 'selected';
    }
}

?>

<div class="row mt-5">
    <div class="offset-xs-0 offset-md-3 col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <form action="<?php echo URLROOT; ?>/mods/add" id="modForm" autocomplete="off" method="POST">
            <div class="card">
                <div class="card-body">
                    <!-- <h3 class="card-title titleFont text-center">New Workout Mod</h3> -->
                    <input class="form-control form-control-lg titleFont text-center  <?php echo (!empty($errors['titleErr'])) ? 'is-invalid' : ''; ?>" maxlength="80" type="text" name="title" placeholder="New Workout Mod Title" value="<?php echo $inputData['title'] ?>" aria-label=".form-control-lg" required>
                    <span class="invalid-feedback"><?php echo $errors['titleErr'] ?></span>
                    <br>
                    <div>
                        <h5 class="card-title titleFont text-center">Number of Workouts</h5>
                        <input name="numOfExer" type="hidden" class="form-control" id="numOfExer" value="<?php echo $data['numOfExer'] ?>">
                        <div class="row">
                            <div class="col-4 text-center">
                                <h1 id="numSelectCaret" onclick="updateForm('decrement')"><i class="fas fa-caret-left"></i></h1>
                            </div>
                            <div class="col-4 text-center">
                                <h1 id="numOfExerDisplay" class="titleFont">0</h1>
                            </div>
                            <div class="col-4 text-center">
                                <h1 id="numSelectCaret" onclick="updateForm('increment')"><i class="fas fa-caret-right"></i></h1>
                            </div>
                        </div>
                        <div class="row">
                            <span id="numOfExerErr" class="text-center text-danger"><?php echo $errors['numOfExerErr'] ?></span>
                        </div>
                        <br>
                        <div class="mb-5">
                            <textarea name="desc" id="desc" class="form-control titleFont <?php echo (!empty($errors['descErr'])) ? 'is-invalid' : ''; ?>" maxlength="200" cols="80" rows="5" placeholder="Description . . . " onkeyup="updateDescCounter()" onchange="updateDescCounter()" required><?php echo $inputData['desc'] ?></textarea>
                            <span id="descCounter" class="pull-right"><small><span id="descCounterNum">0</span>/200</small></span>
                            <span class="invalid-feedback"><?php echo $errors['descErr'] ?></span>
                        </div>
                        <div id="initBtnDiv" class="row mx-auto">
                            <button type="button" onclick="initializeForm()" id="initBtn" class="btn btn-dark mx-auto">Get Started</button>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            <div class="card mb-3" id="workoutCard">
                <div class="card-body">

                    <?php

                    // Loop through form section 
                    for ($i = 1; $i <= EXERCISEMAX; $i++) {
                        require APPROOT . '/views/partials/addMod.partial.php';
                    }

                    ?>

                    <div class="mb-1 text-center">
                        <input class="btn btn-dark" type="submit" value="Save Workout Mod">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // initial vars
    var workoutCard = $("#workoutCard");
    var formInitialized = false;
    exerciseArr = [];
    <?php foreach ($data['exercises'] as $exercise) : ?>
        exerciseArr.push(<?php echo json_encode($exercise); ?>);
    <?php endforeach; ?>

    // hide all exercise cards
    function hideAll() {
        for (var i = 1; i <= <?php echo EXERCISEMAX ?>; i++) {
            $("#exer" + i).hide();
            requireFields(i, false);
        }
    }

    // toggle exercise cards required/not required
    function requireFields(inputNum, bool) {
        $("input[name='exerciseName" + inputNum + "']").prop('required', bool);
        $("input[name='exerciseNum" + inputNum + "']").prop('required', bool);
    }

    // show exercise cards based off # of exercises
    function showExerGroups(numOfExercises) {
        for (var i = 1; i <= numOfExercises; i++) {
            $("#exer" + i).show();
            requireFields(i, true);
        }
    }

    // triggers after # of exercises is established
    function initializeForm() {
        if (formInitialized == false) {
            var numOfExercises = $("#numOfExer").val();
            $("#initBtnDiv").slideUp(500);
            showExerGroups(numOfExercises);
            workoutCard.slideDown(500);
            formInitialized = true;
        }
    }

    // adds/removes exercise cards as # of exercises is changed
    function updateForm(operator) {
        var numOfExercises = $("#numOfExer").val();
        if (operator == "decrement") {
            var inputToDisable = numOfExercises;
            if (numOfExercises > 1) {
                numOfExercises--;
                $("#numOfExer").val(numOfExercises);
                $("#numOfExerDisplay").text(numOfExercises);
                if (formInitialized == true) {
                    $("#exer" + inputToDisable).slideUp(500);
                    requireFields(inputToDisable, false);
                }
            }
        } else {
            if (numOfExercises < <?php echo EXERCISEMAX ?>) {
                numOfExercises++;
                $("#numOfExer").val(numOfExercises);
                $("#numOfExerDisplay").text(numOfExercises);
                if (formInitialized == true) {
                    $("#exer" + numOfExercises).slideDown(500);
                    requireFields(numOfExercises, true);
                }
            }
        }
    }

    // changes whether input field should reflect "# of sets" or "duration" based on exercise
    function numLabelChange(exerciseNum) {
        var exerType = $("#exerciseType" + exerciseNum).val();
        if (exerType == "cardio") {
            $("#exerciseNumLabel" + exerciseNum).text("Duration (in minutes)");
            $("input[name='exerciseNum" + exerciseNum + "']").prop('max', false);
        } else {
            $("#exerciseNumLabel" + exerciseNum).text("Sets (Max: 10)");
            $("input[name='exerciseNum" + exerciseNum + "']").prop('max', 10);
        }
    }

    // checks all exercises of their appropriate Num Label (for POST-backs)
    function fullnumLabelChange() {
        for (var i = 1; i <= <?php echo EXERCISEMAX ?>; i++) {
            numLabelChange(i);
        }
    }

    // updates the number of characters currently in the "description" text area
    function updateDescCounter() {
        var descNum = $("#desc").val().length;
        $("#descCounterNum").text(descNum);
        if (descNum >= 200) {
            $("#descCounter").prop("class", "pull-right text-danger");
        } else {
            $("#descCounter").prop("class", "pull-right");
        }
    }

    // function to hide/show the "new exercise info" section if exercise already exists in DB or not
    function showHideNewExercise(found, exerciseNum, exerType) {
        if (found == true) {
            $("#newExerciseInfo" + exerciseNum).hide();
            $('.nameStatus' + exerciseNum).html('<b class="text-primary">(Existing Exercise)</b>');
        } else {
            $("#newExerciseInfo" + exerciseNum).show();
            $('.nameStatus' + exerciseNum).html('');
        }

        if (exerType == "cardio") {
            $("#exerciseNumLabel" + exerciseNum).text("Duration (in minutes)");
            $("input[name='exerciseNum" + exerciseNum + "']").prop('max', false);
            $("#exerciseType" + exerciseNum + " option:contains('Cardio Exercise')").prop('selected', true)
        } else if(exerType != '') {
            $("#exerciseNumLabel" + exerciseNum).text("Sets (Max: 10)");
            $("input[name='exerciseNum" + exerciseNum + "']").prop('max', 10);
        } else{
            numLabelChange(exerciseNum);
        }
    }

    // Check if exercise name in input exists in DB or not
    function checkIfExerciseExists(exerciseNum) {
        var inputVal = $("#exerciseName" + exerciseNum).val();
        var found = false;
        var exerType = '';

        for (var i = 0; i < exerciseArr.length; i++) {
            if (exerciseArr[i].exercise_name.toUpperCase().trim() === inputVal.toUpperCase().trim()) {
                found = true;
                exerType = exerciseArr[i].exercise_type;
            }
        }

        showHideNewExercise(found, exerciseNum, exerType);
    }

    // function to check all exercise names on POST-back
    function fullExerciseExistCheck() {
        for (var i = 1; i <= <?php echo EXERCISEMAX ?>; i++) {
            checkIfExerciseExists(i);
        }
    }

    // document.ready
    $(document).ready(function() {
        workoutCard.hide();
        var numOfExercises = $("#numOfExer").val();
        hideAll();
        fullnumLabelChange();
        fullExerciseExistCheck();
        if (numOfExercises !== "" && numOfExercises > 0) {
            formInitialized = true;
            $("#numOfExerDisplay").text(numOfExercises);
            $("#initBtnDiv").slideUp(500);
            showExerGroups(numOfExercises);
            updateDescCounter();
            workoutCard.slideDown(500);
        } else {
            $("#numOfExer").val(1);
            $("#numOfExerDisplay").text(1);
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>