<?php 
    $pageNum = $data['pageNum'];
    $totalPages = $data['totalPages'];
?>
<div class="row my-3">
    <div class="col-12 px-0 text-center">
        <div class="pagination titleFont">
            <a class="mx-xs-5 mx-md-3 mx-lg-4 mx-xl-4" <?php echo ($pageNum <= 1) ? 'href="#" disabled' : 'href="' . URLROOT . '/' . $paginationLink . '?pageNum=' . $pageNum - 1 . '"'; ?>>&laquo;</a>
            <?php if($totalPages <= 7) : ?>
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a class="mx-xs-5 mx-md-3 mx-lg-4 mx-xl-4 <?php echo ($i == $pageNum) ? 'active' : ''; ?>" href="<?php echo URLROOT . '/' . $paginationLink . '?pageNum=' . $i ?>"><?php echo $i ?></a>
            <?php endfor; ?>
            <?php else : ?>
                <?php 
                    $visiblePages = [];
                    $counter = 1;
                    array_push($visiblePages, $pageNum);
                    while(count($visiblePages) < 5){
                        $prevPage = $pageNum - $counter;
                        $nextPage = $pageNum + $counter;

                        if($prevPage > 0){
                            array_unshift($visiblePages, $prevPage);
                        }

                        if($nextPage <= $totalPages){
                            array_push($visiblePages, $nextPage);
                        }
                        $counter++;
                    }    
                ?>
                <?php if($visiblePages[0] != 1) : ?>
                    <a class="mx-xs-5 mx-md-3 mx-lg-4 mx-xl-4 " href="<?php echo URLROOT . '/' . $paginationLink . '?pageNum=1' ?>">. . .</a>
                <?php endif; ?>
                <?php for($i = 0; $i < count($visiblePages); $i++) : ?>
                    <a class="mx-xs-5 mx-md-3 mx-lg-4 mx-xl-4 <?php echo ($visiblePages[$i] == $pageNum) ? 'active' : ''; ?>" href="<?php echo URLROOT . '/' . $paginationLink . '?pageNum=' . $visiblePages[$i] ?>"><?php echo $visiblePages[$i] ?></a>
                <?php endfor; ?>
                <?php if(end($visiblePages) != $totalPages) : ?>
                    <a class="mx-xs-5 mx-md-3 mx-lg-4 mx-xl-4 " href="<?php echo URLROOT . '/' . $paginationLink . '?pageNum='.$totalPages ?>">. . .</a>
                <?php endif; ?>
            <?php endif; ?>
            <a class="mx-xs-5 mx-md-3 mx-lg-4 mx-xl-4" <?php echo ($pageNum >= $totalPages) ? 'href="#" disabled' : 'href="' . URLROOT . '/' . $paginationLink . '?pageNum=' . $pageNum + 1 . '"'; ?>>&raquo;</a>
        </div>
    </div>
</div>