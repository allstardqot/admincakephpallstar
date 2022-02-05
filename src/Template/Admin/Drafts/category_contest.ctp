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
					 echo 'No Image';
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
			<th class="text-center"><INPUT class="checkbox" type="checkbox" onchange="checkAll(this)" name="chk[]" /></th>
			<th>Category Name</th>
			<th>Winning Amount</th>
			<th>Contest Size</th>
			<th>Entry Fee</th>
		</tr>
		<?php
		$i = 0;
		foreach($v as $key=>$val)
		{
			?>
			<tr>
				<td class="text-center">
					<?php 
					if (in_array($val->id, $arr))
					{  
						?>
							<input id="selectGroup" class="selectGroup" checked onclick="return false;" type="checkbox" name="contest_id[]" value="<?=$val->id ?>">
						<?php
					}
					else
					{
					?>
						<input id="selectGroup" class="selectGroup checkbox" type="checkbox" name="contest_id[]" value="<?=$val->id ?>">
					<?php
					}	
					?>
				</td>
				<td><span style="font-weight: bold;"> </span><?=$this->Custom->getCategoryName($val->category_id) ?><br>
					<!-- <span style="font-weight: bold;">Contest Name : </span><?=$val->contest_name ?><br> -->
					
				</td>
				<td>
					<span style="font-weight: bold;"> </span><?=$val->winning_amount ?>
				</td>
				<td>
					<span style="font-weight: bold;"> </span><?=$val->contest_size ?><br>
					
				</td>
				<td>
					<span style="font-weight: bold;"> </span><?=$val->entry_fee ?><br>
				</td>
			</tr>
		<?php 
				$i++;
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