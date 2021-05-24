<td class="calendar-table" style="min-width:250px; <?php echo ($data['weekData'][$index]->status == 'closed') ? 'background-color:#f0f0f0;' : ''; ?>">
    <!-- Title for mod goes here -->
    <div id="date<?php echo $key ?>" class="position-relative">
        <div class="titleFont text-center">
            <?php echo $data['weekData'][$index]->mod_title; ?>
            <hr class="my-1">
        </div>
        <ul style="list-style: none;" class="p-0">
            <!-- Loop through to make a list of exercises of that mod -->
            <?php for ($i = 1; $i <= EXERCISEMAX; $i++) : ?>
                <?php
                $exerId = 'exer' . $i . '_id';
                $exerName = 'exer' . $i . 'Name';
                $exerType = 'exer' . $i . 'Type';
                $exerNum = 'exer' . $i . '_num';
                ?>
                <!-- Check exercise $i if they have value -->
                <?php if ($data['weekData'][$index]->$exerId !== null) : ?>
                    <!-- Number of sets that have been completed for each exercise -->
                    <?php $setCount = 0; ?>
                    <!-- <ul style="list-style-type:square"> -->
                        <!-- Loop through arrays with individual set data -->
                        <?php foreach ($weekDataArray as $weekData) : ?>
                            <?php
                            // Check if the set is for looped date and shares exercise ID
                            // Print set data based off the type of exercise
                            if (date('m/d/Y', strtotime($weekData['workout_date'])) == date('m/d/Y', strtotime($date)) && $weekData['setExerId'] == $data['weekData'][$index]->$exerId) {
                                // Update set count by 1 for every set found
                                $setCount++;
                            }
                            ?>
                        <?php endforeach; ?>

                        <?php
                            if($data['weekData'][$index]->$exerType != 'cardio'){
                                $setDifference = $data['weekData'][$index]->$exerNum - $setCount;
                                $exerciseStatus = '';
                                if($setDifference == 0){
                                    $exerciseStatus = 'complete';
                                }elseif($setDifference == $data['weekData'][$index]->$exerNum){
                                    $exerciseStatus = 'none';
                                }
                            }else{
                                if($setCount > 0){
                                    $exerciseStatus = 'complete';
                                }else{
                                    $exerciseStatus = 'none';
                                }
                            }

                        ?>
                    <!-- </ul> -->
                    <li>
                    <?php 
                        switch ($exerciseStatus) {
                            case 'complete': echo '<i class="fas fa-check-circle text-success"></i>';
                            break;
                            case 'none': echo '<i class="far fa-circle text-danger"></i>';
                            break;
                            default: echo '<i class="fas fa-adjust text-warning"></i>';
                        }
                    ?>
                    <strong><?php echo $data['weekData'][$index]->$exerName ?> - </strong> 
                        <?php if ($data['weekData'][$index]->$exerType == 'cardio') : ?>
                            <?php echo $data['weekData'][$index]->$exerNum . ' Min.' ?>
                        <?php else : ?>
                            <?php echo ($data['weekData'][$index]->$exerNum > 1) ? $data['weekData'][$index]->$exerNum . ' Sets' :  $data['weekData'][$index]->$exerNum . ' Set' ?>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            <?php endfor; ?>
        </ul>
        <?php if($data['weekData'][$index]->status == 'started') : ?>
            <div class="text-center position-absolute bottom-0 start-50 translate-middle-x">
                <a href="<?php echo URLROOT ?>/workouts/active" class="btn btn-success text-nowrap"><i class="fas fa-running"></i> <strong>Resume</strong></a>
            </div>
        <?php else : ?>
            <div class="text-center position-absolute bottom-0 start-50 translate-middle-x">
                <?php 
                    // echo date('m-d-Y', strtotime($date)); 
                ?>
                <a href="<?php echo URLROOT ?>/workouts/results/<?php echo date('Y-m-d', strtotime($date)) ?>" class="btn btn-primary text-nowrap"><i class="fas fa-file-alt"></i> <strong>Details</strong></a>
            </div>
        <?php endif; ?>

    </div>
</td>