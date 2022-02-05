<?php echo $this->Html->script(['admin/jquery-3.2.1']); ?>
<?php use Cake\Core\Configure; ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Banner</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/banners">Banner</a></li>
                        <li class="breadcrumb-item active">Edit Banner</li>
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
						<?php echo $this->Form->create($banner, ['type' => 'file', 'novalidate' => true,'id'=>'bannerForm']); ?>
						<div class="card-body">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											$type	=	Configure::read('BANNER_TYPE');
											echo $this->Form->input('banner_type', ['escape'=>false,'class' => 'form-control bannerType','options' => $type,'empty'=>'Select Type', 'label' => __('Banner Type <span class="required">*</span>')]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('sequence', ['escape'=>false,'class' => 'form-control','type' => 'text','placeholder'=>'Banner Sequence', 'label' => __('Banner Sequence <span class="required">*</span>')]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<label for="image">Image <span class="required"></span></label>
				                    	<?php echo $this->Form->input('image',['type'=>'file','id'=>'image','class'=>'form-control','label'=>false,'accept'=>'image/*']); ?>
										<small>image size : 600 x 400</small>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 offerType" style="display: none;">
									<div class="form-group">
										<label for="contest-type">Offer <span class="required"></span></label>
				                    	<select name="offer_id" class="form-control offerId" id="contest-type">
				                    		<option value="">Select Offer</option>
				                    		<?php 
				                    		if(!empty($offer)){
				                    			foreach ($offer as $key => $value) { ?>
				                    				<option value="<?= $value->id;?>"><?= $value->coupon_code; ?></option>
				                    			<?php }
				                    		} else {
				                    			echo '<option value="">No Offer found</option>';
				                    		} ?>
				                    	</select>
									</div>
								</div>

								<div class="col-xs-6 col-sm-6 col-md-6 urltype" style="display: none;">
									<div class="form-group">
										<?php
											echo $this->Form->input('url', ['escape'=>false,'class' => 'form-control','type' => 'text','placeholder'=>'Page url', 'label' => __('Page url <span class="required">*</span>')]);
										?>
									</div>
								</div>			

							</div>
							<div class="row matchType" style="display: none;">
								
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
				                    	<label for="contest-type">Match <span class="required"></span></label>
				                    	<select name="match_id" class="form-control match_list" id="match_list">
				                    		<option value="">Match List</option>
				                    	</select>
				                    </div>
								</div>
							</div>
						</div>
		                <div class="card-footer">
		                  <button type="submit" class="btn btn-primary submit">Submit</button>
		                  <?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('action' => 'index'), array('escape' => false, 'class' => 'btn btn-primary '))); ?>
		                </div
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).on('click', '.submit', function() {
		$(this).attr('disabled',true);
		$('#bannerForm').submit();
	});
	
	var banner	=	'<?php echo $banner->banner_type; ?>';
	if(banner == 1) {
		$('.matchType').show();
		$('.offerType').hide();
		$('.urltype').hide();
	}
	if(banner == 2) {
		$('.offerType').hide();
		$('.matchType').hide();
		$('.urltype').hide();
	}
	if(banner == 3) {
		$('.offerType').show();
		$('.matchType').hide();
		$('.urltype').hide();
	}
	if(banner == 4) {
		$('.urltype').show();
		$('.offerType').hide();
		$('.matchType').hide();
	}
	
	var seriesId	=	"<?php echo $banner->series_id; ?>";
	$.ajax({
		url	:	'<?php echo $this->Url->build(['controller' => 'Banners', 'action' => 'getMatch']); ?>',
		type:	'POST',
		data:	{series: seriesId},
		success :function(data) {
			var response	=	jQuery.parseJSON(data);
			$('.match_list').html('<option value="">Match List</option>');
			if(response.status === 'success') {
				var option	=	'';
				$.each(response.data, function(index, value) {
					option	=	'<option value="'+value.match_id+'">'+value.match_name+'</option>';
					$('.match_list').append(option);
				});
			}
		}
	});
	
	$(document).ready(function() {
		$('#series_list').change(function() {
			var series	=	$(this).val();
			$.ajax({
				url	:	'<?php echo $this->Url->build(['controller' => 'Banners', 'action' => 'getMatch']); ?>',
				type:	'POST',
				data:	{series: series},
				success :function(data) {
					var response	=	jQuery.parseJSON(data);
					$('.match_list').html('<option value="">Match List</option>');
					if(response.status === 'success') {
						var option	=	'';
						$.each(response.data, function(index, value) {
							option	=	'<option value="'+value.match_id+'">'+value.match_name+'</option>'
							$('.match_list').append(option);
						});
					}
				}
			});
		});
	});
</script>

