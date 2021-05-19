<td class="calendar-table" style="min-width:250px; width:300px;">
    <!-- Title for mod goes here -->
    <div id="date<?php echo $key ?>">
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
                <!-- Check exercise $i if they have value -->
                <?php if ($data['weekData'][$index]->$exerId !== null) : ?>
                    <!-- Number of sets that have been completed for each exercise -->
                    <?php $setCount = 0; ?>
                    <li><strong><?php echo $data['weekData'][$index]->$exerName ?></strong>
                        <ul style="list-style-type:square">
                            <!-- Loop through arrays with individual set data -->
                            <?php foreach ($weekDataArray as $weekData) : ?>
                                <?php
                                // Check if the set is for looped date and shares exercise ID
                                // Print set data based off the type of exercise
                                if (date('m/d/Y', strtotime($weekData['workout_date'])) == date('m/d/Y', strtotime($date)) && $weekData['setExerId'] == $data['weekData'][$index]->$exerId) {
                                    if ($data['weekData'][$index]->$exerType == 'adjWt') {
                                        echo '<li>' . $weekData['set_weight'] . ' lb | ' . $weekData['set_value'] . ' reps </li>';
                                    } elseif ($data['weekData'][$index]->$exerType == 'bodyWt') {
                                        echo '<li>' . $weekData['set_value'] . ' reps </li>';
                                    } else {
                                        echo '<li>' . $weekData['set_value'] . ' / ' . $data['weekData'][$index]->$exerNum . ' min.</li>';
                                    }
                                    // Update set count by 1 for every set found
                                    $setCount++;
                                }
                                ?>
                            <?php endforeach; ?>
                            <?php
                            // If exercise was NOT a cardio exercise, list out every set that was not recorded
                            if ($data['weekData'][$index]->$exerType != 'cardio') {
                                $setDifference = $data['weekData'][$index]->$exerNum - $setCount;
                                for ($j = $setDifference; $j >= 0; $j--) {
                                    if ($j != 0) {
                                        echo '<li><i class="text-muted">not complete</i></li>';
                                    }
                                }
                            // Else list that the cardio exercise was not complete if appropriate set was not found
                            }elseif($setCount < 1){
                                echo '<li><i class="text-muted">not complete</i></li>';
                            }
                            ?>
                        </ul>
                <?php endif; ?>
            <?php endfor; ?>
        </ul>
    </div>
</td>