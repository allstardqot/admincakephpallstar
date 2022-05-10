<?php
	use Cake\Core\Configure; 
	use Cake\Routing\Router;
?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>View News</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/news">News List</a></li>
						<li class="breadcrumb-item active">View News</li>
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
									<label>Title : </label>
									<?php echo !empty($result->title) ? $result->title : ''; ?>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label>Local Team : </label>
									<?php echo !empty($result->localteam) ? $result->localteam : ''; ?>
								</td>
							</tr>

                            <tr>
								<td colspan="2">
									<label>Visitor Team : </label>
									<?php echo !empty($result->visitorteam) ? $result->visitorteam : ''; ?>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>