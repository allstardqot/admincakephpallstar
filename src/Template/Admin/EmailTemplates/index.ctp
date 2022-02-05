<?php echo $this->Html->script(['admin/jquery-3.2.1']) ?>
<?php use Cake\Core\Configure; ?>

<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Email Template List</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Email Template List</li>
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
									  <th>Email Subject</th> 
									  <th>Key</th> 
									  <th>Status</th> 
									  <th class="last_td">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i	=	1;
									if(!empty($result)) {
										foreach ($result as $key => $value) { ?>
											<tr>
												<td><?php echo $i; ?></td>
												<td><?php echo !empty($value->email_name) ? $value->email_name : ''; ?></td>
												<td><?php echo !empty($value->subject) ? $value->subject : ''; ?></td>
												<td>
													<?php
														echo $this->Html->link(($value->status == 1) ? '<span class="label-success label label-default">Active</span>' : '<span class="label-default label label-danger">Inactive</span>', ['prefix' => 'admin', 'controller' => 'emailTemplates', 'action' => 'status', $value->id], ['escape' => false]);
													?>
												</td>
												<td class="last_td">
													<?php echo $this->Html->link('Edit', ['controller'=>'emailTemplates','action'=>'edit',$value->id],['escape'=>false,'class'=>'btn btn-success',]); ?>
													<?php echo $this->Html->link('View', ['controller'=>'emailTemplates','action'=>'view',$value->id],['escape'=>false,'class'=>'btn btn-info']); ?>
												</td>
											</tr>
										<?php
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
