<?php
  use Cake\Core\Configure;  
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Send Notifications</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item active">Send Notifications</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content send-outer">
      <div class="r o w">
        <div class="col-md-12">
          <div class="card">
              
            <div class="card-header">
              <h3 class="card-title">Send notification</h3>
            </div>
            <div class="card-body">
              <?php echo $this->Form->create($notification, ['type' => 'file', 'novalidate' => true,'id'=>'notificationForm']); ?>
                <div class="row">
                  <div class="col-md-12">
                   <!--  <div class="card-body"> -->
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
                    <!-- </div> -->
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
    <section class="content">
        <div class="r o w">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Notification Details</h3>
                    </div>
                    <div class="card-body">
                      <form method="post" id="showRecords">
                        <div class="dataTables_length" id="example1_length">
                          <label>Show 
                            <select name="length" aria-controls="example1" class="form-control form-control-sm slctRow">
                              <option value="10" <?php if(isset($limit) && ($limit=='10')){ echo 'selected'; } ?>>10</option>
                              <option value="25" <?php if(isset($limit) && ($limit=='25')){ echo 'selected'; } ?>>25</option>
                              <option value="50" <?php if(isset($limit) && ($limit=='50')){ echo 'selected'; } ?>>50</option>
                              <option value="100" <?php if(isset($limit) && ($limit=='100')){ echo 'selected'; } ?>>100</option>
                            </select> entries
                          </label>
                        </div>
                      </form>
                      <div class="table-responsive">
                        <table  class="table  table-bordered responsive" >
                          <thead>
                            <tr>
                              <!-- <th><input type="checkbox" class="selectall" /></th> -->
                              <th>#</th>
                              <th>Title</th>
                              <th>Notification</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            if (!empty($notifidata)) { 
                              $start = 1;
                              $pl=  $limit;
                              foreach ($notifidata as $key => $value) {  ?>
                                <tr>
                                  <td>
                                    <?php 
                                    if(isset($this->request->params['?']['page'])){
                                        $pc = $this->request->params['?']['page']-1;
                                    }else{
                                        $pc = 0;
                                    }
                                    echo ($pl*$pc)+$start;
                                    ?>
                                  </td>
                                  <td><?php echo h($value->title); ?></td>
                                  <td><?php echo $value->description; ?></td>
                                  <td><?php echo $this->Html->link('Edit', ['controller'=>'Notifications','action'=>'edit',$value->id],['escape'=>false,'class'=>'btn btn-success',]); ?></td>
                                </tr>
                              <?php $start++;
                              }
                            } else { ?>
                              <tr>
                                <td colspan="9" style="text-align: center;"><?php echo __('No Record Found') ?></td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                      <?php echo $this->element('Admin/pagination'); ?>
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
