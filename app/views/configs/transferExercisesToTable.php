<?php require APPROOT . '/views/inc/header.php'; ?>

<form action="<?php echo URLROOT; ?>/configs/transferExercisesToTable" method="post">

    <div class="row mx-auto mt-5">
        <?php flash('transfer_success'); ?>
        <?php if($data['transferred'] == false) : ?>
        <input type="submit" class="btn btn-primary text-center" value="Transfer Data">
        <?php else: ?>
        <h5 class="text-center">Data has already been transferred.</h5>
        <?php endif; ?>
    </div>


</form>

<?php require APPROOT . '/views/inc/footer.php'; ?>