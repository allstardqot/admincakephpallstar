<?php
	echo $this->Html->css(['admin/bootstrap-datepicker.min']);
	echo $this->Html->script(['admin/bootstrap-datepicker.min']);
	echo $this->Html->script(['admin/jquery-3.2.1']);
	use Cake\Core\Configure;
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Contest List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
                        <li class="breadcrumb-item active">Contest List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content contest_outer">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
						<?php echo $this->Form->create(null,['novalidate' => true, 'valueSources' => 'query', 'type' => 'get', 'class' => 'form-inline search_form']); ?>
						<div class="row">

							<div class="form-group col-sm-6 col-md-3 ">
								<?php 
									echo $this->Form->select('category_id',$categoryList,['class' => 'form-control', 'label' => false, 'empty' => __('Select Category Name')]);
								?>
							</div>
							<div class="form-group col-sm-6 col-md-3 ">
								<?php
									echo $this->Form->button(__('Search'),['type' => 'submit', 'class' => 'btn btn-default site_btn_color']);
								
									echo $this->Html->link('<i class="fa fa-undo"></i>'.__(' Reset'), array('controller' => 'contest', 'action' => 'index'), array('class' => 'btn btn-default', 'escape' => false)); 
								?>
							</div>
						</div>
                        <?php  echo $this->Form->end(); ?>
                    </div>
                    <div class="card-header">
                      <h3 class="card-title">Contest details</h3>
                    </div>
                    <div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered responsive" >
								<thead>
									<tr>
										<!-- <th><input type="checkbox" class="selectall" /></th> -->
										<th>#</th>
										<th><?php echo __('Category name');?></th>
										<th><?php echo $this->Paginator->sort('Contest.winning_amount', __('Winning Points')) ?></th>
										<th><?php echo $this->Paginator->sort('Contest.contest_size', __('Contest size')) ?></th>
										<th><?php echo $this->Paginator->sort('Contest.contest_type', __('Contest type')) ?></th>
										<th><?php echo $this->Paginator->sort('Contest.entry_fee', __('Entry Points')) ?></th>
										<th><?php echo $this->Paginator->sort('Contest.created', __('Added date')) ?></th>
										<th><?php echo __('Status');?></th>
										<th><?php echo __('Action');?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($contest->count()>0)) {
										$i		=	0;
										$start	=	1;
										$pl		=	Configure::read('ADMIN_PAGE_LIMIT');
										foreach($contest as $key => $value) {
											$i++;
											$isJoinedContest = $this->Custom->isContestJoined($value->id);
											?>
											<tr>
												<!-- <td><?php echo $this->Form->checkbox('published', ['hiddenField' => false,'value' => $value->id, 'class'=>'individual']); ?></td> -->
												<td>
												<?php 
												if(isset($this->request->params['?']['page'])){
		                                            $pc = $this->request->params['?']['page']-1;
		                                        }else{
		                                            $pc = 0;
		                                        }
		                                        echo ($pl*$pc)+$start;
												?>
												</td>
												<td><?php echo $this->Custom->getCategoryName($value->category_id); ?></td>
												<td><?php echo h($value->winning_amount); ?></td>
												<td><?php echo h($value->contest_size); ?></td>
												<td>
													<?php echo h($value->contest_type); ?><br/>
												</td>
												<td><?php echo h($value->entry_fee); ?></td>
												<td><?php echo h(date("Y-m-d", strtotime($value->created))); ?></td>
												<td class="center">  
													<?php echo $this->Html->link(($value->status == 1) ? '<span class="label-success label label-default">Active</span>' : '<span class="label-default label label-danger">Inactive</span>', ['prefix' => 'admin', 'controller' => 'Contest', 'action' => 'status', $value->id], ['escape' => false,'class'=>'btn']);  ?>
												</td>
												<td class="center"> 
													<!-- <?php //echo $this->Html->link('Edit', ['controller'=>'Contest','action'=>'edit',$value->id],['escape'=>false,'class'=>'btn btn-success',]); ?> -->

													<?php 
													 	echo $this->Html->link('View', ['controller'=>'Contest','action'=>'detail',$value->id],['escape'=>false,'class'=>'btn btn-primary',]).'&nbsp;';

														if(empty($isJoinedContest)){
															echo $this->Html->link('Delete Contest', ['controller'=>'Contest','action'=>'delete',$value->id],['escape'=>false,'class'=>'btn btn-danger btn-xs','title'=>'Delete User','onclick'=>"return confirm('Are you sure you want to delete this contest?')"]).'&nbsp;';
														} 

														echo $this->Html->link('Archive Contest', ['controller'=>'Contest','action'=>'archive',$value->id],['escape'=>false,'class'=>'btn btn-danger btn-xs','title'=>'Archive Contest','onclick'=>"return confirm('Are you sure you want to archive this contest?')"]).'&nbsp;';
													
														if(($value->contest_type=='Paid') && ($value->price_breakup=='0')) {
															echo $this->Html->link('Price Break', array('controller' => 'contest', 'action' => 'price',$value->id), array('class' => 'btn btn-default', 'escape' => false));
														}
													?>

												</td>
											</tr>
										<?php $start++;
										}
									}else { ?>
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

<script type="text/javascript">
    $(document).ready(function () {
        var selected = [];
        // select all
        $(".selectall").click(function () {
            $(".individual").prop("checked", $(this).prop("checked"));
            selected = [];
            $('.individual:checkbox:checked').each(function () {
                selected.push($(this).val());
            });
            $('#selected-id').val(selected);
        });
		
        // select individual
        $('.individual').click(function () {
            selected = [];
            $('.individual:checkbox:checked').each(function () {
                selected.push($(this).val());
            });
            $('#selected-id').val(selected);
        });

    });

    $(function () {
		/* $('.datepicker-input').datepicker({
            startDate: false,
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: false
        }); */
		
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
<style>
	.site_btn_color, a.btn.btn-default {
	   top: 0;
	}
</style>




