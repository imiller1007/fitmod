<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
$pageNum = $data['pageNum'];
$totalPages = $data['totalPages'];
$paginationLink = 'mods';
?>
<div class="container mt-5" id="card-section">
    <div class="row">
        <div class="offset-lg-3 offset-xs-0 col-xs-12 col-sm-12 col-lg-6 text-center">
            <h2 class="titleFont">Newest Mods</h2>
            <hr>
        </div>
    </div>

    <?php require APPROOT . '/views/partials/pagination.partial.php'; ?>

    <div class="row">
        <div class="offset-md-2 offset-xs-0"></div>
        <div class="col-xs-12 col-sm-12">
            <!-- This is to load heart icons for liking -->
            <div class="d-none">
                <div id="solidHeart">
                    <a href="#" class="likeLink">
                        <h3><i class="fas fa-heart"></i></h3>
                    </a>
                </div>
                <div id="simpleHeart">
                    <a href="#" class="likeLink">
                        <h3><i class="far fa-heart"></i></h3>
                    </a>
                </div>
            </div>

            <?php foreach ($data['mods'] as $mod) : ?>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title titleFont"><?php echo $mod->mod_title; ?></h4>
                            <?php if (isLoggedIn()) : ?>
                                <!-- Check if mod has not been saved by user -->
                                <?php if (!in_array($mod->mod_id, $data['savedMods'])) : ?>
                                    <a href="#a" class="likeLink" data-mod="<?php echo $mod->mod_id; ?>">
                                        <h3><i class="far fa-heart"></i></h3>
                                    </a>
                                <?php else : ?>
                                    <a href="#a" class="unlikeLink" data-mod="<?php echo $mod->mod_id; ?>">
                                        <h3><i class="fas fa-heart"></i></h3>
                                    </a>
                                <?php endif; ?>
                            <?php else : ?>
                                <a href="#a" class="likeLink" data-mod="<?php echo $mod->mod_id; ?>">
                                    <h3><i class="far fa-heart"></i></h3>
                                </a>
                            <?php endif; ?>
                        </div>
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

    $(document.body).on('mouseenter', '.likeLink', function() {
        var solidHeart = $('#solidHeart').find('i').attr('class');
        $(this).find('i').attr('class', solidHeart);
    });

    $(document.body).on('mouseleave', '.likeLink', function() {
        var simpleHeart = $('#simpleHeart').find('i').attr('class');
        $(this).find('i').attr('class', simpleHeart);
    });

    $(document.body).on('click', '.likeLink', function() {
        var modId = $(this).data('mod');
        var likeLink = $(this);
        $.post('<?php echo URLROOT; ?>/mods/saveModAjax', {
                modId: modId
            },
            function(res, status) {
                var data = JSON.parse(res);

                if (data.success) {
                    likeLink.replaceWith(`<a href="#a" class="unlikeLink" data-mod="${modId}"><h3><i class="fas fa-heart"></i></h3></a>`);
                } else {
                    $('.modErrorMessage').slideUp();
                    $('#error' + modId).slideDown();
                    $('#error' + modId).text(data.message);
                    setTimeout(function() {
                        $('#error' + modId).slideUp();
                    }, 3000);
                }
            }
        );
    });

    $(document.body).on('click', '.unlikeLink', function() {
        var unlikeLink = $(this);
        var modId = $(this).data('mod');

        $.post('<?php echo URLROOT; ?>/mods/unsaveModAjax', {
                modId: modId
            },
            function(res, status) {
                var data = JSON.parse(res);

                if (data.success) {
                    unlikeLink.replaceWith(`<a href="#a" class="likeLink" data-mod="${modId}"><h3><i class="far fa-heart"></i></h3></a>`);
                } else {
                    $('.modErrorMessage').slideUp();
                    $('#error' + modId).slideDown();
                    $('#error' + modId).text(data.message);
                    setTimeout(function() {
                        $('#error' + modId).slideUp();
                    }, 3000);
                }
            }
        );
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>