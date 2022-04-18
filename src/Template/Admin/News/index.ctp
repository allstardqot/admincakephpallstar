
<?php use Cake\Core\Configure; ?>

<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>News Manager</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>admin/">Home</a></li>
						<li class="breadcrumb-item active">News Manager</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example1" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>#</th>
										<th>Title</th>
										<th>Description</th>
										<th>Description 1</th>
										<th class="last_td">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i	=	1;
									$start = 1;
									$pl=Configure::read('ADMIN_PAGE_LIMIT');
									if(!empty($result)) {
										foreach ($result as $key => $value) { ?>
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
												<td><?php echo !empty($value->title) ? $value->title : ''; ?></td>
												<td><?php echo !empty($value->description) ? htmlentities(substr($value->description,0,100)) : ''; ?></td>
                                                <td><?php echo !empty($value->description2) ? htmlentities(substr($value->description2,0,100)) : ''; ?></td>
												<!-- <td>
													<?php
														echo $this->Html->link(($value->status == 1) ? '<span class="btn btn-success">Active</span>' : '<span class="btn btn-danger">Inactive</span>', ['prefix' => 'admin', 'controller' => 'Contents', 'action' => 'status', $value->id], ['escape' => false]);
													?>
												</td> -->
												<td class="last_td">
													<?php echo $this->Html->link('Edit', ['controller'=>'faq','action'=>'edit',$value->id],['escape'=>false,'class'=>'btn btn-success',]); ?>
													<?php echo $this->Html->link('View', ['controller'=>'faq','action'=>'view',$value->id],['escape'=>false,'class'=>'btn btn-info',]); ?>
												</td>
											</tr>
										<?php $start++;
										$i++;
										}
									} else { ?>
										<tr>
											<td colspan="5" class="text-center">No Record Found. </td>
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
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/dataTables.bootstrap4.js"></script>
