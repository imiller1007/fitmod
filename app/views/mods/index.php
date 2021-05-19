<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
$pageNum = $data['pageNum'];
$totalPages = $data['totalPages'];
$paginationLink = 'mods';
?>
<div class="container mt-5" id="card-section">
    <?php flash('add_mod_success'); ?>
    <div class="row">
        <div class="offset-lg-3 offset-xs-0 col-xs-12 col-sm-12 col-lg-6 text-center">
            <h2 style="font-family: 'Montserrat', sans-serif;">Newest Mods</h2>
            <hr>
        </div>
    </div>

    <?php require APPROOT . '/views/partials/pagination.partial.php'; ?>

    <div class="row">
        <div class="offset-md-2 offset-xs-0"></div>
        <div class="col-xs-12 col-sm-12">

            <?php foreach ($data['mods'] as $mod) : ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title titleFont mb-1"><?php echo $mod->mod_title; ?></h4>
                        <?php if (isLoggedIn()) : ?>
                            <small class="h6 titleFont">By <?php echo ($mod->created_by_id != $_SESSION['user_id']) ? $mod->userFirst . ' ' . strtoupper(substr($mod->userLast, 0, 1)) . '.' : 'Me' ?></small>
                        <?php else : ?>
                            <small class="h6 titleFont">By <?php echo $mod->userFirst . ' ' . strtoupper(substr($mod->userLast, 0, 1)) . '.' ?></small>
                        <?php endif; ?>
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
                        <div class="d-flex justify-content-center">
                            <!-- Check if logged in -->
                            <?php if (isLoggedIn()) : ?>
                                <!-- Check if mod was not made by user -->
                                <?php if ($mod->created_by_id != $_SESSION['user_id']) : ?>
                                    <!-- Check if mod has not been saved by user -->
                                    <?php if (!in_array($mod->mod_id, $data['savedMods'])) : ?>
                                        <button class="saveBtn btn btn-dark" id="saveMod<?php echo $mod->mod_id ?>">Save Mod</button>
                                    <?php else : ?>
                                        <button class="deleteBtn btn btn-dark" id="deleteMod<?php echo $mod->mod_id ?>">Saved <i class="fas fa-check"></i></button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php else : ?>
                                <a href="<?php echo URLROOT ?>/users/login" class="btn btn-dark">Save Mod</a>
                            <?php endif; ?>
                        </div>
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

<script>
    $(document.body).on('click', '.saveBtn' ,function(){

        modId = $(this).attr('id').replace('saveMod', '');

        $.post('<?php echo URLROOT; ?>/mods/saveModAjax', {
                modId: modId
            },
            function(res, status) {
                var data = JSON.parse(res);

                if (data.success) {
                    $('#saveMod' + modId).replaceWith('<button class="deleteBtn btn btn-dark" id="deleteMod' + modId + '">Saved <i class="fas fa-check"></i></button>');
                } else {
                    $('.modErrorMessage').slideUp();
                    $('#error' + modId).slideDown();
                    $('#error' + modId).text(data.message);
                    setTimeout(function() {
                        $('#error' + modId).slideUp();
                    }, 3000);
                }
                console.log(data.message);
            }
        );
    });

    $(document.body).on('click', '.deleteBtn' ,function(){

        modId = $(this).attr('id').replace('deleteMod', '');

        $.post('<?php echo URLROOT; ?>/mods/unsaveModAjax', {
                modId: modId
            },
            function(res, status) {
                var data = JSON.parse(res);

                if (data.success) {
                    $('#deleteMod' + modId).replaceWith('<button class="saveBtn btn btn-dark" id="saveMod' + modId + '">Save Mod</button>');
                } else {
                    $('.modErrorMessage').slideUp();
                    $('#error' + modId).slideDown();
                    $('#error' + modId).text(data.message);
                    setTimeout(function() {
                        $('#error' + modId).slideUp();
                    }, 3000);
                }
                console.log(data.message);
            }
        );
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>