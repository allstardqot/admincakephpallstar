<?php use Cake\Core\Configure; ?>
<style type="text/css">
#walletModal .modal-body p {
    font-weight: 600;
}
#walletModal .modal-body p span {
    font-weight: normal;
}
#walletModal .modal-body a.update_balance {
    background: #007aff;
    padding: 5px 20px;
    border-radius: 2px;
    color: #fff;
    text-decoration: none;
}
.balance-btn{
	text-align: center;
}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Wallet Manager</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
                        <li class="breadcrumb-item active">Wallet Manager</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content wallets-outer">
        <div class="r o w">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                    	<?php  echo $this->Form->create(null,['novalidate' => true, 'valueSources' => 'query', 'type' => 'get', 'class' => 'form-inline search_form']); ?>
						<div class="row">
							<div class="form-group col-sm-6 col-md-3 ">
								<?php 
									echo $this->Form->input('phone',['class' => 'form-control', 'label' => false, 'placeholder' => __('Mobile Number')]);
								?>
							</div>
							<div class="form-group col-sm-6 col-md-3 ">
								<?php
									echo $this->Form->input('email',['class' => 'form-control', 'label' => false, 'placeholder' => __('Email address')]);
								?>
							</div>
							<div class="form-group col-sm-6 col-md-3 ">
								<?php           
									echo $this->Form->input('start_date',["type" => "text",'readonly' => 'readonly','class' => 'form-control datepicker-input start_date', 'label' => false, 'placeholder' => __('Enter Date From'),'required'=>false]);
								?>
							</div>
							<div class="form-group col-sm-6 col-md-3 ">
								<?php 
									echo $this->Form->input('end_date',["type" => "text",'readonly' => 'readonly','class' => 'form-control datepicker-input end_date', 'label' => false, 'placeholder' => __('To'),'required'=>false]);
								?>
							</div>
						<!-- </div>
						<div class="row"> -->
							<div class="form-group col-sm-6 col-md-3 ">
								<?php
									echo $this->Form->button(__('Search'),['type' => 'submit', 'class' => 'btn btn-default site_btn_color']);
									
									echo $this->Html->link('<i class="fa fa-undo"></i>'.__(' Reset'), array('controller' => 'wallets', 'action' => 'index'), array('class' => 'btn btn-default', 'escape' => false));
								?>
							</div>
						</div>
						<?php echo $this->Form->end(); ?>
                    </div>
                    <div class="card-header">
                      <h3 class="card-title">User Wallet Details</h3>
                    </div>
                    <div class="card-body">
						<div class="table-responsive">
							<table  class="table  table-bordered responsive" >
								<thead>
									<tr>
										<th colspan="3"><span style="float:right;">Total</span></th>
										<th><?=$this->Custom->totalCash();?> INR</th>
										<th><?=$this->Custom->totalBonus();?> INR</th>
										<th><?=$this->Custom->totalWinning();?> INR</th>
									</tr>
									<tr>
										<th>#</th>
										<th>User Mobile</th>
										<th>User Email</th>
										<th>Deposit Amount</th>
										<th>Bonus Amount</th>
										<th>Winning Amount</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($users->count() > 0)) {
										$start = 1;
										$pl=Configure::read('ADMIN_PAGE_LIMIT');
										foreach ($users as $key => $value) { ?>
											<tr>
												<td><?php 
												if(isset($this->request->params['?']['page'])){
                                                    $pc = $this->request->params['?']['page']-1;
                                                    
                                                }else{
                                                    $pc = 0;
                                                }
                                                echo ($pl*$pc)+$start;

												?></td>
												<td><?php echo $this->Html->link(h($value->phone), ['controller'=>'Users','action'=>'detail',$value->id]); ?></td>
												<td><?php echo h($value->email); ?></td>
												<td><?php echo $value->cash_balance ? h($value->cash_balance) : '0'; ?><span style="float: right;" class="btn btn-success update_wallet" data_id="<?php echo $value->id; ?>" data_phone="<?php echo $value->phone; ?>" data_type="deposit" data_amount="<?php echo $value->cash_balance; ?>">Edit</span></td>
												<td><?php echo $value->bonus_amount ? h($value->bonus_amount) : '0'; ?><span style="float: right;" class="btn btn-success update_wallet" data_id="<?php echo $value->id; ?>" data_phone="<?php echo $value->phone; ?>" data_type="bonus" data_amount="<?php echo $value->bonus_amount; ?>">Edit</span></td>
												<td><?php echo $value->winning_balance ? h($value->winning_balance) : '0'; ?><span style="float: right;" class="btn btn-success update_wallet" data_id="<?php echo $value->id; ?>" data_type="winning" data_phone="<?php echo $value->phone; ?>" data_amount="<?php echo $value->winning_balance; ?>">Edit</span></td>
											</tr>
										<?php $start++;
										}
									} else { ?>
										<tr>
											<td colspan="9" style="text-align: center;"><?php echo __('No Record Found') ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php echo $this->element('Admin/pagination'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="walletModal" class="modal fade" role="dialog">
  	<div class="modal-dialog">
	    <div class="modal-content">
	      	<div class="modal-header">
		        <h4 class="modal-title">Update Wallet</h4>
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
	      	</div>
	      	<div class="modal-body">
	        	<p>User Mobile : <span id="mobile"></span></p>
	        	<p>User Amount : <span id="wallet_amount"></span></p>
	        	<p>Wallet Type : <span id="wallet_type"></span></p>
	        	<div class="">
	        		<div class="form-group">
	        			<?php echo $this->Form->input('amount',['class' => 'form-control', 'label' => false, 'placeholder' => __('Amount'),'oninput'=>"this.value = this.value.replace(/[^0-9.-]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');", 'maxlength'=>"10"]); ?>
	        			<?php echo $this->Form->input('type',['class' => 'form-control', 'label' => false, 'placeholder' => __('Amount'),'type'=>'hidden']); ?>
	        			<?php echo $this->Form->input('user_id',['class' => 'form-control', 'label' => false, 'placeholder' => __('Amount'),'type'=>'hidden']); ?>
	        		</div>
	        		<div class="form-group">
	        			<?php $transactionTypes= Configure::read('Wallet_update_type');
						echo $this->Form->select('up_type',$transactionTypes,['id'=>'up_type','class' => 'form-control', 'label' => false]); ?>
	        		</div>
	        		<div class="form-group balance-btn">
	        			<a href="javascript:void(0);" class="update_balance">Update</a>
	        		</div>
	        	</div>
	      	</div>
	      	<!-- <div class="modal-footer">
	        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      	</div> -->
	    </div>
  	</div>
</div>
<div class="modal fade" id="myModal" role="dialog"></div>

<?= $this->Html->css(['admin/bootstrap-datepicker.min']) ?>
<?= $this->Html->script(['admin/bootstrap-datepicker.min']) ?>
<?= $this->Html->script(['admin/jquery-3.2.1']) ?>
</div>
<script>
	var siteurl = '<?php echo SITE_URL; ?>';
	$( function() {
		$(".start_date").datepicker({
			format	:	"yyyy-mm-dd",
			autoclose: true,
			endDate: '+0d',
		}).on('changeDate', function (selected) {
			$('.end_date').val('');
			var minDate	=	new Date(selected.date.valueOf());
			$('.end_date').datepicker('setStartDate', minDate);
		});
		
		$(".end_date").datepicker( {
			format	:	"yyyy-mm-dd",
			endDate: '+0d',
			autoclose: true,
		}).on('changeDate', function (selected) {
			// $('.start_date').val('');
			var maxDate = new Date(selected.date.valueOf());
			$('.start_date').datepicker('setEndDate', maxDate);
		});
	});

	function LoaderShow()
  	{
      	if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
          	jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="' + siteurl + '/img/ajax-loader.gif"><div></div></div><div class="bg"></div></div>');
      	}
      	jQuery('#resultLoading').css({
          'width':'100%',
          'height':'100%',
          'position':'fixed',
          'z-index':'10000000',
          'top':'0',
          'left':'0',
          'right':'0',
          'bottom':'0',
          'margin':'auto'
      	});
      	jQuery('#resultLoading .bg').css({
          'background':'#000000',
          'opacity':'0.7',
          'width':'100%',
          'height':'100%',
          'position':'absolute',
          'top':'0'
      	});
      	jQuery('#resultLoading>div:first').css({
          'width': '250px',
          'height':'75px',
          'text-align': 'center',
          'position': 'fixed',
          'top':'0',
          'left':'0',
          'right':'0',
          'bottom':'0',
          'margin':'auto',
          'font-size':'16px',
          'z-index':'10',
          'color':'#ffffff'
      	});
      	jQuery('#resultLoading .bg').height('100%');
      	jQuery('#resultLoading').fadeIn(300);
      	jQuery('body').css('cursor', 'wait');
  	}

  	function LoaderHide()
  	{
      	jQuery('#resultLoading .bg').height('100%');
      	jQuery('#resultLoading').fadeOut(300);
      	jQuery('body').css('cursor', 'default');
  	}
	$(document).ready(function(){
		$('.update_wallet').click(function(){
			var amt = $(this).attr('data_amount');
			var phn = $(this).attr('data_phone');
			var user_id = $(this).attr('data_id');
			var dtype = $(this).attr('data_type');
			$('#mobile').html(phn);
			$('#wallet_amount').html(amt);
			$('#wallet_type').html(dtype);
			$('#type').val(dtype);
			$('#user-id').val(user_id);
			$('#walletModal').modal('show');
		});

		$('.update_balance').click(function(){
			var uid = $('#user-id').val();
			var amount = $('#amount').val();
			var type = $('#type').val();
			var up_type = $('#up_type').val();
			if(uid !='' && amount != '' && type != ''){
				LoaderShow();
		        $.ajax({
		          url: siteurl+'/admin/wallets/update_wallet',
		          data : {uid: uid,amount:amount,type:type,up_type:up_type},
		          type : 'POST',
		          dataType : 'json',
		          success: function(result){
		            LoaderHide();
		            console.log(result);
		            if(result.class == 'success'){
		            	alert(result.message);
		              	$('#walletModal').modal('hide');
		              	window.location.href = window.location.href;
		            }
		            else {
		              	alert(result.message);
		              	$('#walletModal').modal('hide');
		            }
		          }
		        });
		    }else{
		    	alert('Something went wrong. Please try again');
		    	return false;
		    }
	    });
	});
</script>




