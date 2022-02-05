<?php use Cake\Core\Configure; ?>
<style type="text/css">
	.exportBtn {float: right;}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>List Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
                        <li class="breadcrumb-item active">List Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content admin_users">
        <div class="r o w">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                    <?php  echo $this->Form->create(null,['novalidate' => true, 'valueSources' => 'query', 'type' => 'get', 'class' => 'form-inline search_form']); ?>
						<div class="row">
							<div class="form-group col-sm-6 col-md-3">
								<?php 
									echo $this->Form->input('full_name',['class' => 'form-control', 'label' => false, 'placeholder' => __('Enter name')]);
								?>
							</div>
							<div class="form-group col-sm-6 col-md-3">
								<?php
									echo $this->Form->input('email',['class' => 'form-control', 'label' => false, 'placeholder' => __('Email address')]);
								?>
							</div>
							<div class="form-group col-sm-6 col-md-3">
								<?php 
									echo $this->Form->input('phone',['class' => 'form-control', 'label' => false, 'placeholder' => __('Phone number')]);
								?>
							</div>
							
						<!-- </div>
						<div class="row"> -->
							
							<div class="form-group col-sm-6 col-md-3">
								<div class="input text">
									<!-- <?php $checked	=	(!empty($unverified) && $unverified== 'checked') ? 'checked="checked"' : ''; ?>
									<input type="checkbox" name="unverified" value="checked" <?php echo $checked; ?>> Show unverified users -->
								</div>
							</div>
							<div class="form-group col-sm-6 col-md-3 ">
								<?php
									echo $this->Form->button(__('Search'),['type' => 'submit', 'class' => 'btn btn-default site_btn_color']);
									
									echo $this->Html->link('<i class="fa fa-undo"></i>'.__(' Reset'), array('controller' => 'users', 'action' => 'userdetail'), array('class' => 'btn btn-default', 'escape' => false));
								?>
							</div>
						</div>
						<?php echo $this->Form->end(); ?>
                    </div>
                    <div class="card-header">
                      <h3 class="card-title" style="display: inline-block;">User Details</h3>
                      <?php
						//echo $this->Html->link(__(' Export'), array('controller' => 'users', 'action' => 'export'), array('class' => 'btn btn-primary exportBtn', 'escape' => false));
                      ?>
                    </div>
                    <div class="card-body">
						<div class="table-responsive">
							<table  class="table  table-bordered responsive" >
								<thead>
									<tr>
										<!-- <th><input type="checkbox" class="selectall" /></th> -->
										<th>Rank</th>
										<th><?php echo __('Image');?></th>
										<th><?php echo $this->Paginator->sort('Users.first_name', __('Name')) ?></th>
										<th class="userEmailField"><?php echo $this->Paginator->sort('Users.email', __('Email')) ?></th>
										<th><?php echo $this->Paginator->sort('Users.phone', __('Phone number')) ?></th>
										<!-- <th><?php echo $this->Paginator->sort('Users.winning_wallet', __('Winning Coins')) ?></th> -->
										<th><?php echo __('Winning Coins'); ?></th>
										
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($users->count() > 0)) {
										
										$old_coin=$i	=	0;
										$start = 0;
										$pl=Configure::read('ADMIN_PAGE_LIMIT');
										foreach ($users as $key => $value) {
											if($old_coin==$value->winning_wallet){
												$rank= $start;
											}else{
												$start++;
												$rank= $start;
											}
											$old_coin=$value->winning_wallet;
											$i++; ?>
											<tr>
												<!-- <td><?php echo $this->Form->checkbox('published', ['hiddenField' => false,'value' => $value->id, 'class'=>'individual']); ?></td> -->
												<td>
												<?php 
													if(isset($this->request->params['?']['page'])){
														$pc = $this->request->params['?']['page']-1;
														
													} else {
														$pc = 0;
													}
													echo ($pl*$pc)+$rank;
												?>
												</td>
												<td>
												<?php
												if(!empty($value->profile_image)) {
													echo '<img src="'.U_IMAGE_PATH.$value->profile_image.'" hight="100px" width="40px" alt="">';
												} else {
													echo 'No Image';
												} ?>
											</td>
												<td><?php echo h($value->full_name); ?></td>
												<td class="userEmailField"><?php echo h($value->email); ?></td>
												<td><?php echo h($value->phone); ?></td>
												<td><?php echo h($value->winning_wallet); ?></td>
												
											</tr>
										<?php
										
										
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
<div class="modal fade" id="myModal" role="dialog"></div>

<?= $this->Html->css(['admin/bootstrap-datepicker.min']) ?>
<?= $this->Html->script(['admin/bootstrap-datepicker.min']) ?>
<?= $this->Html->script(['admin/jquery-3.2.1']) ?>
</div>
<script>
    $(document).ready(function () {
        var selected	=	[];
        // select all
        $(".selectall").click(function () {
            $(".individual").prop("checked", $(this).prop("checked"));
            selected	=	[];
            $('.individual:checkbox:checked').each(function () {
                selected.push($(this).val());
            });
            $('#selected-id').val(selected);
        });
		
        // select individual
        $('.individual').click(function () {
            selected	=	[];
            $('.individual:checkbox:checked').each(function () {
				selected.push($(this).val());
			});
			$('#selected-id').val(selected);
		});



		$('.pan_detail').click(function() {
			var userId	=	$(this).attr('id');
			var page	=	$(this).attr('page');
			$.ajax({
				url :	"<?php echo $this->Url->build(['controller'=>'users','action'=>'panDetail']); ?>",
				type:	'POST',
				data:	{uset_id:userId,type:'pan_card',page:page},
				success : function(data) {
					$('#myModal').html(data);
					$('#myModal').modal('show');
				} 
			});
		});

		$('.add_pan_detail').click(function() {
			var userId	=	$(this).attr('id');
			var page	=	$(this).attr('page');
			$.ajax({
				url :	"<?php echo $this->Url->build(['controller'=>'users','action'=>'addPanDetail']); ?>",
				type:	'POST',
				data:	{uset_id:userId,type:'pan_card',page:page,action:'addPanDetail'},
				success : function(data) {
					$('#myModal').html(data);
					$('#myModal').modal('show');
				} 
			});
		});

		$(document).on('click', '.edit_pan', function() {
			var userId	=	$(this).attr('data-id');
			$.ajax({
				url :	'<?php echo $this->Url->build(['controller'=>'users','action'=>'addPanDetail'])?>',
				type:	'POST',
				data:	{user_id: userId,action:'editPanDetail'},
				success : function(data) {
					//location.reload();
					$('#myModal').html(data);
					$('#myModal').modal('show');
				}
			});
		});

		$(document).on('click', '.verify_pan', function() {
			var userId	=	$(this).attr('data-id');
			$.ajax({
				url :	'<?php echo $this->Url->build(['controller'=>'users','action'=>'verifyPan'])?>',
				type:	'POST',
				data:	{user_id: userId},
				success : function(data) {
					location.reload();
				}
			});
		});
		
		$(document).on('click', '.cancel_pan', function() {
			var userId	=	$(this).attr('data-id');
			$.ajax({
				url :	'<?php echo $this->Url->build(['controller'=>'users','action'=>'cancelPan'])?>',
				type:	'POST',
				data:	{user_id: userId},
				success : function(data) {
					location.reload();
				}
			});
		});


		$('.bank_detail').click(function() {
			var userId	=	$(this).attr('id');
			var page	=	$(this).attr('page');
			$.ajax({
				url :	"<?php echo $this->Url->build(['controller'=>'users','action'=>'panDetail']); ?>",
				type:	'POST',
				data:	{uset_id:userId,type:'bank_detail',page:page},
				success : function(data) {
					$('#myModal').html(data);
					$('#myModal').modal('show');
				} 
			});
		});

		$('.add_bank_detail').click(function() {
			var userId	=	$(this).attr('id');
			var page	=	$(this).attr('page');
			$.ajax({
				url :	"<?php echo $this->Url->build(['controller'=>'users','action'=>'addBankDetail']); ?>",
				type:	'POST',
				data:	{uset_id:userId,type:'bank_detail',page:page,action:'addBankDetail'},
				success : function(data) {
					$('#myModal').html(data);
					$('#myModal').modal('show');
				} 
			});
		});

		$(document).on('click', '.edit_bank', function() {
			var userId	=	$(this).attr('data-id');
			$.ajax({
				url :	'<?php echo $this->Url->build(['controller'=>'users','action'=>'addBankDetail'])?>',
				type:	'POST',
				data:	{user_id: userId,action:'editBankDetail'},
				success : function(data) {
					//location.reload();
					$('#myModal').html(data);
					$('#myModal').modal('show');
				}
			});
		});
		
		$(document).on('click', '.verify_bank', function() {
			var userId	=	$(this).attr('data-id');
			$.ajax({
				url :	'<?php echo $this->Url->build(['controller'=>'users','action'=>'verifyBank'])?>',
				type:	'POST',
				data:	{user_id: userId},
				success : function(data) {
					location.reload();
				}
			});
		});
		
		$(document).on('click', '.cancel_bank', function() {
			var userId	=	$(this).attr('data-id');
			$.ajax({
				url :	'<?php echo $this->Url->build(['controller'=>'users','action'=>'cancelBank'])?>',
				type:	'POST',
				data:	{user_id: userId},
				success : function(data) {
					location.reload();
				}
			});
		});


		
		$('.aadhar_card').click(function() {
			var userId	=	$(this).attr('id');
			var page	=	$(this).attr('page');
			$.ajax({
				url :	"<?php echo $this->Url->build(['controller'=>'users','action'=>'panDetail']); ?>",
				type:	'POST',
				data:	{uset_id:userId,type:'aadhar_detail',page:page},
				success : function(data) {
					$('#myModal').html(data);
					$('#myModal').modal('show');
				} 
			});
		});

		$('.add_aadhar_card').click(function() {
			var userId	=	$(this).attr('id');
			var page	=	$(this).attr('page');
			$.ajax({
				url :	"<?php echo $this->Url->build(['controller'=>'users','action'=>'addAadharDetail']); ?>",
				type:	'POST',
				data:	{uset_id:userId,type:'aadhar_detail',page:page,action:'addAadharDetail'},
				success : function(data) {
					$('#myModal').html(data);
					$('#myModal').modal('show');
				} 
			});
		});

		$(document).on('click', '.edit_aadhar', function() {
			var userId	=	$(this).attr('data-id');
			$.ajax({
				url :	'<?php echo $this->Url->build(['controller'=>'users','action'=>'addAadharDetail'])?>',
				type:	'POST',
				data:	{user_id: userId,action:'editAadharDetail'},
				success : function(data) {
					//location.reload();
					$('#myModal').html(data);
					$('#myModal').modal('show');
				}
			});
		});
		
		$(document).on('click', '.verify_aadhar', function() {
			var userId	=	$(this).attr('data-id');
			$.ajax({
				url :	'<?php echo $this->Url->build(['controller'=>'users','action'=>'verifyAadhar'])?>',
				type:	'POST',
				data:	{user_id: userId},
				success : function(data) {
					location.reload();
				}
			});
		});
		
		$(document).on('click', '.cancel_aadhar', function() {
			var userId	=	$(this).attr('data-id');
			$.ajax({
				url :	'<?php echo $this->Url->build(['controller'=>'users','action'=>'cancelAadhar'])?>',
				type:	'POST',
				data:	{user_id: userId},
				success : function(data) {
					location.reload();
				}
			});
		});


	});
	
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
	
	
</script>




