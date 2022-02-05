<?php echo $this->Html->script(['admin/jquery-3.2.1']) ?>

<?php use Cake\Core\Configure; ?>
<style type="text/css">
  div#example1_filter {
    display: none;
  }
</style>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Banner List</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Banner List</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content banner-outer">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Banner Type</th> 
                                  <!-- <th>Url</th> --> 
                                  <th>Image</th> 
                                  <th>Sequence</th>
                                  <th>Status</th>
                                  <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($list as $key => $value) {
                                ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
										<td><?php echo Configure::read('BANNER_TYPE.'.$value->banner_type); ?></td>
                                        <td>
											<?php
												$rootPath	=	WWW_ROOT.DS.'uploads'.DS.'banner_image'.DS;
												if(!empty($value->image) && file_exists($rootPath.$value->image)) {
													echo $this->Html->image('../uploads/banner_image/'.$value->image,['alt'=>'Banner Image','style'=>'hight:80px;width:100px;']);
												 } else {
													echo $this->Html->image('no_image.png',['alt'=>'Banner Image','style'=>'hight:100px;width:100px;']);
												}
											?>
										</td>
										<td><?php echo $value->sequence; ?></td>
                                        <td class="center">  
	                                        <?php echo $this->Html->link(($value->status == 1) ? '<span class="label-success label label-default">Active</span>' : '<span class="label-default label label-danger">Inactive</span>', ['prefix' => 'admin', 'controller' => 'Banners', 'action' => 'status', $value->id], ['escape' => false]);  ?>
	                                    </td>
                                        <td class="center">
                                          <?php echo $this->Html->link('Edit', ['controller'=>'Banners','action'=>'edit',$value->id],['escape'=>false,'class'=>'btn btn-success',]); ?>
                                          <?php echo $this->Html->link('View', ['controller'=>'Banners','action'=>'detail',$value->id],['escape'=>false,'class'=>'btn btn-primary',]); ?>
                                          <?php echo $this->Html->link('Delete Banner', ['controller'=>'Banners','action'=>'delete',$value->id],['escape'=>false,'class'=>'btn btn-danger btn-xs','title'=>'Delete User','onclick'=>"return confirm('Are you sure you want to delete this banner?')"]); ?>
	                                    </td>
                                    </tr>
                                <?php $i++; } ?>
                                
                            </tbody>
                        	</table>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script src="<?= SITE_URL; ?>webroot/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/dataTables.bootstrap4.js"></script>
<script type="text/javascript">
    $(function () {
        $("#example1").DataTable();
        
    });
</script>
