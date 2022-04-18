<?php
	use Cake\Core\Configure; 
	use Cake\Routing\Router;
?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>View Blog</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/blogs">Blog List</a></li>
						<li class="breadcrumb-item active">View Blog</li>
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
									<label>Content : </label>

									<?php echo !empty($result->description) ? $result->description : ''; ?>
								</td>
							</tr>
                            <tr>
								<td colspan="2">
									<label>Image : </label>
                                    <img class="blogImg" src="<?php echo SITE_URL ?>uploads/blogs/<?php echo $result->image ?>" alt="">
									<?php echo !empty($result->content) ? $result->content : ''; ?>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>