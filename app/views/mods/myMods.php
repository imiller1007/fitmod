<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
$pageNum = $data['pageNum'];
$totalPages = $data['totalPages'];
$paginationLink = 'mods/mymods';
?>
<div class="container mt-5" id="card-section">
    <?php flash('add_mod_success'); ?>
    <div class="row">
        <div class="offset-lg-3 offset-xs-0 col-xs-12 col-sm-12 col-lg-6 text-center">
            <h2 class="titleFont">My Mods</h2>
            <hr>
        </div>
    </div>

    <?php require APPROOT . '/views/partials/pagination.partial.php'; ?>

    <div class="row">
        <div class="offset-md-2 offset-xs-0"></div>
        <div class="col-xs-12 col-sm-12">

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo URLROOT ?>/mods/add" class="link-dark hoverLink"><h4 class="card-title titleFont mb-0">+ Add New Mod</h4></a>
                    </div>
                </div>
            </div>
            <br>

            <?php foreach ($data['mods'] as $mod) : ?>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title titleFont"><?php echo $mod->mod_title; ?></h4>
                        </div>
                        <p class="card-text mb-1"><span class="titleFont">Description: </span><?php echo $mod->mod_desc; ?></p>
                        <table class="table">
                            <thead>
                                <tr class="table-dark">
                                    <th scope="col" style="width: 25%;">Exercise</th>
                                    <th scope="col" style="width: 25%;">Sets / Duration</th>
                                    <th scope="col" style="width: 25%;">Target</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 1; $i <= EXERCISEMAX; $i++) : ?>
                                    <?php $exerName = 'exer' . $i . 'Name'; ?>
                                    <?php $exerNum = 'exer' . $i . '_num'; ?>
                                    <?php $exerTarget = 'exer' . $i . 'Target'; ?>
                                    <?php $exerType = 'exer' . $i . 'Type'; ?>
                                    <?php if ($mod->$exerName != null) : ?>
                                        <tr>
                                            <th scope="row"><a class="link-dark" href="https://www.google.com/search?q=<?php echo str_replace(' ', '+', $mod->$exerName); ?>+exercise" target="_blank" rel="noopener noreferrer"><i class="far fa-question-circle"></i></a> <?php echo $mod->$exerName; ?></th>
                                            <td><?php
                                                if ($mod->$exerType != 'cardio') {
                                                    echo $mod->$exerNum . ' Sets';
                                                } else {
                                                    echo $mod->$exerNum . ' Minutes';
                                                }
                                                ?></td>
                                            <td><?php echo $mod->$exerTarget; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                        <div class="row mt-2">
                            <span class="text-danger text-center mx-auto modErrorMessage" id="error<?php echo $mod->mod_id ?>"></span>
                        </div>
                    </div>
                </div>
                <br>
            <?php endforeach; ?>

            <div class="mb-5">
                <?php require APPROOT . '/views/partials/pagination.partial.php'; ?>
            </div>

        </div>
    </div>

</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>