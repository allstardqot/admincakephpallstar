<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Core\Configure;
?>
<!DOCTYPE html>
<?php
	use Cake\Routing\Router;
	$path = Router::url('/', true);
?>

<html>
	<head>
	  <meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <?php /* <title><?php echo $title_for_layout; ?></title> */ ?>
	  <title><?php echo SITE_TITLE; ?></title>
	  <!-- Tell the browser to be responsive to screen width -->
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <!--  -->
	  <!-- Google Font: Source Sans Pro -->
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
		<!-- <link rel="shortcut icon" href="<?= SITE_URL.'webroot/dist/img/favicon.png' ?>" type="image/x-icon"> -->
		<!-- <link rel="icon" href="<?= SITE_URL.'webroot/dist/img/favicon.png' ?>" type="image/x-icon"> -->
		<link rel="stylesheet" href="<?= SITE_URL.'webroot/css/admin/select2-bootstrap.min.css' ?>">
		<link rel="stylesheet" href="<?= SITE_URL.'webroot/css/admin/select2/select2.min.css' ?>">

		
	  <?php
			echo $this->Html->css([
				'../plugins/font-awesome/css/font-awesome.min.css',	//Font Awesome
				'../dist/css/adminlte.min.css',	//Theme style
				'../plugins/datepicker/datepicker3.css',	//Date Picker
				'admin/bootstrap.min.css',
				'admin/editor.css',
				'admin/rte_theme_default.css',
				'admin/custom.css',
				// 'bootstrap-multiselect.css',
			]);
		?>
		<script src="<?php echo SITE_URL ?>webroot/js/admin/jquery-3.5.1.min.js"></script>
		<script src="<?= SITE_URL; ?>/webroot/js/admin/bootstrap.min.js"></script>
		

	</head>
	<body class="hold-transition sidebar-mini">
		<div class="wrapper">
			<div class="loader" style="display:none;">
				<?php echo $this->Html->image('loader.gif'); ?>
			</div>
			<?php echo $this->element('Admin/header'); ?>
			<div class="col-md-12"> <?php echo $this->Flash->render() ?></div>
			<div class="clearfix"> </div>
			<?php echo $this->element('Admin/sidebar'); ?>
			
			<?php echo $this->fetch('content'); ?>
			
		</div>
		<?php echo $this->element('Admin/footer'); ?>
		
		<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js" integrity="sha256-6XMVI0zB8cRzfZjqKcD01PBsAy3FlDASrlC8SxCpInY=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.6.0/umd/popper.min.js" integrity="sha512-BmM0/BQlqh02wuK5Gz9yrbe7VyIVwOzD1o40yi1IsTjriX/NGF37NyXHfmFzIlMmoSIBXgqDiG1VNU6kB5dBbA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

		<script src="<?= SITE_URL; ?>/webroot/plugins/datepicker/bootstrap-datepicker.js"></script>

		<script src="<?= SITE_URL; ?>/webroot/dist/js/adminlte.js"></script>
		<script src="<?= SITE_URL; ?>/webroot/js/admin/custom.js"></script>
		<script src="<?= SITE_URL; ?>/webroot/js/admin/validation.js"></script>
		<script src="<?= SITE_URL; ?>/webroot/js/admin/select2/select2.js"></script>
		<script src="<?= SITE_URL; ?>/webroot/js/admin/bootstrap-multiselect.js"></script>
		<script src="<?php echo SITE_URL ?>webroot/js/admin/all_plugins.js"></script>
		<script src="<?php echo SITE_URL ?>webroot/js/admin/rte.js"></script>
		
		<script src="<?php echo SITE_URL ?>webroot/js/admin/editor.js"></script>
		
		<script>

// editor1.appendTo("#rte");

			$(document).ready(function(){
				$( ".my_date_picker" ).datepicker();
  
				$('#subadmin_roles').select2({
					placeholder:"Select Module Access"
				});
				
				$('#subadmin_roles').change(function() {
					var value	=	$(this).val();
					$('.module_accesss').val(value.toString())
				});
				
				$('.editButton').on('click',function(e){
					e.preventDefault();
					var val=$(this).val();
					// alert(val)
					$.ajax({
						url:'SubAdmins/edit',
						type:"POST",
						data: {eid:val},
						
						success:function(data){
							// console.log(data);
							$("#myModal_edit").html(data);
							$('#myModal_edit').modal('show');
						}
					})
				});

				$('.pointedit').on('click',function(e){
					e.preventDefault();
					var val=$(this).val();
					// alert(val)
					$.ajax({
						url:'FantasyPoints/edit',
						type:"POST",
						data: {eid:val},
						
						success:function(data){
							// console.log(data);
							$("#myPoint_edit").html(data);
							$('#myPoint_edit').modal('show');
						}
					})
				});

				$('.editUser').on('click',function(e){
					e.preventDefault();
					var val=$(this).val();
					// alert(val)
					$.ajax({
						url:'users/edit',
						type:"POST",
						data: {eid:val},
						
						success:function(data){
							// console.log(data);
							$("#usersedit").html(data);
							$('#usersedit').modal('show');
						}
					})
				});


				$('.editTeamButton').on('click',function(e){
					// alert('asdlfhldf');
					e.preventDefault();
					var val=$(this).val();
					// alert(val)
					$.ajax({
						url:'team/edit',
						type:"POST",
						data: {eid:val},
						
						success:function(data){
							// console.log(data);
							$("#teamsedit").html(data);
							$('#teamsedit').modal('show');
						}
					})
				});

				$('.palyerEdit').on('click',function(e){
					// alert('asdlfhldf');
					e.preventDefault();
					var val=$(this).val();
					// alert(val)
					$.ajax({
						url:'player/edit',
						type:"POST",
						data: {eid:val},
						
						success:function(data){
							// console.log(data);
							$("#playersedit").html(data);
							$('#playersedit').modal('show');
						}
					})
				});

				$('#manageAdmin').on('click',function(e){
						$('#myModal').modal('show');
						// alert('sdkjfsdjhf')

				});


				// Week Manage
				$('#manageweek').on('click',function(e){
						$('#WeekModel').modal('show');
						// alert('sdkjfsdjhf')

				});

				$('.editWeekButton').on('click',function(e){
					e.preventDefault();
					var val=$(this).val();
					// alert(val)
					$.ajax({
						url:'Week/edit',
						type:"POST",
						data: {eid:val},
						
						success:function(data){
							// console.log(data);
							$("#weekedit").html(data);
							$('#weekedit').modal('show');
						}
					})
				});

				$('#submitWeek').on('click',function(){
					// alert('asdha');
					$('#weekform').submit();
				});
				// End Week Manager


				$('.editCountry').on('click',function(e){
					// alert('asdlfhldf');
					e.preventDefault();
					var val=$(this).val();
					// alert(val)
					$.ajax({
						url:'country/edit',
						type:"POST",
						data: {eid:val},
						
						success:function(data){
							// console.log(data);
							$("#country_edit").html(data);
							$('#country_edit').modal('show');
						}
					})
				});
				
			});

		</script>
	</body>
</html>