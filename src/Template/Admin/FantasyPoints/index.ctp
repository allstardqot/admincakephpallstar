<?php use Cake\Core\Configure; 
	// echo SITE_URL;die;
?>

<div class="content-wrapper"> 
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1> Manage Points</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
						<li class="breadcrumb-item active"> Manage Points</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content sub-admins_outer">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
					  <!-- <h3 class="card-title"> Manage Points</h3> -->
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table  class="table  table-bordered responsive" >
								<thead>
									<tr>
										<!-- <th><input type="checkbox" class="selectall" /></th> -->
										<th>#</th>
										<th><?php echo $this->Paginator->sort('points.name', __(' Name')) ?></th>
										<th><?php echo $this->Paginator->sort('points.point', __('Point')) ?></th>
										<th><?php echo __('Action');?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(!empty($points->count()>0)) {
										$i	=	0;
										$start = 1;
										$pl=Configure::read('ADMIN_PAGE_LIMIT');
										foreach($points as $key => $value) {
											$i++; ?>
											<tr>
												
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
												<td><?php echo h($value->name); ?></td>
												<td><?php echo h($value->point); ?></td>
												
												<td class="center">												
													<?php 
													//echo $this->Html->link('Edit', ['controller'=>'subAdmins','action'=>'edit',$value->id],['escape'=>false,'id'=>'editButton','class'=>'btn btn-success',]); ?>
													<button class = 'btn btn-success pointedit' id='pointedit' value=<?= $value->id;?> >Edit</button>
													<!-- <?php // echo $this->Html->link('Delete User', ['controller'=>'subAdmins','action'=>'delete',$value->id],['escape'=>false,'class'=>'btn btn-danger btn-xs','title'=>'Delete Subadmin','onclick'=>"return confirm('Are you sure you want to delete sub admin user?')"]); ?> -->
												</td>
											</tr>
											<?php $start++;
										}
									} else { ?>
										<tr>
											<td colspan="9" class="text-center"><?php echo __('No Record Found') ?></td>
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


<div class="modal fade" id="myPoint_edit" role="dialog">

</div>








