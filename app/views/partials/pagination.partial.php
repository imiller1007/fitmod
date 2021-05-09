<div class="row my-3">
    <div class="col-12 text-center">
        <div class="pagination titleFont">
            <a class="mx-auto" <?php echo ($pageNum <= 1) ? 'href="#" disabled' : 'href="' . URLROOT . '/' . $paginationLink . '?pageNum=' . $pageNum - 1 . '"'; ?>>&laquo;</a>
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a class="mx-md-4 <?php echo ($i == $pageNum) ? 'active' : ''; ?>" href="<?php echo URLROOT . '/' . $paginationLink . '?pageNum=' . $i ?>"><?php echo $i ?></a>
            <?php endfor; ?>
            <a class="mx-auto" <?php echo ($pageNum >= $totalPages) ? 'href="#" disabled' : 'href="' . URLROOT . '/' . $paginationLink . '?pageNum=' . $pageNum + 1 . '"'; ?>>&raquo;</a>
        </div>
    </div>
</div>