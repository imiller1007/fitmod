<?php require APPROOT . '/views/inc/header.php'; ?>

<form>

<label for="exampleDataList" class="form-label">Datalist example</label>
<input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="Type to search..." oninput="autocompleteTest()">
<datalist id="datalistOptions">
  <?php foreach($data['exercises'] as $exercise)  : ?>
    <option value="<?php echo $exercise->exercise_name ?>" >
  <?php endforeach; ?>
</datalist>

</form>

<br>

<h5>Is this value in the Exercise table?  <bold id="answer" class="text-danger">No...</bold></h5>


<script>

exerciseArr = [];

$(document).ready(function(){

    
    <?php foreach($data['exercises'] as $exercise) : ?>

        exerciseArr.push(<?php echo json_encode($exercise); ?>);

    <?php endforeach; ?>
    
});

function autocompleteTest() {
    var inputVal = $("#exampleDataList").val();
    var found = false;
    
    for(var i = 0; i < exerciseArr.length; i++) {
        if(exerciseArr[i].exercise_name.toUpperCase().trim() === inputVal.toUpperCase().trim()){
            found = true;
        }
    }

    if(found == true){
        $("#answer").text('Yes!');
        $("#answer").attr("class", "text-success");
    }else{
        $("#answer").text('No...');
        $("#answer").attr("class", "text-danger");
    }

}

</script>




<?php require APPROOT . '/views/inc/footer.php'; ?>