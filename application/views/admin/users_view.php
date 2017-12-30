<!DOCTYPE html>
<html>


<?php $this->load->view('includes/admin_css.php');?>

<?php $this->load->view('includes/admin_header.php');?>

<?php $this->load->view('includes/admin_navbar.php');?>

<?php
      
      $key1 = $results['keys'][0];
      $key2 = $results['keys'][1];
      $key3 = $results['keys'][2];
      $key4 = $results['keys'][3];
      $key5 = $results['keys'][4];
      unset($results['keys']);
?>

<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

<!--content-wrapper div starts-->

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Users</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
     
      <div class="callout callout-info">
        <h4>Reminder!</h4>
        Instructions for how to use modals are available on the
        <a href="http://getbootstrap.com/javascript/#modals">Bootstrap documentation</a>
      </div>
     
      <!-- Small boxes (Stat box) -->
      
      
      <!-- Main row -->
<!--       data tables is here-->
      <div class="row">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Student Transaction Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?=$key1?></th>
                  <th><?=$key2?></th>
                  <th><?=$key3?></th>
                  <th><?=$key4?></th>
                  <th><?=$key5?></th>
                  <th>Ali Pay</th>
                </tr>
                </thead>
               <tbody>
                  <?php foreach($results as $result):?>
                   <tr>
                  <td><?=$result[$key1]?></td>
                  <td><?=$result[$key2]?></td>
                  <td><?=$result[$key3]?></td>
                  <td><?=$result[$key4]?></td>
                  <td><?=$result[$key5]?></td>
                  <td><a href="" class="btn bg-maroon btn-flat btn-sm">Info</a></td>
                </tr>
                <?php endforeach;?>
               </tbody> 
                <tfoot>
                <tr>
                 <th>Student Name</th>
                  <th>Teacher Name</th>
                  <th>Total Amount</th>
                  <th>Teacher Fee(80%)</th>
                  <th>Merchant Fee(80%)</th>
                  <th>Ali Pay</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
         
      </div>
      
<!--      data table end here-->
      
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
<!--content-wrapper div ends-->
  
    
        

<!--wrapper div ends  -->
</div>












<?php $this->load->view('includes/admin_footer.php');?>

<?php $this->load->view('includes/admin_scripts.php');?>


</body>
</html>