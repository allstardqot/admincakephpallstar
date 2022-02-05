<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Contest detail</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/contest">Contest</a></li>
						<li class="breadcrumb-item active">Contest Detail</li>
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
						<table class="table table-bordered">
							<tbody>
								<tr>
									<td width="30%"><label>Category Name : </label> </td>
									<td width="70%"><?=$list['category_name']?></td>
								</tr>
								<!-- <tr>
									<td><label>Contest Name : </label> </td>
									<td><?=$contest->contest_name?></td>
								</tr> -->
								<tr>
									<td><label>Winning Amount (INR) : </label> </td>
									<td><?=$contest->winning_amount?></td>
								</tr>
								<tr>
									<td><label>Contest Size : </label> </td>
									<td><?=$contest->contest_size?></td>
								</tr>
								<!--<tr>
									<td><label>Minimum Contest Size : </label> </td>
									<td><?=$contest->min_contest_size?></td>
								</tr>-->
								<tr>
									<td><label>Contest Type : </label> </td>
									<td><?=$contest->contest_type?></td>
								</tr>
								<tr>
									<td><label>Entry Fee (INR) : </label> </td>
									<td><?=$contest->entry_fee?></td>
								</tr>
								<tr>
									<td><label>Added Date : </label> </td>
									<td><?=date("Y-m-d", strtotime($contest->created));?></td>
								</tr>
								<tr>
									<td><label>Auto Create : </label> </td>
									<td><?=$contest->auto_create=='yes'?'Yes':'No'?></td>
								</tr>
								<tr>
									<td><label>Join with multiple teams : </label> </td>
									<td><?=$contest->multiple_team=='yes'?'Yes':'No'?></td>
								</tr>
								<tr>
									<td><label>Confirmed winning : </label> </td>
									<td><?=$contest->confirmed_winning=='yes'?'Yes':'No'?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body"> <span><b>Price Breakup</b></span><br><br>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Percentage</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            	<?php
                            	if(!empty($priceBreak)){
                            		foreach ($priceBreak as $value) { ?>
                            			<tr>
	                            			<td><?=$value->name;?></td>
	                            			<td><?=$value->start;?></td>
	                            			<td><?=$value->end;?></td>
	                            			<td><?=$value->percentage;?></td>
	                            			<td><?=$value->price;?></td>
	                            		</tr>
                            		<?php }
                            	}
                            	?>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
	
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="col-sm-12" style="margin-bottom: 40px;">
							<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('controller' => 'Contest', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-primary'))); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
