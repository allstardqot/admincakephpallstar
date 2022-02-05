<?php use Cake\Core\Configure; ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Banner detail</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/banners">Banner</a></li>
                        <li class="breadcrumb-item active">Banner detail</li>
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
                                    <td width="30%"><label>Banner Type : </label> </td>
                                    <td width="70%"><?php echo Configure::read('BANNER_TYPE.'.$banner->banner_type); ?></td>
                                </tr>
								<tr>
                                    <td><label>Image : </label></td>
                                    <td>
										<?php
											$rootPath	=	WWW_ROOT.DS.'uploads'.DS.'banner_image'.DS;
											if(!empty($banner->image) && file_exists($rootPath.$banner->image)) {
												echo $this->Html->image('../uploads/banner_image/'.$banner->image,['alt'=>'Banner Image','style'=>'hight:80px;width:100px;']);
											 } else {
												echo $this->Html->image('no_image.png',['alt'=>'Banner Image','style'=>'hight:100px;width:100px;']);
											}
										?>
                                    </td>
				                </tr>
								<?php if($banner->banner_type == MATCH_TYPE) { ?>
									<tr>
										<td><label>Series : </label> </td>
										<td><?php echo $this->Custom->getSeriesName($banner->series_id); ?></td>
									</tr>
									<tr>
										<td><label>Match : </label> </td>
										<td>
											<?php
												$matches	=	$this->Custom->getsetiesMatch($banner->series_id, $banner->match_id);
												if(!empty($matches)) {
													echo $matches->visitorteam.' vs '.$matches->localteam;
												}
											?>
										</td>
									</tr>
								<?php
								}
								if($banner->banner_type == OFFER_TYPE) { ?>
									<tr>
										<td><label>Offer : </label> </td>
										<td>
											<?php
												$offer	=	$this->Custom->getOffers($banner->offer_id);
												if(!empty($offer)) {
													echo $offer->coupon_code;
												}
											?>
										</td>
									</tr>
								<?php } ?>
								 <tr>
                                    <td><label>Status : </label> </td>
                                    <td>
										<?php $class	=	($banner->status == ACTIVE) ? 'success' : 'danger'; ?>
										<span class="label label-<?php echo $class; ?>">
											<?php echo Configure::read('status.'.$banner->status); ?>
										</span>
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
							<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('controller' => 'Banners', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-primary'))); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
