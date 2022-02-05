<?php
	$name	=	$this->request->session()->read('Auth.Admin.first_name') . ' ' . $this->request->session()->read('Auth.Admin.last_name'); 
	$params	=	$this->request->params;
	$cont	=	$params['controller'];
	$actn	=	$params['action'];
?>
<script>
	$(document).ready(function() {
		$('.sidebar-dark-primary').mouseover(function() {
			$('.sidebar-dark-primary').addClass('logo_change');
		});
		$('.sidebar-dark-primary').mouseleave(function() {
			$('.sidebar-dark-primary').removeClass('logo_change');
		});
	});
</script>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="<?= SITE_URL."admin" ?>" class="brand-link">
	<img class="website" src="<?= SITE_URL.'webroot/dist/img/logoinner.png' ?>" style="width: 30%;">
	<img class="mobile" src="<?= SITE_URL.'webroot/dist/img/adminlogo.png' ?>" style="display:none;">
	</a>
	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="<?=SITE_URL ?>webroot/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
			</div>
			<div class="info">
				<a href="#" class="d-block"><?= $name ?></a>
			</div>
			</div> -->
		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" style="padding-bottom:115px;">
				<li class="nav-item">
					<a href="<?= SITE_URL."admin" ?>" class="nav-link <?php if(($cont=='Users') && ($actn=='dashboard')){ echo 'active'; }?>">
						<i class="nav-icon fa fa-dashboard"></i> 
						<p> Dashboard </p>
					</a>
				</li>
				<li class="nav-item has-treeview <?php if(($cont=='SubAdmins')) { echo 'menu-open'; }?>">
					<a href="javascript:void(0)" class="nav-link <?php if(($cont=='SubAdmins')) { echo 'active'; } ?>">
						<i class="nav-icon fa fa-users"></i>
						<p> Sub Admin<i class="right fa fa-angle-left"></i> </p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<?php echo $this->Html->link('<i class="fa fa-circle-o nav-icon"></i> List',['controller'=>'subAdmins','action'=>'index'],['class'=>'nav-link '.(($cont == 'SubAdmins' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
						</li>
						<li class="nav-item">
							<?php echo $this->Html->link('<i class="fa fa-circle-o nav-icon"></i> Add',['controller'=>'subAdmins','action'=>'add'],['class'=>'nav-link '.(($cont == 'SubAdmins' && $actn=='add') ? 'active' : ''),'escape'=>false]); ?>
						</li>
					</ul>
				</li>

				<li class="nav-item has-treeview <?php if(($cont=='Users') && ($actn=='index' || $actn=='add' || $actn=='subscriber'|| $actn=='referraldata' )){ echo 'menu-open'; }?>">
					<a href="javascript:void(0)" class="nav-link <?php if(($cont=='Users') && ($actn=='index' || $actn=='add' || $actn=='subscriber'|| $actn=='referraldata' )){ echo 'active'; }?>">
						<i class="nav-icon fa fa-users"></i>
						<p> Users <i class="right fa fa-angle-left"></i> </p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?=SITE_URL."admin/users"?>" class="nav-link <?php if(($cont=='Users') && ($actn=='index')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>List</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?=SITE_URL."admin/users/add"?>" class="nav-link <?php if(($cont=='Users') && ($actn=='add')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>Add</p>
							</a>
						</li>
						
					</ul>
				</li>

				<!-- <li class="nav-item has-treeview <?php if(($cont=='Jobs') && (($actn=='index') || ($actn=='add'))){ echo 'menu-open'; }?>">
					<a href="javascript:void(0)" class="nav-link <?php if(($cont=='Jobs') && (($actn=='index') || ($actn=='add'))){ echo 'active'; }?>">
						<i class="nav-icon fa fa-list-ul"></i>
						<p> Jobs Manager <i class="right fa fa-angle-left"></i> </p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?=SITE_URL."admin/jobs"?>" class="nav-link <?php if(($cont=='Jobs') && ($actn=='index')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>List</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?=SITE_URL."admin/jobs/add"?>" class="nav-link <?php if(($cont=='Jobs') && ($actn=='add')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>Add</p>
							</a>
						</li>
					</ul>
				</li> -->

				<li class="nav-item has-treeview <?php if(($cont=='Category') && (($actn=='index') || ($actn=='add'))){ echo 'menu-open'; }?>">
					<a href="javascript:void(0)" class="nav-link <?php if(($cont=='Category') && (($actn=='index') || ($actn=='add'))){ echo 'active'; }?>">
						<i class="nav-icon fa fa-list-ul"></i>
						<p> Category Manager <i class="right fa fa-angle-left"></i> </p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?=SITE_URL."admin/category"?>" class="nav-link <?php if(($cont=='Category') && ($actn=='index')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>List</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?=SITE_URL."admin/category/add"?>" class="nav-link <?php if(($cont=='Category') && ($actn=='add')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>Add</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-item has-treeview <?php if(($cont=='Contest') && (($actn=='index') || ($actn=='add') || ($actn=='price'))){ echo 'menu-open'; }?>">
					<a href="javascript:void(0)" class="nav-link <?php if(($cont=='Contest') && (($actn=='index') || ($actn=='add') || ($actn=='price'))){ echo 'active'; }?>">
						<i class="nav-icon fa fa-database"></i>
						<p> Contest Manager <i class="right fa fa-angle-left"></i> </p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?=SITE_URL."admin/contest"?>" class="nav-link <?php if(($cont=='Contest') && ($actn=='index')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>List</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?=SITE_URL."admin/contest/add"?>" class="nav-link <?php if(($cont=='Contest') && ($actn=='add')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>Add</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-item has-treeview <?php if(($cont=='Drafts') && (in_array($actn,['index','addnew','edit','schedule'])) ){ echo 'menu-open'; }?>">
					<a href="javascript:void(0)" class="nav-link <?php if(($cont=='Drafts') && (in_array($actn,['index','addnew','edit','schedule'])) ){ echo 'active'; }?>">
						<i class="nav-icon fa fa-database"></i>
						<p> Fantasy Modules <i class="right fa fa-angle-left"></i> </p>
					</a>
					<ul class="nav nav-treeview">

						<li class="nav-item">
							<a href="<?=SITE_URL."admin/drafts"?>" class="nav-link <?php if(($cont=='Drafts') && ($actn=='index') ) { echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>Draft List</p>
							</a>
						</li>

						<li class="nav-item">
							<a href="<?=SITE_URL."admin/drafts/addnew"?>" class="nav-link <?php if(($cont=='Drafts') && ($actn=='addnew') ) { echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>Add Draft</p>
							</a>
						</li>


						<li class="nav-item">
							<a href="<?= SITE_URL."admin/drafts/schedule" ?>" class="nav-link <?php if(($cont=='Drafts') && ($actn=='schedule') ) { echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p> Schedule Contest </p>
							</a>
						</li>

					</ul>
				</li>

				<!-- <li class="nav-item has-treeview <?php if(($cont=='Series' || $cont=='Schedule' || $cont=='Points' || $cont=='SeriesPlayers' || $cont=='MstTeams') && (($actn=='index'))){ echo 'menu-open'; }?>">
					<a href="javascript:void(0)" class="nav-link <?php if(($cont=='Series' || $cont=='Schedule' || $cont=='Points' || $cont=='SeriesPlayers' || $cont=='MstTeams') && (($actn=='index'))){ echo 'active'; }?>">
						<i class="nav-icon fa fa-database"></i>
						<p> Cricket Modules <i class="right fa fa-angle-left"></i> </p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?= SITE_URL."admin/series" ?>" class="nav-link <?php if(($cont=='Series') && ($actn=='index')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p> Series Contest </p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= SITE_URL."admin/schedule" ?>" class="nav-link <?php if(($cont=='Schedule') && (($actn=='index') || ($actn=='contest') || ($actn=='addedContest') || ($actn=='contestDetails'))){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p> Schedule Contest </p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= SITE_URL."admin/points" ?>" class="nav-link <?php if(($cont=='Points') && ($actn=='index')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p> Points System </p>
							</a>
						</li>

						<li class="nav-item">
							<?php echo $this->Html->link('<i class="fa fa-circle-o nav-icon"></i> Manage Player',['controller'=>'seriesPlayers','action'=>'index'],['class'=>'nav-link '.(($cont == 'SeriesPlayers' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
						</li>

						<li class="nav-item">
							<?php echo $this->Html->link('<i class="fa fa-circle-o nav-icon"></i> <p>Teams Manager</p>',['controller'=>'mstTeams','action'=>'index'],['class'=>'nav-link '.(($cont == 'MstTeams' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
						</li>

						<li class="nav-item">
							<?php echo $this->Html->link('<i class="fa fa-circle-o nav-icon"></i> <p>PreSquad Matches</p>',['controller'=>'matches','action'=>'index'],['class'=>'nav-link '.(($cont == 'matches' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
						</li>

					</ul>
				</li> -->


				<!-- <li class="nav-item has-treeview <?php if(($cont=='Sseries' || $cont=='Sschedule' || $cont=='Spoints' || $cont=='SseriesPlayers' || $cont=='Steams') && (($actn=='index'))){ echo 'menu-open'; }?>">
					<a href="javascript:void(0)" class="nav-link <?php if(($cont=='Sseries' || $cont=='Sschedule' || $cont=='Spoints' || $cont=='SseriesPlayers' || $cont=='Steams') && (($actn=='index'))){ echo 'active'; }?>">
						<i class="nav-icon fa fa-database"></i>
						<p> Football Modules <i class="right fa fa-angle-left"></i> </p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?= SITE_URL."admin/sseries" ?>" class="nav-link <?php if(($cont=='Sseries') && ($actn=='index')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p> Series Contest </p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= SITE_URL."admin/sschedule" ?>" class="nav-link <?php if(($cont=='Sschedule') && (($actn=='index') || ($actn=='contest') || ($actn=='addedContest') || ($actn=='contestDetails'))){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p> Schedule Contest </p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= SITE_URL."admin/spoints" ?>" class="nav-link <?php if(($cont=='Spoints') && ($actn=='index')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p> Points System </p>
							</a>
						</li>

						<li class="nav-item">
							<?php echo $this->Html->link('<i class="fa fa-circle-o nav-icon"></i> Manage Player',['controller'=>'SseriesPlayers','action'=>'index'],['class'=>'nav-link '.(($cont == 'SseriesPlayers' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
						</li>

						<li class="nav-item">
							<?php echo $this->Html->link('<i class="fa fa-circle-o nav-icon"></i> <p>Teams Manager</p>',['controller'=>'Steams','action'=>'index'],['class'=>'nav-link '.(($cont == 'Steams' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
						</li>

					</ul>
				</li> -->
				
				<!-- <li class="nav-item">
					<?php echo $this->Html->link('<i class="fa fa-list-ul nav-icon"></i> <p>TDS</p>',['controller'=>'tdsDetails','action'=>'index'],['class'=>'nav-link '.(($cont == 'TdsDetails' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
				</li>
				<li class="nav-item">
					<?php echo $this->Html->link('<i class="fa fa-paypal nav-icon"></i> <p>Transaction</p>',['controller'=>'transactions','action'=>'index'],['class'=>'nav-link '.(($cont == 'Transactions' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
				</li>
				<li class="nav-item">
					<?php echo $this->Html->link('<i class="fa fa-money nav-icon" ></i> <p>Wallet Manager</p>',['controller'=>'wallets','action'=>'index'],['class'=>'nav-link '.(($cont == 'Wallets' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
				</li>
				<li class="nav-item">
					<?php echo $this->Html->link('<i class="fa fa-money nav-icon" ></i> <p>Earning Manager</p>',['controller'=>'earnings','action'=>'index'],['class'=>'nav-link '.(($cont == 'Earnings' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
				</li> -->


				


				<!-- <li class="nav-item has-treeview <?php if(($cont=='PaymentOffers')) { echo 'menu-open'; }?>">
					<a href="javascript:void(0)" class="nav-link <?php if(($cont=='PaymentOffers')) { echo 'active'; } ?>">
						<i class="nav-icon fa fa-users"></i>
						<p>Payment Offers<i class="right fa fa-angle-left"></i> </p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<?php echo $this->Html->link('<i class="fa fa-circle-o nav-icon"></i> List',['controller'=>'paymentOffers','action'=>'index'],['class'=>'nav-link '.(($cont == 'PaymentOffers' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
						</li>
						<li class="nav-item">
							<?php echo $this->Html->link('<i class="fa fa-circle-o nav-icon"></i> Add',['controller'=>'paymentOffers','action'=>'add'],['class'=>'nav-link '.(($cont == 'PaymentOffers' && $actn=='add') ? 'active' : ''),'escape'=>false]); ?>
						</li>
					</ul>
				</li> -->

				<li class="nav-item has-treeview <?php if(($cont=='Banners') && (($actn=='index') || ($actn=='add'))){ echo 'menu-open'; }?>">
					<a href="javascript:void(0)" class="nav-link <?php if(($cont=='Banners') && (($actn=='index') || ($actn=='add'))){ echo 'active'; }?>">
						<i class="nav-icon fa fa-picture-o"></i>
						<p> Banner Manager <i class="right fa fa-angle-left"></i> </p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?=SITE_URL."admin/banners"?>" class="nav-link <?php if(($cont=='Banners') && ($actn=='index')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>List</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?=SITE_URL."admin/banners/add"?>" class="nav-link <?php if(($cont=='Banners') && ($actn=='add')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>Add</p>
							</a>
						</li>
					</ul>
				</li>
				
				
				<!-- <li class="nav-item">
					<?php //echo $this->Html->link('<i class="fa fa-list-ul nav-icon"></i> <p>Withdraw (Pending)</p>',['controller'=>'withdrawRequests','action'=>'index'],['class'=>'nav-link '.(($cont == 'WithdrawRequests' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
				</li>

				<li class="nav-item">
					<?php //echo $this->Html->link('<i class="fa fa-list-ul nav-icon"></i> <p>Withdraw  (Confirmed)</p>',['controller'=>'withdrawRequests','action'=>'confirmed'],['class'=>'nav-link '.(($cont == 'WithdrawRequests' && $actn=='confirmed') ? 'active' : ''),'escape'=>false]); ?>
				</li> -->

				<li class="nav-item has-treeview <?php if(($cont=='Notifications') && (($actn=='sendnew') || ($actn=='received') || ($actn=='price'))){ echo 'menu-open'; }?>">
					<a href="javascript:void(0)" class="nav-link <?php if(($cont=='Notifications') && (($actn=='sendnew') || ($actn=='received'))){ echo 'active'; }?>">
						<i class="nav-icon fa fa-bell"></i>
						<p> Notification Manager <i class="right fa fa-angle-left"></i> </p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?=SITE_URL."admin/notifications/sendnew"?>" class="nav-link <?php if(($cont=='Notifications') && ($actn=='sendnew')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>Sent</p>
							</a>
						</li>
						
					</ul>
				</li>
				<li class="nav-item">
					<?php echo $this->Html->link('<i class="nav-icon fa fa-users nav-icon"></i> <p>User Detail</p>',['controller'=>'users','action'=>'userdetail'],['class'=>'nav-link '.(($cont == 'Users' && $actn=='userdetail') ? 'active' : ''),'escape'=>false]); ?>
				</li>
				<li class="nav-item">
					<?php echo $this->Html->link('<i class="fa fa-newspaper-o nav-icon"></i> <p>Contents Manager</p>',['controller'=>'contents','action'=>'index'],['class'=>'nav-link '.(($cont == 'Contents' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
				</li>
				<li class="nav-item">
					<?php echo $this->Html->link('<i class="fa fa-question-circle nav-icon"></i> <p>Faq Manager</p>',['controller'=>'faq','action'=>'index'],['class'=>'nav-link '.(($cont == 'Faq' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
				</li>
				<li class="nav-item">
					<?php echo $this->Html->link('<i class="fa fa-cogs nav-icon"></i> <p>Settings</p>',['controller'=>'Settings','action'=>'index'],['class'=>'nav-link '.(($cont == 'Settings' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
				</li>
				<li class="nav-item">
					<?php echo $this->Html->link('<i class="fa fa-envelope-o nav-icon"></i> <p>Email Templates</p>',['controller'=>'emailTemplates','action'=>'index'],['class'=>'nav-link '.(($cont == 'EmailTemplates' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
				</li>
				<li class="nav-item">
					<?php echo $this->Html->link('<i class="fa fa-user nav-icon"></i> <p>Profile</p>',['controller'=>'users','action'=>'profile'],['class'=>'nav-link '.(($cont == 'Users' && $actn=='profile') ? 'active' : ''),'escape'=>false]); ?>
				</li>
				<li class="nav-item">
					<?php echo $this->Html->link('<i class="fa fa-lock nav-icon"></i> <p>Change Password</p>',['controller'=>'users','action'=>'changePassword'],['class'=>'nav-link '.(($cont == 'Users' && $actn=='changePassword') ? 'active' : ''),'escape'=>false]); ?>
				</li>
			</ul>
		</nav>
	</div>
</aside>