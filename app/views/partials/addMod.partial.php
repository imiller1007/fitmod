<div id="exer<?= $i ?>" class="exergroup">
    <?php echo ($i > 1 ? '<hr>' : ''); ?>
    <h4 id="exerTitle<?= $i ?>" class="card-title titleFont">Exercise <?php echo $i ?></h4>
    <div class="mb-3">
        <label class="form-label" for="exerciseName<?= $i ?>">Exercise Name <small class="nameStatus<?php echo $i ?>"></small></label>
        <input type="text" list="exerciseOptions" maxlength="80" class="form-control <?php echo (!empty($errors['exerNameErr' . $i])) ? 'is-invalid' : ''; ?>" name="exerciseName<?= $i ?>" id="exerciseName<?= $i ?>" value="<?php echo $inputData['exerciseName' . $i] ?>" oninput="checkIfExerciseExists(<?= $i ?>)">
        <datalist id="exerciseOptions">
            <?php foreach ($data['exercises'] as $exercise) : ?>
                <option value="<?php echo $exercise->exercise_name ?>">
            <?php endforeach; ?>
        </datalist>
        <span class="invalid-feedback"><?php echo $errors['exerNameErr' . $i] ?></span>
    </div>
    <div id="newExerciseInfo<?= $i ?>">
        <div class="mb-3">
            <label class="form-label" for="exerciseType<?= $i ?>">Exercise Type</label>
            <select name="exerciseType<?= $i ?>" id="exerciseType<?= $i ?>" class="form-select <?php echo (!empty($errors['exerTypeErr' . $i])) ? 'is-invalid' : ''; ?>" onchange="numLabelChange(<?= $i ?>)" value="cardio">
                <option value="adjWt" <?php echo selectCheck($inputData['exerciseType' . $i], 'adjWt') ?>>Adjusted Weight Exercise</option>
                <option value="bodyWt" <?php echo selectCheck($inputData['exerciseType' . $i], 'bodyWt') ?>>Bodyweight Exercise</option>
                <option value="cardio" <?php echo selectCheck($inputData['exerciseType' . $i], 'cardio') ?>>Cardio Exercise</option>
            </select>
            <span class="invalid-feedback"><?php echo $errors['exerTypeErr' . $i] ?></span>
        </div>
        <div class="mb-3">
            <label class="form-label" for="exerciseTarget<?= $i ?>">Exercise Target</label>
            <select name="exerciseTarget<?= $i ?>" id="exerciseTarget<?= $i ?>" class="form-select <?php echo (!empty($errors['exerTargetErr' . $i])) ? 'is-invalid' : ''; ?>">
                <option value="Abs" <?php echo selectCheck($inputData['exerciseTarget' . $i], 'Abs') ?>>Abs</option>
                <option value="Arms" <?php echo selectCheck($inputData['exerciseTarget' . $i], 'Arms') ?>>Arms</option>
                <option value="Back" <?php echo selectCheck($inputData['exerciseTarget' . $i], 'Back') ?>>Back</option>
                <option value="Calves" <?php echo selectCheck($inputData['exerciseTarget' . $i], 'Calves') ?>>Calves</option>
                <option value="Chest" <?php echo selectCheck($inputData['exerciseTarget' . $i], 'Chest') ?>>Chest</option>
                <option value="Legs" <?php echo selectCheck($inputData['exerciseTarget' . $i], 'Legs') ?>>Legs</option>
                <option value="Shoulders" <?php echo selectCheck($inputData['exerciseTarget' . $i], 'Shoulders') ?>>Shoulders</option>
            </select>
            <span class="invalid-feedback"><?php echo $errors['exerTargetErr' . $i] ?></span>
        </div>
    </div>
    <div class="mb-3">
        <label for="exerciseNum<?= $i ?>" id="exerciseNumLabel<?= $i ?>">Sets (Max: 10)</label>
        <input type="number" class="form-control <?php echo (!empty($errors['exerNumErr' . $i])) ? 'is-invalid' : ''; ?>" max="10" name="exerciseNum<?= $i ?>" id="exerciseNum<?= $i ?>" value="<?php echo $inputData['exerciseNum' . $i] ?>">
        <span class="invalid-feedback"><?php echo $errors['exerNumErr' . $i] ?></span>
    </div>
</div>