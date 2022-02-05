<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Category detail</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/category">Category</a></li>
                        <li class="breadcrumb-item active">Category detail</li>
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
                                    <td><label>Category Name : </label> </td>
                                    <td><?=$category->category_name?></td>
                                </tr>
				                <tr>
				                    <td><label>Description : </label> </td>
                                    <td><?=$category->description; ?></td>
				                </tr>
								<tr>
                                    <td><label>Image : </label></td>
                                    <td>
                                        <?php
                                        if(!empty($category->image)) {
                                            echo '<img src="'.$this->request->webroot.'uploads/category_image/'.$category->image.'" hight="150px" width="150px" alt="">';
                                         }else{
                                             echo 'No Image';
                                        }
                                        ?>
                                    </td>
									
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
                    <div class="card-body">
                    	<div class="col-sm-12" style="margin-bottom: 40px;">
							<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('controller' => 'Category', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-primary'))); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
