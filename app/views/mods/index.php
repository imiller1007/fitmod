<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container mt-5" id="card-section">

    <div class="row">
        <div class="offset-lg-3 offset-xs-0 col-xs-12 col-sm-12 col-lg-6 text-center">
            <h2 style="font-family: 'Montserrat', sans-serif;">Newest Mods</h2>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="offset-md-2 offset-xs-0"></div>
        <div class="col-xs-12 col-sm-12">

            <?php foreach ($data['mods'] as $mod) : ?>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title WOtitle"><?php echo $mod->mod_title; ?></h3>
                        <p class="card-text"><?php echo $mod->mod_desc; ?></p>
                        <table class="table">
                            <thead>
                                <tr class="table-dark">
                                    <th scope="col" style="width: 33%;">Exercise</th>
                                    <th scope="col" style="width: 33%;">Set Count</th>
                                    <th scope="col" style="width: 33%;">Target</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 1; $i <= 10; $i++) : ?>
                                    <?php $exerName = 'exer' . $i . '_name'; ?>
                                    <?php $exerSets = 'exer' . $i . '_sets'; ?>
                                    <?php $exerTarget = 'exer' . $i . '_target'; ?>
                                    <?php if ($mod->$exerName != null) : ?>
                                        <tr>
                                            <th scope="row"><?php echo $mod->$exerName; ?></th>
                                            <td><?php echo $mod->$exerSets; ?></td>
                                            <td><?php echo $mod->$exerTarget; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            <a href="#" class="btn btn-dark">Save Mod</a>
                        </div>
                        <div class="d-flex justify-content-center mt-1">
                            <a href="#" class="dark-link">View Comments (0)</a>
                        </div>
                    </div>
                </div>
                <br>
            <?php endforeach; ?>

        </div>
    </div>

</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>