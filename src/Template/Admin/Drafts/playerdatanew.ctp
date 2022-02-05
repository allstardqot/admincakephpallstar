<tbody>
	<tr>
		<td colspan="5">
			<div class="chkAlign">
				<!-- <input type="checkbox" class="checkbox"  onchange="checkAll(this)" name="chk[]" /> Select All -->
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="5">
			<span id="showstartdate"></span>
			<span id="showenddate"></span>
			<?php 
			//pr($draft_data);
			$defalutlisting=DefalutListing;
			if(!empty($draft_data)){
				$in=1;
				$index = 1;
						$tindex = 1;
						$pndex = 1;
						//<input type="checkbox" class="checkbox" name="gchk[]" value="'.$key.'" />
						echo '<div class="accordion" id="accordionExample"><table id="record_show" colspan="5" style="width: 100%;">';
				echo "<tr><td>Sr no</td><td class='filter_list'>Filter listing</td><td>Display Name</td><td class='select_mem'>Select Player (Start date-Team name-Opponent-Position-Player name-Projected points)</td><td>Action</td></tr>";
				foreach($draft_data as $dkey=>$dvalue){
					
						
						
						echo "<tr id='maintr_".$in."'><td class='sr_no'>".$in."</td><td><select name='filter_listing[]' class='filter_listing' style='width: 100%;' tabindex='-1' id='filter_select_".$in."'><option value=''>Choose Any</option>";
						foreach($defalutlisting AS $key => $value) {
							$select="";
							if($dvalue['filter_listing']==$value){
								$select="selected";
							}
								echo "<option value='".$value."' ".$select.">".$value."</option>";
							$index++;
						}
						echo "</select></td><td><input type='text' name='display_name[]' id='display_name_".$in."' value='".$dvalue['display_name']."'></td>
							<td class='member_name' id='member_select_".$in."'>";

							if(!empty($formated_week_data[$dvalue['filter_listing']])){
								//echo $dvalue['filter_listing']; pr($formated_week_data[$dvalue['filter_listing']]);
								echo "<select class='select2 select2-hidden-accessible' name='team_member[]' multiple='multiple' data-placeholder='Select team' style='width: 100%;' tabindex='-1' aria-hidden='true'>";
									foreach($formated_week_data[$dvalue['filter_listing']] as $team=>$teamvalue){
										$select="";
										if(in_array($teamvalue['PlayerID'],$dvalue['team_member'])){
											$select="selected";
										}
											echo "<option value='".$teamvalue['PlayerID']."' ".$select.">".$teamvalue['GameDate']."-".$teamvalue['Team']."-".$teamvalue['Opponent']."-".$teamvalue['Position']."-".$teamvalue['Name']."-".$teamvalue['FantasyPointsPPR']."</option>";
									}
								echo "</select>";
							}
							
							echo "</td>";
						if($in>1){
							echo "<td><input type='button' value='Remove' id='remove' rel='maintr_".$in."'></td></tr>";
						}else{
							echo "<td><input type='button' value='Add More' id='add_more'></td></tr>";
						}
												
					echo '</div>';
					$in++;
				}
			}else{
				echo '<div class="accordion" id="accordionExample"><table id="record_show" colspan="5" style="width: 100%;">';
					$index = 1;
					$tindex = 1;
					$pndex = 1;
					//<input type="checkbox" class="checkbox" name="gchk[]" value="'.$key.'" />
					echo "<tr><td>Sr no</td><td class='filter_list'>Filter listing</td><td>Display Name</td><td class='select_mem'>Select Player (Start date-Team name-Opponent-Position-Player name-Projected points)</td><td>Action</td></tr>";
					echo "<tr id='maintr_1'><td class='sr_no'>".$index."</td><td><select name='filter_listing[]' class='filter_listing' style='width: 100%;' tabindex='-1' id='filter_select_1'><option value=''>Choose Any</option>";
					foreach($defalutlisting AS $key => $value) {
							echo "<option value=".$value.">".$value."</option>";
						$index++;
					}
					echo "</select></td><td><input type='text' name='display_name[]' id='display_name_1'></td>
						<td class='member_name' id='member_select_1'></td>
						<td><input type='button' value='Add More' id='add_more'></td></tr>";
				echo '</div>';
			}
			?>
		</td>
	</tr>
</tbody>

<script>
	$('.accordion input[type="checkbox"]').click(function(e) {
		e.stopPropagation();
	});
	$(document).on('change','.filter_listing',function(){
		var fil_list = $(this).val();
		var week_list = $("#weekList").val();
		var seasonList = $("#seasonList").val();
		var seasontypeList = $("#seasontypeList").val();
		var stdate = $("#startfildate").val();
		var enddate = $("#endfildate").val();
		var listingId = $(this).attr('id').split('_')[2];
		$.ajax({
			type:"POST",
			url:'<?php echo $this->Url->build(['controller'=>'drafts','action'=>'memberdata']); ?>',
			data:{team:fil_list,week:week_list,stdate:stdate,enddate:enddate,seasonList:seasonList,seasontypeList:seasontypeList},
			success:function(data){
				$("#member_select_"+listingId).html(data);
			}
		});
		$( "#maintr_"+listingId+" .select2-hidden-accessible" ).attr("name","team_member["+listingId+"][]");
		//$(".select2-hidden-accessible").attr("name","team_member["+listingId+"][]");
		$("#display_name_"+listingId).val(fil_list);
	});
	$(document).ready(function(){
		$('.select2').select2({
            closeOnSelect: false
        });
		var stdatee='<?= $formated_week_data['startdate'];?>';
		var endDate='<?= $formated_week_data['endDate'];?>';
		$("#showstartdate").html("Week Start Date"+stdatee);
		$("#showenddate").html("Week End Date"+endDate);
		//$("#startfildate").val(stdatee);
		$('#add_more').click(function() {
            //! Cloning the HTML 
			var sr_no = $('.sr_no').last().text();
			var $clonedContent = $('#maintr_'+sr_no).clone();

			$id     = $clonedContent.attr('id').split('_')[1];
			
			$nid    = parseInt($id) + 1; 
			$trid   = 'maintr_'+$nid;
			$clonedContent.attr('id',$trid);
			$clonedContent.find('.sr_no').text($nid);
			$clonedContent.find('#display_name_'+$id).attr("value",'').attr("id",'display_name_'+$nid);
			$clonedContent.find('select').attr("value",'').attr("id",'filter_select_'+$nid);
			$clonedContent.find('.select2').remove();
			$clonedContent.find('.member_name').attr("id",'member_select_'+$nid);

			$clonedContent.find('#add_more').attr("id",'remove').attr("value",'Remove').attr("rel",$trid);
			$clonedContent.find('#remove').attr("value",'Remove').attr("rel",$trid);
			
			$clonedContent.insertAfter('#maintr_'+sr_no);
		});
        
        $(document).on('click','#remove',function(){    
            $trid = $(this).attr('rel');
            console.log('Ts',$trid);
            $('#'+$trid).remove();
        });
	});
</script>