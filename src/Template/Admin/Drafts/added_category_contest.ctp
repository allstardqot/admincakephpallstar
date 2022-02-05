<tbody>
<?php 
if(!empty($newRecords))
{
	foreach ($newRecords as $k => $v) 
	{ 
	?>
		<tr class="cat_heading">
			<td class="border-0">
			<div class="cat_thumb_outer">
			<?php 
				$image = $this->Custom->getCategoryImage($k);
				if($image!=''){
					echo '<img src="'.$this->request->webroot.'uploads/category_image/'.$image.'" hight="80px" width="80px" alt="">';
				}else{
					 echo '<img src="'.$this->request->webroot.'img/front_home/no_image.png" hight="80px" width="80px" alt="">';
				}
			?>
			</div>
			</td>
			<td class="border-0">
			<h4><?=$this->Custom->getCategoryName($k) ?></h4>
			</td>
			<td colspan="3" class="text-right border-0">
				
			</td>
		</tr>
		<tr>
			<!--<th>Category Name</th>-->
			<th>Winning Amount</th>
			<th>Contest Size</th>
			<th>Entry Fee</th>
			<th>Contest Status</th>
			<th>View</th>
		</tr>
		<?php
		$i = 0;
		foreach($v as $key=>$val)
		{
			$data	=	$this->Custom->getCategoryDetails($val->category_id);
			$joined	=	$this->Custom->getContestLeftMatch($val->id,$match_id); 
			$left 	= 	($val->contest_size-$joined);
			if($val->match_contest['isCanceled']==0){
				$mtchStatus = 'Active';
			}else{
				$mtchStatus = 'Cancelled';
			}
		
			?>
			<tr>
				<td>
					<span style="font-weight: bold;"> </span><?=$val->winning_amount ?>
				</td>
				<td>
					<span style="font-weight: bold;"> </span><?php echo $joined ?>/<?php echo $val->contest_size?><br>
					
				</td>
				<td>
					<span style="font-weight: bold;"> </span><?=$val->entry_fee ?><br>
				</td>
				<td>
					<?=$mtchStatus;?>
				</td>
				<td width="5%">
					<?php echo $this->Html->link('View',['controller'=>'drafts','action'=>'contestDetails',$val->id,$id],['class'=>'btn btn-primary btn-sm viewBTN']); ?>
					<?php /* <a class="btn btn-primary btn-sm viewBTN" href="<?=SITE_URL."admin/schedule/contest_details/".$value->id."/".$id?>">View</a> */ ?>
				</td>
			</tr>
		<?php 
				$i++;
				// if($i == 5)
				// {
					// break;
				// }
			}
		} 
	} 
	else 
	{ ?>
		<tr>
			<td colspan="4" style="text-align: center;">No data found</td>
		</tr>
<?php 	} ?>
</tbody>