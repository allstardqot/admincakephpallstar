<?php
	use Cake\Core\Configure; 
	use Cake\Routing\Router;
	// $path	=	Router::url('/', true);
?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>View Email Template</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/email-templates">Email Template List</a></li>
						<li class="breadcrumb-item active">View Email Template</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-primary">
						<table class="table table-bordered">
							<tr>
								<td width="50%">
									<label>Email Subject : </label>
									<?php echo !empty($result->email_name) ? $result->email_name : ''; ?>
								</td>
								<td width="50%">
									<label>Key : </label>
									<?php echo !empty($result->subject) ? $result->subject : ''; ?>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label>Content :</label>
									<?php echo !empty($result->template) ? $result->template : ''; ?>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>