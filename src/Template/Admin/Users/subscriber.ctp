<?php use Cake\Core\Configure; ?>
<style type="text/css">
	.exportBtn {float: right;}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Subscribed Users</h1>
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
						<div class="table-responsive">
							<table  class="table  table-bordered responsive" >
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo $this->Paginator->sort('SubscribeUsers.first_name', __('Email')) ?></th>
										<th><?php echo $this->Paginator->sort('SubscribeUsers.created', __('Registration date')) ?></th>										
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($users->count() > 0)) {
										$i	=	0;
										$start = 1;
										$pl=Configure::read('ADMIN_PAGE_LIMIT');
										foreach ($users as $key => $value) {
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
														echo ($pl*$pc)+$start;
													?>
												</td>
												<td><?php echo h($value->email); ?></td>
												<td><?php echo h(date("Y-m-d", strtotime($value->created))); ?></td>
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
		
		$('.aadhar').click(function() {
			var userId	=	$(this).attr('id');
			var page	=	$(this).attr('page');
			$.ajax({
				url :	"<?php echo $this->Url->build(['controller'=>'users','action'=>'panDetail']); ?>",
				type:	'POST',
				data:	{uset_id:userId,type:'aadhar',page:page},
				success : function(data) {
					$('#myModal').html(data);
					$('#myModal').modal('show');
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
</script>




