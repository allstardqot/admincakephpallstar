<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Price Breakdown</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/contest">Contest</a></li>
						<li class="breadcrumb-item active">Price Breakdown</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<!-- /.col -->
				<div class="col-md-12">
					<div class="card">
						<div class="card-header p-2">
							<ul class="nav nav-pills">
						</div>
						<!-- /.card-header -->
						<div class="card-body">
							<div class="tab-content">
								<div class="post">
									<div class="user-block">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-4">Contest size : <?php echo $contest['contest_size']?></div>
												<div class="col-md-4">
													Winning Points : 
													<?php echo $contest['winning_amount']; ?>
												</div>
												<div class="col-md-4">Entry Points : <?php echo $contest['entry_fee']?></div>
												<input type="hidden" class="winPrice" value="<?=$contest['winning_amount']?>">
												<input type="hidden" class="contestSize" value="<?=$contest['contest_size']?>">
											</div>
										</div>
									</div>
									<?php
										echo $this->Form->create($data, array('url' => array('controller' => 'Contest', 'action' => 'price'), 'id' => 'addForm', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'));  
										?>
									<input type="hidden" name="contest_id" value="<?=$contest['id']?>">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th><label># </label> </th>
												<th><label>Start rank</label> </th>
												<th><label>End rank</label> </th>
												<th><label>Percentage (each)</label> </th>
												<th><label>Points (each)</label> </th>
												<th><label>Total percentage (<span class="tPer">100</span>) </label> </th>
												<th><label>Total Points (<span class="tPri"><?=$contest['winning_amount']?></span>) </label> </th>
												<!--<th><label></label> </th>-->
											</tr>
										</thead>
										<tbody class="question-option-body">
											<tr class="type_option type_options_body">
												<td>1</td>
												<td>
													<input name="start[]" class="form-control option_input startTag srt_1" type="number" value="1" readonly="readonly">
												</td>
												<td>
													<input name="end[]" class="form-control option_input end_1 endTag" type="number" min="0">
												</td>
												<td>
													<input name="percentage[]" class="form-control option_input percentTag per_1">
												</td>
												<td>
													<input name="price[]" class="form-control option_input price1 priceTag" type="">
												</td>
												<td>
													<input name="percentage_seprate[]" class="form-control option_input perSep_1 percentValue" type="">
												</td>
												<td>
													<input name="price_seprate[]" class="form-control option_input priceSep1 priceValue" type="">
												</td>
												<!--<td>
													<a href="javascript:void(0);" class="btn btn-danger btn-sm remove">Remove</a>
												</td>-->
											</tr>
										</tbody>
									</table>
									<a href="javascript:void(0);" class="btn btn-primary btn-sm add-more">Add Row</a><br>
									<br> <button type="submit" name="custom" class="btn btn-primary btn-sm saveData">Update Breakup</button>
									<?php echo $this->Form->end(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var wrapper		=	$('.question-option-body');
        var option_clone=	'<tr class="type_option"><td>1</td><td><input name="start[]" class="form-control option_input startTag srt_1" type="number" readonly="readonly"></td><td><input name="end[]" class="form-control option_input end_1 endTag" type="number" min="0"></td><td><input name="percentage[]" class="form-control option_input per_1 percentTag"></td><td><input name="price[]" class="form-control option_input price1 priceTag" type=""></td><td><input name="percentage_seprate[]" class="form-control option_input percentValue perSep_1" type=""></td><td><input name="price_seprate[]" class="form-control option_input priceValue priceSep1" type=""></td><td><a href="javascript:void(0);" class="btn btn-danger btn-sm remove">Remove</a></td></tr>';

		$('.add-more').click(function () {
			countRow	=	0;
			$(".option_input").each(function(key, value){
				if($(this).val()=='' || $(this).val()==" " || $(this).val()=="0" || $(this).val()=="NaN"){
					countRow++;
				}
			});
			
			if(countRow>0) {
				alert('Please fill this row first.');
			} else {
				var winPrice	=	$('.winPrice').val();
				var percentValue=	0;
				var priceValue	=	0;
				$('.percentValue').each(function() {
					percentValue +=	parseInt($(this).val());
				});
				$('.priceValue').each(function() {
					priceValue +=	parseInt($(this).val());
				});
				if(winPrice <= parseInt(priceValue) && parseInt(percentValue) >= 100) {
					alert('No you can not add another row now');
					return false;
				}
				var option_count=	$('.type_option').length+1;
				var data	=	option_clone.replace("srt_1", "srt_"+ option_count);
				var data	=	data.replace("end_1", "end_"+ option_count);
				var data	=	data.replace("per_1", "per_"+ option_count);
				var data	=	data.replace("price1", "price"+ option_count);
				var data	=	data.replace("perSep_1", "perSep_"+ option_count);
				var data	=	data.replace("priceSep1", "priceSep"+ option_count);
				var data	=	data.replace("1", option_count);
				
				$(wrapper).append(data);
				$('.remove').click(function() {
					$(this).parent().parent('.type_option').remove(); 
				});

				var optn_cnt	=	$('.type_option').length;
				var finl_cnt	=	$('.type_option').length-1;
				var endPoint	=	$('.end_'+optn_cnt).val();
				var contestSize	=	$('.contestSize').val();
				if(endPoint == '') {
					$('.end_'+optn_cnt).val(0)
				}
				if(endPoint >= contestSize){
					alert('Contest size can not be greater than '+contestSize);
					return false;
				}
				
				var newStrt	=	(parseFloat(option_count) - parseFloat(1));
				lastEndVal	=	$('.end_'+newStrt).val();
				newStrtVal	=	(parseFloat(lastEndVal) + parseFloat(1));
				$('.srt_'+option_count).val(newStrtVal);
				
				$(document).on('keyup','.per_'+option_count, function() {
					percent	=	$(this).val();
					price	=	winPrice*(percent/100);
					price	=	price.toFixed(2);
					$('.price'+option_count).val(price);
					str1	=	$('.srt_'+option_count).val();
					end1	=	$('.end_'+option_count).val();
					rsltEnd	=	(parseFloat(end1)-parseFloat(str1));
					rsltEnd	=	(parseFloat(rsltEnd)+parseFloat(1));
					finPer	=	(percent * rsltEnd);
					finPri	=	(price * rsltEnd);
					$('.perSep_'+option_count).val(finPer.toFixed(2));
					$('.priceSep'+option_count).val(finPri.toFixed(2));
					
					var previousPrsnt	=	0;
					var previousPrice	=	0;
					$('.percentValue').each(function() {
						previousPrsnt +=	parseFloat($(this).val());
					});
					$('.priceValue').each(function() {
						previousPrice +=	parseFloat($(this).val());
					});
					console.log(previousPrsnt);
					console.log(previousPrice);
					if(winPrice < parseFloat(previousPrice) && parseFloat(previousPrsnt) > 100) {
						alert('Total price is greater than winning price and total percentage is greater than 100%.');
						return false;
					}
					
					$('.tPer').text(previousPrsnt);
					$('.tPri').text(previousPrice);
				});
			}
		});
		
		var contestSize	=	$('.contestSize').val();
		$(document).on('blur', '.endTag', function() {
			var endPoint	=	$(this).val();
			if(endPoint == '') {
				$(this).val(0);
			}
			endRank	=	$(this).parent().parent().prev().find('.endTag').val();
			if(parseInt(endRank) != 'undefined' && parseInt(endRank) > parseInt(endPoint)) {
				alert('Current rank can not be less then prevoius rank.');
				$(this).val('');
				$(this).focus();
				return false;
			}
			if(parseInt(endPoint) > parseInt(contestSize)){
				alert('Contest size can not be greater than '+contestSize);
				$(this).val('');
				$(this).focus();
				return false;
			}
		});
		
		$(document).on('keyup', '.per_1', function() {
			var winPrice=	$('.winPrice').val();
			percent		=	$(this).val();
			thisVal		=	$(this).parent().parent();
			price		=	winPrice*(percent/100);
			price	=	price.toFixed(2);
			thisVal.find('.priceTag').val(price);
			
			srt1	=	$('.srt_1').val();
			end1	=	$('.end_1').val();
			rsltEnd	=	(parseInt(end1)-parseInt(srt1));
			rsltEnd	=	(parseInt(rsltEnd)+parseInt(1));
			finPer	=	(percent * rsltEnd);
			finPri	=	(price * rsltEnd);
			
			previousPrsnt	=	0;
			previousPrice	=	0;
			$('.percentValue').each(function() {
				var thiClass	=	$(this).attr('class');
				if(thiClass.indexOf("perSep_1") == -1) {
					previousPrsnt +=	parseFloat($(this).val());
				}
			});
			$('.priceValue').each(function() {
				var thiClass	=	$(this).attr('class');
				if(thiClass.indexOf("priceSep1") == -1) {
					previousPrice +=	parseFloat($(this).val());
				}
			});
			var totalPercent=	previousPrsnt + finPer;
			var totalPrice	=	previousPrice + finPri;
			if(winPrice < totalPrice && parseFloat(totalPercent) > 100) {
				alert('Total price is greater than winning price and total percentage is greater than 100%.');
				return false;
			} else {
				$('.perSep_1').val(finPer.toFixed(2));
				$('.priceSep1').val(finPri.toFixed(2));
				$('.tPer').text(totalPercent.toFixed(2));
				$('.tPri').text(totalPrice.toFixed(2));
			}
		});
		
        var arr = [];
        $('.saveData').click(function () {
			var empty_count=0; 
			var percentVal=0; 
			var cunt=1; 
			
			$(".option_input").each(function(key, value){
				if($(this).val()=='' || $(this).val()==" "){
					empty_count++;
				}
			});
			
			$(".type_option").each(function(key, value){
				if($(".perSep_"+cunt).val() !==''){
					percentVal += parseFloat($(".perSep_"+cunt).val());
				}
				cunt++;
				strt = $('input[name="start[]"]').val();
				end = $('input[name="end[]"]').val();
				
				/*if(strt > end){ 
					alert('End not be less to Start.');
					return false;
				}*/
			}); 
			if(percentVal < 100){
				alert('Total percentage must be equal to 100');
				return false;
			}
			if(empty_count>0){
				alert('Field can not empty');
				return false;
			}
			$(this).attr('disabled',true);
			$('#addForm').submit();
		});

        //var newRow5 = '<tr class="row6"><td>Rank </td><td>percent </td><td>price</td></tr>';
        var newRow5 = '<tr class="row6"><td><input class="form-control" name="name[]" value="Rank" readonly=""></td><td><input class="form-control" name="percentage[]" value="percent_val" readonly=""></td><td><input class="form-control" name="price[]" value="price_val" readonly=""></td></tr>';

        var wrapper1 = $('.appendRows');
        /* $('.winners').change(function () {
          $('.row7').remove();
          $('.row8').remove();
          $('.row9').remove();
          winners      = $(this).val();
          winPrice     = $('.winPrice').val();
          contestSize  = $('.contestSize').val();
          remaining    = (parseInt(winners)-parseInt(5));
          perCandidate = Math.round(remaining/3);

          if(contestSize=='10'){
            if(winners=='5'){
              $('.percent1').val('40%'); $('.percent2').val('25%');
              $('.percent3').val('15%');  $('.percent4').val('12.5%'); $('.percent5').val('7.5%');
              $('.price1').val(winPrice*40/100);  $('.price2').val(winPrice*25/100);
              $('.price3').val(winPrice*15/100); $('.price4').val(winPrice*12.5/100);
              $('.price5').val(winPrice*7.5/100); $('.rank5,.r4,.r3,.r2,.r1').show();
            }
            if(winners=='4'){
              $('.percent1').val('40%'); $('.percent2').val('30%'); $('.percent3').val('20%');
              $('.percent4').val('10%'); $('.price1').val(winPrice*40/100);
              $('.price2').val(winPrice*30/100); $('.price3').val(winPrice*20/100);
              $('.price4').val(winPrice*10/100); $('.rank5').hide(); $('.r4,.r3,.r2,.r1').show();
            }
            if(winners=='3'){
              $('.percent1').val('50%'); $('.percent2').val('30%');
              $('.percent3').val('20%'); $('.price1').val(winPrice*50/100);
              $('.price2').val(winPrice*30/100); $('.price3').val(winPrice*20/100);
              $('.r3,.r2,.r1').show(); $('.rank5,.r4').hide();
            }
            if(winners=='2'){
              $('.percent1').val('70%'); $('.percent2').val('30%');
              $('.price1').val(winPrice*70/100); $('.price2').val(winPrice*30/100);
              $('.r2,.r1').show(); $('.rank5,.r4,.r3').hide();
            }
            if(winners=='1'){
              $('.percent1').val('100%'); $('.price1').val(winPrice*100/100);
              $('.r1').show();
			  $('.rank5,.r4,.r3,.r2').hide();
            }
          }

          if(contestSize>'10'){
            rowCount = Math.round((winners/100)*contestSize);
            reminingWinners = (rowCount-5);
            perRow = Math.round(reminingWinners/3);
            
            if(winners=='50'){
              remainingPercntForElseUser = (25-5); 
              perRowPercent = (remainingPercntForElseUser/3).toFixed(2);
              percentToAdd  = (perRowPercent/2).toFixed(2); 
              percent_1     = (parseInt(perRowPercent) + parseInt(percentToAdd));
              percent_2     = perRowPercent;
              percent_3     = (parseInt(perRowPercent) - parseInt(percentToAdd));
              rnkFirst      = (parseInt(5)+parseInt(perRow));
              firstGap      = (parseInt(rnkFirst)-parseInt(5));
              firstRowPersent = (percent_1/firstGap).toFixed(2);
              price1        = (winPrice*(firstRowPersent/100)).toFixed(1);

              var data6 = newRow5.replace("Rank", "Rank 6 - "+rnkFirst); var data6 = data6.replace("percent_val", firstRowPersent+"%");
              var data6 = data6.replace("price_val", price1); var data6 = data6.replace("row6", "row7");
              $(wrapper1).append(data6);

              rnkScndStart = (parseInt(rnkFirst)+parseInt(1));
              rnkScnfEnd   = (parseInt(rnkFirst)+parseInt(perRow));
              secondGap    = (parseInt(rnkScnfEnd)-parseInt(rnkFirst));
              secondRowPersent = (percent_2/secondGap).toFixed(2);
              price2       = (winPrice*(secondRowPersent/100)).toFixed(1);

              var data7 = newRow5.replace("Rank", "Rank "+rnkScndStart+" - "+rnkScnfEnd); var data7 = data7.replace("percent_val", secondRowPersent+"%");
              var data7 = data7.replace("price_val", price2); var data7 = data7.replace("row6", "row8");
              $(wrapper1).append(data7);

              rnkThirdStart = (parseInt(rnkScnfEnd)+parseInt(1));
              rnkThirdEnd   = (parseInt(rnkScnfEnd)+parseInt(perRow));
              thirdGap      = (parseInt(rowCount)-parseInt(rnkScnfEnd));
              thirdRowPersent = (percent_3/thirdGap).toFixed(2);
              price3        = (winPrice*(thirdRowPersent/100)).toFixed(1);
              var data8 = newRow5.replace("Rank", "Rank "+rnkThirdStart+" - "+rowCount); var data8 = data8.replace("percent_val", thirdRowPersent+"%");
              var data8 = data8.replace("price_val", price3); var data8 = data8.replace("row6", "row9");
              $(wrapper1).append(data8);
            }
            if(winners=='75'){
              remainingPercntForElseUser = (25-5); 
              perRowPercent = (remainingPercntForElseUser/3).toFixed(2);
              percentToAdd  = (perRowPercent/2).toFixed(2); 
              percent_1     = (parseInt(perRowPercent) + parseInt(percentToAdd));
              percent_2     = perRowPercent;
              percent_3     = (parseInt(perRowPercent) - parseInt(percentToAdd));
              rnkFirst      = (parseInt(5)+parseInt(perRow));
              firstGap      = (parseInt(rnkFirst)-parseInt(5));
              firstRowPersent = (percent_1/firstGap).toFixed(2);
              price1        = (winPrice*(firstRowPersent/100)).toFixed(1);
              

              var data6 = newRow5.replace("Rank", "Rank 6 - "+rnkFirst); var data6 = data6.replace("percent_val", firstRowPersent+"%");
              var data6 = data6.replace("price_val", price1); var data6 = data6.replace("row6", "row7");
              $(wrapper1).append(data6);

              rnkScndStart  = (parseInt(rnkFirst)+parseInt(1));
              rnkScnfEnd    = (parseInt(rnkFirst)+parseInt(perRow));
              secondGap     = (parseInt(rnkScnfEnd)-parseInt(rnkFirst));
              secondRowPersent = (percent_2/secondGap).toFixed(2);
              price2        = (winPrice*(secondRowPersent/100)).toFixed(1);

              var data7 = newRow5.replace("Rank", "Rank "+rnkScndStart+" - "+rnkScnfEnd); var data7 = data7.replace("percent_val", secondRowPersent+"%");
              var data7 = data7.replace("price_val", price2); var data7 = data7.replace("row6", "row8");
              $(wrapper1).append(data7);

              rnkThirdStart = (parseInt(rnkScnfEnd)+parseInt(1));
              rnkThirdEnd   = (parseInt(rnkScnfEnd)+parseInt(perRow));
              thirdGap      = (parseInt(rowCount)-parseInt(rnkScnfEnd));
              thirdRowPersent = (percent_3/thirdGap).toFixed(2);
              price3        = (winPrice*(thirdRowPersent/100)).toFixed(1);

              var data8 = newRow5.replace("Rank", "Rank "+rnkThirdStart+" - "+rowCount); var data8 = data8.replace("percent_val", thirdRowPersent+"%");
              var data8 = data8.replace("price_val", price3); var data8 = data8.replace("row6", "row9");
              $(wrapper1).append(data8);
            }
          }

        });
 */
        $('.btnSave').click(function () {
			winners=$('.winners').val();
			$(".option_input").each(function(key, value){
				if($(this).val()=='' || $(this).val()==" " || $(this).val()=="0" || $(this).val()=="NaN"){
					countRow++;
				}
			});
			if(countRow>0) {
				alert('Please fill this row first.');
				return false;
			}
			if(winners==''){
				alert('Please select total no of winners');
				return false;
			}
		});
	});
</script>
<script src="<?= SITE_URL; ?>webroot/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

