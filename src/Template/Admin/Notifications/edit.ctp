<?php
  use Cake\Core\Configure;  
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit & Send Notifications</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/notifications/send">Sent Notifications</a></li>
                        <li class="breadcrumb-item active">Edit & Send Notifications</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
              
            <div class="card-header">
              <h3 class="card-title">Edit & Send notification</h3>
            </div>
            <div class="card-body">
              <?php echo $this->Form->create($noti, ['type' => 'file', 'novalidate' => true,'id'=>'notificationForm']); ?>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card-body">
                      <div class="form-group">
                        <?php
                          echo $this->Form->input('title',['escape'=>false,'class' => 'form-control','label'=>'Title <span class="required">*</span>', 'placeholder' => __('Notification Title')]);
                        ?>
                      </div>

                      <div class="form-group">
                        <label>Notification <span class="required">*</span></label>
                        <?php
                          echo $this->Form->input('notification',['type'=>'textarea','class'=>'form-control','rows'=>'3','escape'=>false,'label'=>false]);
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary submit">Sent</button>
                </div>
              <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    
</div>
<script src="https://cdn.ckeditor.com/4.11.1/standard-all/ckeditor.js"></script>
<script>
  $(document).on('change', '.slctRow', function() {
    $('#showRecords').submit();
  });
  $(document).on('click', '.submit', function() {
    $(this).attr('disabled',true);
    $('#notificationForm').submit();
  });
  
  /*CKEDITOR.replace('notification', {
    allowedContent: true,
    height: 320
  });*/
</script>
