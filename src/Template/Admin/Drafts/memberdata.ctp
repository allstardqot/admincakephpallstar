<?php
    if(!empty($newDataArray['position_data']) && !empty($newDataArray['selectname'])){
        ?>
<select class='select2 select2-hidden-accessible' name='team_member[]' multiple='multiple' data-placeholder='Select team' style="width: 100%;" tabindex="-1" aria-hidden="true">
    <?php
    $formated_position_data=$newDataArray['position_data'];
		foreach($formated_position_data as $team=>$memberValue){
			//foreach($teamvalue as $key=>$memberValue){
				echo "<option value='".$memberValue['PlayerID']."'>".$memberValue['GameDate']."-".$memberValue['Team']."-".$memberValue['Opponent']."-".$memberValue['Position']."-".$memberValue['Name']."-".$memberValue['FantasyPointsPPR']."</option>";
			//}
		}
		?>
</select>
<?php }else{
        echo "Data not Found";
        }
?>
<script>
$(document).ready(function(){
		//$('.js-example-basic-multiple').select2();
        $('.select2').select2({
            closeOnSelect: false
        });
});
</script>