<style type="text/css">
.pagination {
	display: flex;
	padding-left: 144px;
}
div#example1_filter {
	margin-left: 280px;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Dashboard</h1>
				</div>
				<!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">Dashboard</li>
					</ol>
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
	</div>
	<!-- Main content -->
	<section class="content dashboard_outer">
		<div class="container-fluid">
			<!-- Small boxes (Stat box) -->
			<div class="row">
				<div class="col-md-3 col-lg-3 col-6">
					<div class="small-box bg-info">
						<div class="inner">
							<h3><?php echo $this->Custom->totalUser();?></h3>
							<p>Total Users</p>
						</div>
						<div class="icon">
							<i class="ion ion-bag"></i>
						</div>
						<?php echo $this->Html->link('<i class="fa fa-arrow-circle-right"></i>',['controller'=>'users','action'=>'index'],['class'=>'small-box-footer','escape'=>false]); ?>
					</div>
				</div>

				<div class="col-md-3 col-lg-3 col-6">
					<div class="small-box bg-warning">
						<div class="inner">
							<h3><?=$this->Custom->totalNewUser();?></h3>
							<p>New Registration</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<?php echo $this->Html->link('<i class="fa fa-arrow-circle-right"></i>',['controller'=>'users','action'=>'index?status=new'],['class'=>'small-box-footer','escape'=>false]); ?>

						</a>
					</div>
				</div>
				
			</div>

			<div class="row">
				<!-- <div class="col-md-3 col-lg-3 col-6">
					<div class="small-box bg-danger">
						<div class="inner">
							<h3><?php  ?></h3>
							<p>Created Contest</p>
						</div>
						<div class="icon">
							<i class="ion ion-pie-graph"></i>
						</div>
						<?php echo $this->Html->link('<i class="fa fa-arrow-circle-right"></i>',['controller'=>'users','action'=>'index'],['class'=>'small-box-footer','escape'=>false]); ?>
					</div>
				</div> -->
				<!-- <div class="col-md-3 col-lg-3 col-6">
					<div class="small-box bg-warning">
						<div class="inner">
							<h3><?php  ?></h3>
							<p>Active Contest</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<?php echo $this->Html->link('<i class="fa fa-arrow-circle-right"></i>',['controller'=>'users','action'=>'index?status=active'],['class'=>'small-box-footer','escape'=>false]); ?>
					</div>
				</div> -->
				<!-- <div class="col-md-3 col-lg-3 col-6">
					<div class="small-box bg-success">
						<div class="inner">
							<h3><?php ?></h3>
							<p>Inactive Contest</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<?php echo $this->Html->link('<i class="fa fa-arrow-circle-right"></i>',['controller'=>'users','action'=>'index?status=deactive'],['class'=>'small-box-footer','escape'=>false]); ?>
					</div>
				</div> -->
				
			</div>
			
			<?php if($authUser['role_id'] == 1) { ?>
				<!-- <div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">Admin Last Login</h3>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example1" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>#</th>
											<th>Name</th>
											<th>Last Login</th>
										</tr>
									</thead>
									<tbody>
										<?php if(!empty($subadminUser)) {
											$i	=	0;
											foreach($subadminUser as $user) {
												$i++;
												?>
												<tr>
													<td><?php echo $i; ?></td>
													<td>
														<?php echo ucwords($user->first_name.' '.$user->last_name); ?>
													</td>
													<td><?php echo $user->last_login; ?></td>
												</tr>
											<?php
											}
										} else { ?>
											<tr>
												<td colspan="5" class="text-center"> No Record found.</td>
											</tr>
										<?php } ?>
									</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div> -->
			<?php } ?>
		</div>
	</section>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?= SITE_URL; ?>/webroot/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= SITE_URL; ?>/webroot/plugins/datatables/dataTables.bootstrap4.js"></script>
<script type="text/javascript">
	$(function () {
	    $("#example1").DataTable();
	});
</script>