<?php
	use Cake\Core\Configure;	
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Received Notifications</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item active">Received Notifications</li>
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
              <h3 class="card-title">Notification Details</h3>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                          <th>#</th>
                          <th>User</th> 
                          <th>Message</th> 
                          <th>Date</th>
                          <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                        $i = 1;
                        foreach ($record as $key => $value) { ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $value['users']['first_name'].' '.$value['users']['last_name']; ?></td>
                            <td><?php echo $value->message; ?></td>
                            <td><?php echo date('Y-m-d',strtotime($value->created)); ?></td>
                            <td class="center"> 
                              <?php echo $this->Html->link('Delete', ['controller'=>'Notifications','action'=>'delete',$value->id],['escape'=>false,'class'=>'btn btn-danger btn-xs','title'=>'Delete User','onclick'=>"return confirm('Are you sure you want to delete this notification?')"]); ?>
                            </td>
                            
                        </tr>
                        <?php $i++; } ?> 
                    </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/dataTables.bootstrap4.js"></script>
<script type="text/javascript">
    $(function () {
        $("#example1").DataTable();
    });
</script>

