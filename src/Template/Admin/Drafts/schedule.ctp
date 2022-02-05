<?php
	// echo $this->Html->css(['admin/bootstrap-datepicker.min']);
	echo $this->Html->script([
			// 'admin/bootstrap-datepicker.min',
			'admin/jquery-3.2.1'
		]);
	use Cake\Core\Configure;
?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Schedule Contest</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Schedule Contest</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content schedule_outer">
		<div class="container-fluid">
			<div class="row">
				<!-- /.col -->
				<div class="col-md-12">
					<div class="card">
						<div class="card-header p-2">
							<ul class="nav nav-pills">
								<li class="nav-item"><a class="nav-link active" href="#upcoming" data-toggle="tab">UpComing</a></li>
								<li class="nav-item"><a class="nav-link" href="#live" data-toggle="tab">Live</a></li>
								<li class="nav-item"><a class="nav-link" href="#result" data-toggle="tab">Result</a></li>
							</ul>
						</div>
						<!-- /.card-header -->
						<div class="card-body">
							<div class="tab-content">
								<div class="active tab-pane" id="upcoming">
									<div class="post">
										<div class="col-md-12">
											<div class="table-responsive">
												<table id="example1" class="table table-bordered table-striped">
													<thead>
														<tr>
															<th>#</th>
															<th>Draft Name</th>
															<th>Start Date Time / End Date Time</th>
															<th>No of Contest</th>
															<th>Add Contest</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$i = 1;
														foreach ($upcoming as $key => $value) {
															$date1=strtotime(date('Y-m-d H:i:s'));
															$date2=strtotime($value->startdate.' '.$value->starttime);
															if($date1<$date2){
															$count=(isset($contest_ids_ary[$value->id])) ? ($contest_ids_ary[$value->id]) :0;
															$autoCount	=	0;

														?>
															<tr>
																<td><?php echo $i; ?></td>
																<td>
																	<?php echo $value->name; ?>
																</td>
																<td>
																	<?php echo date('Y-m-d H:i:s',strtotime($value->startdate.' '.$value->starttime)); ?> / 
																	<?php echo date('Y-m-d H:i:s',strtotime($value->enddate.' '.$value->endtime)); ?>
																</td>
																<td> <a href="<?=SITE_URL."admin/drafts/added_contest/".$value->id?>" class="btn btn-primary btn-sm"><?php echo $count; ?> Contest</a>
																</td>
																<td><a href="<?=SITE_URL."admin/drafts/addcontest/".$value->id?>" class="btn btn-primary btn-sm">Add Contest</a></td>
																
																
															</tr>
															<?php
															$i++;
														} }
														?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>

								<div class="tab-pane" id="live">
									<div class="post">
										<div class="col-md-12">
											<div class="table-responsive">
												<table id="example2" class="table table-bordered responsive" >
													<thead>
														<tr>
															<th>#</th>
															<th>Draft Name</th>
															<th>Start Date Time / End Date Time</th>
															<th><?php echo __('No of Contest'); ?></th>
														</tr>
													</thead>
													<tbody>
														<?php
														$i = 1;
														foreach ($live as $key => $value) {
															$date1=strtotime(date('Y-m-d H:i:s'));
															
															$date2=strtotime($value->startdate.' '.$value->starttime);
															$date3=strtotime($value->enddate.' '.$value->endtime);
															if($date1>$date2 && $date1<$date3){
															$count	=	(isset($contest_ids_ary[$value->id])) ? $contest_ids_ary[$value->id] : 0;
														?>
															<tr>
																<td><?php echo $i; ?></td>
																<td>
																	<?php echo $value->name; ?>
																</td>
																<td>
																	<?php echo date('Y-m-d H:i:s',strtotime($value->startdate.' '.$value->starttime)); ?> / 
																	<?php echo date('Y-m-d H:i:s',strtotime($value->enddate.' '.$value->endtime)); ?>
																</td>
																
																<td>
																	<a href="<?=SITE_URL."admin/drafts/added_contest/".$value->id?>" class="btn btn-primary btn-sm"><?php echo $count; ?> Contest</a>
																</td>
															<?php
															$i++;
															}
														} ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>

								<div class="tab-pane" id="result">
									<div class="post">
										<div class="col-md-12">
											<div class="table-responsive">
												<table id="example3" class="table table-bordered responsive" >
													<thead>
														<tr>
															<th>#</th>
															<th>Draft Name</th>
															<th>Start Date Time / End Date Time</th>
															<th><?php echo __('No of Contest'); ?></th>
														</tr>
													</thead>
													<tbody>
														<?php
														$i = 1;
														
														foreach ($complete as $key => $value) {
															$date1=strtotime(date('Y-m-d H:i:s'));
															$date2=strtotime($value->enddate.' '.$value->endtime);

															if($date1>$date2){

															$count	=	(isset($contest_ids_ary[$value->id])) ? $contest_ids_ary[$value->id] : 0;
														?>
															<tr>
																<td><?php echo $i; ?></td>

																<td>
																	<?php echo $value->name; ?>
																</td>

																<td>
																	<?php //echo $value->startdate.' '.$value->starttime;?>
																	<?php echo date('Y-m-d H:i:s',strtotime($value->startdate.' '.$value->starttime)); ?> / 
																	<?php echo date('Y-m-d H:i:s',strtotime($value->startdate.' '.$value->endtime)); ?>
																</td>
																
																<td>
																	<a href="<?=SITE_URL."admin/drafts/added_contest/".$value->id?>" class="btn btn-primary btn-sm"><?php echo $count; ?> Contest</a>
																</td>
															</tr>
															<?php
															$i++;
															}
														} ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>

							</div>
							<!-- /.tab-content -->
						</div>
						<!-- /.card-body -->
					</div>
					<!-- /.nav-tabs-custom -->
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</section>
</div>
<script src="<?= SITE_URL; ?>webroot/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/dataTables.bootstrap4.js"></script>
<script type="text/javascript">
	$(function () {
		$("#example1, #example2, #example3").DataTable();
	});
</script>