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
        
        <div class="text-center position-absolute bottom-0 start-50 translate-middle-x">
            <button class="btn btn-warning text-nowrap scheduleBtn" id="<?php echo date('m-d-Y', strtotime($date)); ?>" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fas fa-undo-alt"></i> <strong>Swap</strong></button>
        </div>
    </div>
</td>