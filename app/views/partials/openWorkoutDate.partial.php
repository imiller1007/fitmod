<td class="calendar-table" style="min-width:250px;">
    <!-- Title for mod goes here -->
    <div id="date<?php echo $key ?>" class="calendar-table position-relative">
        <div class="titleFont text-center">
            <?php echo $data['weekData'][$index]->mod_title; ?>
            <hr class="my-1">
        </div>
        <ul>
            <!-- Loop through to make a list of exercises of that mod -->
            <?php for ($i = 1; $i <= EXERCISEMAX; $i++) : ?>
                <?php
                $exerId = 'exer' . $i . '_id';
                $exerName = 'exer' . $i . 'Name';
                $exerType = 'exer' . $i . 'Type';
                $exerNum = 'exer' . $i . '_num';
                ?>
                <?php if ($data['weekData'][$index]->$exerId !== null) : ?>
                    <li><strong><?php echo $data['weekData'][$index]->$exerName ?> - </strong>
                        <?php if ($data['weekData'][$index]->$exerType == 'cardio') : ?>
                            <?php echo $data['weekData'][$index]->$exerNum . ' Min.' ?></li>
                        <?php else : ?>
                            <?php echo ($data['weekData'][$index]->$exerNum > 1) ? $data['weekData'][$index]->$exerNum . ' Sets' :  $data['weekData'][$index]->$exerNum . ' Set' ?></li>
                        <?php endif; ?>
            <?php endif; ?>
        <?php endfor; ?>
        </ul>
        
        <div class="position-absolute bottom-0 start-50 translate-middle-x">
            <?php if(date('m/d/Y') == date('m/d/Y', strtotime($date))) : ?>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-warning text-nowrap scheduleBtn" id="<?php echo date('m-d-Y', strtotime($date)); ?>" data-mod-id="<?php echo $data['weekData'][$index]->mod_id; ?>" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fas fa-undo-alt"></i> <strong>Swap</strong></button>
                    <div>&nbsp;</div>
                    <a href="<?php echo URLROOT ?>/workouts/active" class="btn btn-success text-nowrap"><i class="fas fa-running"></i> <strong>Start</strong></a>
                </div>
            <?php else : ?>
                <button class="btn btn-warning text-nowrap scheduleBtn" id="<?php echo date('m-d-Y', strtotime($date)); ?>" data-mod-id="<?php echo $data['weekData'][$index]->mod_id; ?>" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fas fa-undo-alt"></i> <strong>Swap</strong></button>
            <?php endif; ?>

        </div>
    </div>
</td>