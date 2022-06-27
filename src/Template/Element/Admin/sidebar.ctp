<?php
	$name	=	$this->request->session()->read('Auth.Admin.first_name') . ' ' . $this->request->session()->read('Auth.Admin.last_name'); 
	$params	=	$this->request->params;
	// pr($loggedInUser['role_id']);die;
	$role_id  = (isset($loggedInUser['role_id'])) ? $loggedInUser['role_id'] : 0;
	$subadmin_roles  = (!empty($loggedInUser['module_access'])) ? explode(',',$loggedInUser['module_access']) : [];
	$cont	=	$params['controller'];
	$actn	=	$params['action'];
	// if($_SERVER['REMOTE_ADDR'] == '49.204.161.179'){
		// echo 'Subadmins :- ';pr($subadmin_roles);
		// echo 'Role ID :- ';pr($role_id);die;

	// }
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
	<img class="website" src="<?= SITE_URL.'/webroot/dist/img/logoinner.png' ?>" style="width: 30%;">
	<img class="mobile" src="<?= SITE_URL.'/webroot/dist/img/adminlogo.png' ?>" style="display:none;">
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
					<a href="<?= SITE_URL."/admin" ?>" class="nav-link <?php if(($cont=='Users') && ($actn=='dashboard')){ echo 'active'; }?>">
						<i class="nav-icon fa fa-dashboard"></i> 
						<p> Dashboard </p>
					</a>
				</li>
				<?php if (in_array($role_id,array(1)) ) { ?>
				<li class="nav-item has-treeview <?php  ?>">
					<a href="<?= SITE_URL."admin/SubAdmins" ?>" class="nav-link <?php if(($cont=='SubAdmins') && ($actn=='index')) { echo 'active'; } ?>">
						<i class="nav-icon fa fa-users"></i>
						<p> Manage Admin </p>
					</a>
					
				</li>
				 <?php } if (in_array($role_id,array(1)) || in_array('Users',$subadmin_roles) ) { ?>
					<li class="nav-item has-treeview <?php if(($cont=='Users') && ($actn=='index' || $actn=='add' || $actn=='subscriber'|| $actn=='referraldata' )){ echo 'menu-open'; }?>">
					<a href="<?= SITE_URL."admin/users" ?>" class="nav-link <?php if(($cont=='Users') && ($actn=='index' || $actn=='add' || $actn=='subscriber'|| $actn=='referraldata' )){ echo 'active'; }?>">
						<i class="nav-icon fa fa-users"></i>
						<p> Manage Users  </p>
					</a>
				</li>

				<?php } 
				if (in_array($role_id,array(1)) || in_array('FantasyPoints',$subadmin_roles) ) {
					echo '<li class="nav-item">';
					echo $this->Html->link('<i class="fa fa-star"></i> <p>Manage Points</p>',['controller'=>'FantasyPoints','action'=>'index'],['class'=>'nav-link '.(($cont == 'FantasyPoints' && $actn=='index') ? 'active' : ''),'escape'=>false]); 
					echo '</li>';
				}
				?>

				
				<?php  if (in_array($role_id,array(1)) || in_array('Team',$subadmin_roles) ) { ?>	
				<li class="nav-item">
					<?php echo $this->Html->link('<i class="fa fa-user"></i> <p>Manage Teams</p>',['controller'=>'Team','action'=>'index'],['class'=>'nav-link '.(($cont == 'Team' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
				</li>
				<?php } 
					if (in_array($role_id,array(1)) || in_array('Player',$subadmin_roles) ) {
						echo '<li class="nav-item">';
						 echo $this->Html->link('<i class="fa fa-user"></i> <p>Manage Players</p>',['controller'=>'Player','action'=>'index'],['class'=>'nav-link '.(($cont == 'Player' && $actn=='index') ? 'active' : ''),'escape'=>false]); 
						echo '</li>';
					}
					// if (in_array($role_id,array(1)) || in_array('Country',$subadmin_roles) ) {
					// 	echo '<li class="nav-item">';
					//   echo $this->Html->link('<i class="fa fa-flag"></i> <p>Manage Country</p>',['controller'=>'Country','action'=>'index'],['class'=>'nav-link '.(($cont == 'Country' && $actn=='index') ? 'active' : ''),'escape'=>false]); 
					// echo '</li>';}
					if (in_array($role_id,array(1)) || in_array('AdminManager',$subadmin_roles) ) {
					echo '<li class="nav-item">';
					 echo $this->Html->link('<i class="fa fa-calendar "></i> <p>Manage Week</p>',['controller'=>'Week','action'=>'index'],['class'=>'nav-link '.(($cont == 'Week' && $actn=='index') ? 'active' : ''),'escape'=>false]); 
					echo '</li>';}


					if (in_array($role_id,array(1)) || in_array('Pages',$subadmin_roles) ) {
						echo '<li class="nav-item">';
					 echo $this->Html->link('<i class="fa fa-list"></i> <p>Manage CMS</p>',['controller'=>'Pages','action'=>'index'],['class'=>'nav-link '.(($cont == 'Pages' && $actn=='index') ? 'active' : ''),'escape'=>false]); 
					echo '</li>';} 

					// if (in_array($role_id,array(1)) || in_array('Blogs',$subadmin_roles) ) {
					// 	echo '<li class="nav-item">';
					// echo $this->Html->link('<i class="fa fa-file"></i> <p>Manage Blogs</p>',['controller'=>'Blogs','action'=>'index'],['class'=>'nav-link '.(($cont == 'Blogs' && $actn=='index') ? 'active' : ''),'escape'=>false]); 
					// echo '</li>';} 

					if (in_array($role_id,array(1)) || in_array('Transaction',$subadmin_roles) ) {
						echo '<li class="nav-item">';
					echo $this->Html->link('<i class="fa fa-file"></i> <p>User Transactions</p>',['controller'=>'Users','action'=>'usertransection'],['class'=>'nav-link '.(($cont == 'Users' && $actn=='usertransection') ? 'active' : ''),'escape'=>false]); 
					echo '</li>';} 


					if (in_array($role_id,array(1)) || in_array('News',$subadmin_roles) ) {
						echo '<li class="nav-item">';
					 		echo $this->Html->link('<i class="fa fa-file"></i> <p>Manage News</p>',['controller'=>'News','action'=>'index'],['class'=>'nav-link '.(($cont == 'News' && $actn=='index') ? 'active' : ''),'escape'=>false]); 
						echo '</li>';
					} 

					if (in_array($role_id,array(1)) || in_array('FAQ',$subadmin_roles) ) {
						echo '<li class="nav-item">';
					 		echo $this->Html->link('<i class="fa fa-question-circle nav-icon"></i> <p>Faq Manager</p>',['controller'=>'faq','action'=>'index'],['class'=>'nav-link '.(($cont == 'Faq' && $actn=='index') ? 'active' : ''),'escape'=>false]); 
						echo '</li>';
					} 

					if (in_array($role_id,array(1))  ) {
						echo '<li class="nav-item">';
							echo $this->Html->link('<i class="fa fa-cogs nav-icon"></i> <p>Settings</p>',['controller'=>'Settings','action'=>'index'],['class'=>'nav-link '.(($cont == 'Settings' && $actn=='index') ? 'active' : ''),'escape'=>false]); 
						echo '</li>';
					}
				
					if (in_array($role_id,array(1)) ) {
						echo '<li class="nav-item">';
							echo $this->Html->link('<i class="fa fa-user nav-icon"></i> <p>Manage Profile</p>',['controller'=>'users','action'=>'profile'],['class'=>'nav-link '.(($cont == 'Users' && $actn=='profile') ? 'active' : ''),'escape'=>false]); 
						echo '</li>';
					}


					if (in_array($role_id,array(1)) ) {
						echo '<li class="nav-item">';
					    	echo $this->Html->link('<i class="fa fa-lock nav-icon"></i> <p>Change Password</p>',['controller'=>'users','action'=>'changePassword'],['class'=>'nav-link '.(($cont == 'Users' && $actn=='changePassword') ? 'active' : ''),'escape'=>false]); 
						echo '</li>';
					}
					?>
				




					<!-- <li class="nav-item">
					<?php echo $this->Html->link('<i class="nav-icon fa fa-users nav-icon"></i> <p>User Detail</p>',['controller'=>'users','action'=>'userdetail'],['class'=>'nav-link '.(($cont == 'Users' && $actn=='userdetail') ? 'active' : ''),'escape'=>false]); ?>
				</li> -->
				<!-- <li class="nav-item">
					<?php echo $this->Html->link('<i class="fa fa-newspaper-o nav-icon"></i> <p>Contents Manager</p>',['controller'=>'contents','action'=>'index'],['class'=>'nav-link '.(($cont == 'Contents' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
				</li> -->

				<!-- <li class="nav-item">
				<?php //echo $this->Html->link('<i class="fa fa-envelope-o nav-icon"></i> <p>Email Templates</p>',['controller'=>'emailTemplates','action'=>'index'],['class'=>'nav-link '.(($cont == 'EmailTemplates' && $actn=='index') ? 'active' : ''),'escape'=>false]); ?>
				</li> -->
			</ul>
		</nav>
	</div>
</aside>