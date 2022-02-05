<?php
	$contestType	=	array('Free'=>'Free Entry','Paid'=>'Paid');
?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Add Contest</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Add Contest</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content admins_add">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Fill Form</h3>
						</div>
						<?php echo $this->Form->create($contest, ['type' => 'file', 'novalidate' => true,'id'=>'contestForm']); ?>
						<div class="card-body">
							<div class="row">

								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<label for="category_id">Category name <span class="required">*</span></label>
										<?php echo $this->Form->input('category_id',['options'=>$list,'type'=>'select','class'=>'form-control category','empty'=>'Select Category','id'=>'category_id','label'=>false]); ?>
									</div>
								</div>

								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('contest_amount', ['escape'=>false,'class' => 'form-control contest_amount', 'placeholder' => __('Contest Points'), 'label' => __('Contest Total Points<span class="required">*</span>'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');",'maxlength'=>'9']);
										?>
									</div>
								</div>

								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('admin_comission', ['type'=>'text','escape'=>false,'class' => 'form-control adminC', 'placeholder' => __('Admin Commission '), 'label' => __('Admin Commission (%)'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');",'maxlength'=>'5']);
										?>
									</div>
								</div>

								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('contest_size', ['escape'=>false,'class' => 'form-control contest_size','label'=>'Contest size (Team size) <span class="required">*</span>', 'placeholder' => __('Contest size'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');",'maxlength'=>'10']);
										?>
									</div>
								</div>

								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('contest_type', ['escape'=>false,'class' => 'form-control cTyp','options' => $contestType,'empty'=>'Select contest type', 'label' => __('Contest Type <span class="required">*</span>')]);
										?>
									</div>
								</div>

								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('winning_amount', ['escape'=>false,'class' => 'form-control winning_amount', 'placeholder' => __('Winning amount'), 'label' => __('Contest Winning Points <span class="required">*</span>'),'readonly'=>'readonly','oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');",'maxlength'=>'9']);
										?>
									</div>
								</div>

								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('entry_fee', ['escape'=>false,'class' => 'form-control', 'placeholder' => __('Entry Points'), 'label' => __('Entry Points <span class="required">*</span>'),'readonly'=>'readonly']);
										?>
									</div>
								</div>

							</div>
						</div>
						<div class="card-footer">
							<button type="submit" id="saveButton" class="btn btn-primary submit">Submit</button>
						</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<style>
	.checkbox label {
		font-weight: normal !important;
	}
</style>
<script type="text/javascript">
	$(document).on('click', '.submit', function() {
		$(this).attr('disabled',true);
		$('#contestForm').submit();
	});
	$(document).ready(function () {
		$('.cTyp').on('change', function() {
			var catText		=	$('.category option:selected').text();
			var contestType	=	$('.cTyp option:selected').val();
			if(contestType == 'Paid' && (catText.indexOf('Practice') !== -1 || catText.indexOf('practice') !== -1)){
				alert('Practice contest could not be paid.');
				$('#contest-type').val('Free');
				$('#entry-fee').val('0');
			}
		});

		$('#multiple-team').click(function(){
           if($(this).is(":checked")){
               $( "#max-team-user" ).prop( "readonly", false );
           }
           else if($(this).is(":not(:checked)")){
           $( "#max-team-user" ).val(1);
               $( "#max-team-user" ).prop( "readonly", true );
           }
        });
		
		$('.winning_amount').keyup(function() {
			var maxWining	=	'99999999';
			var winAmount	=	$(this).val();
			if(parseFloat(winAmount) > parseFloat(maxWining)) {
				alert('You can not add winning points more than 9,99,99,999.');
				$(this).val(winAmount.substr(0, winAmount.length-1));
			}
		});

		/* $("#contest-amount").keyup(function(){
			$("#contest-type").change();
		});

		$("#admin-comission").keyup(function(){
			$("#contest-type").change();
		});

		$("#contest-size").keyup(function(){
			$("#contest-type").change();
		}); */

		$("#contest-type").change(function(){

			cTyp	=	$(this).find(':selected').val();
			adminC	=	$('.adminC').val();

			if(cTyp=='Free'){
				$('.adminC').hide();
				$('.adminC').val("0");
				contest_amount	=	$("#contest-amount").val();
				contestSize		=	$("#contest-size").val();
				adminC			=	$('.adminC').val();

				if(adminC!='' && contest_amount!='' && contestSize!='') {
					commision		=	Math.ceil(contest_amount*(adminC/100));
					winningamount	=	parseInt(contest_amount) - parseInt(commision);
					entryFee		=	Math.ceil(contest_amount/contestSize);
				} else {
					alert('Please fill first contest amount, contest size and admin comission.')
				}
				
				$( "#entry-fee" ).prop( "readonly", true );
				$( "#entry-fee" ).val( 0 );

				$( "#winning-amount" ).prop( "readonly", true );
				$( "#winning-amount" ).val( winningamount );

				$("#saveButton").text('Next');

			} else if (cTyp=='Paid') {
				$('.adminC').show();

				contest_amount	=	$("#contest-amount").val();
				contestSize		=	$("#contest-size").val();
				adminC			=	$('.adminC').val();

				if(adminC!='' && contest_amount!='' && contestSize!='') {
					commision		=	Math.ceil(contest_amount*(adminC/100));
					winningamount	=	parseInt(contest_amount) - parseInt(commision);
					entryFee		=	Math.ceil(contest_amount/contestSize);
				} else {
					alert('Please fill first contest amount, contest size and admin comission.')
				}
				
				$( "#entry-fee" ).prop( "readonly", true );
				$( "#entry-fee" ).val( entryFee );

				$( "#winning-amount" ).prop( "readonly", true );
				$( "#winning-amount" ).val( winningamount );

				$("#saveButton").text('Next');
			} else {

				$( "#entry-fee" ).prop( "readonly", true );
				$( "#entry-fee" ).val( 0 );

				$( "#winning-amount" ).prop( "readonly", true );
				$( "#winning-amount" ).val( 0 );

			}
		});
		
		// $('#contestForm')
		$('#saveButton').click(function(e) {
			var entryFee	=	$('#entry-fee').val();
			e.preventDefault();
			if(parseFloat(entryFee) > parseFloat('9999')) {
				alert('Entry can not be greater than 9,999 points.');
				return false;
			} else {
				$('#contestForm').submit();
			}
		});

	});
</script>