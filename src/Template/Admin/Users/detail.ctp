<?php ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User detail</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/users">User</a></li>
                        <li class="breadcrumb-item active">User detail</li>
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
                    	<table class="table table-bordered">
                    		<tbody>
				                <tr>
				 					<td><label>First Name : </label> <?=$user->full_name?></td>
                                     <td><label>Email : </label> <?=$user->email?></td>
				                </tr>
								<tr>
                                    <td><label>Gender : </label> <?=$user->gender?></td>
									<td><label>Phone : </label> <?=$user->phone?></td>
				                </tr>
								<tr>
                                    <td><label>Country : </label> <?=$user->country?></td>
									<td><label>State : </label> <?=$user->state?></td>
				                </tr>
                                <tr>
                                    <td><label>City : </label> <?=$user->city?></td>
                                    <td><label>Address : </label> <?=$user->address?></td>
                                </tr>
								<tr>
                                    <td><label>Referral Code : </label> <?php echo $user->referral_code; ?></td>
                                    <td><label>Winning Points : </label> <?php echo $user->winning_wallet; ?></td>
				                </tr>
                                <tr>
                                    <td><label>County Code : </label> <?=$user->country_code?></td>
                                </tr>
                                
			                </tbody>
			           	</table>
					</div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- <section class="content user-details">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
			<div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th colspan="5"><span style="float: right;">Total Winning Amount</span></th>
                                    <th><?=$this->Custom->getTotalUserWin($user->id);?></th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>Series Name</th>
                                    <th>Match Name</th>
                                    <th>Match Date</th>
                                    <th>Points</th>
                                    <th>Rank</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(isset($history) && !empty($history)){
                                $i=1;
                                foreach ($history as $key => $value) { ?>
                                    <tr>
                                        <td><?=$i;?></td>
                                        <td><?=$value['series_name'];?></td>
                                        <td><?=$value['match_name'];?></td>
                                        <td><?php echo (!empty($value['date'])) ? $value['date']->format('Y-m-d') : ''; ?></td>
                                        <td><?=$value['points'];?></td>
                                        <td><?=$value['rank'];?></td>
                                        <td><?=$value['winning_amount'];?></td>
                                    </tr>
                                <?php $i++;}  }else{ ?>
                                    <tr><td colspan="6">No record found.</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
</div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                    	<div class="col-sm-12" style="margin-bottom: 40px;">
							<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('controller' => 'Users', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-primary'))); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/dataTables.bootstrap4.js"></script>
<script>
    $(function () {
        $("#example1").DataTable();
    });
</script>