<?php $this->load->view('includes/admin_css')?>


<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>EarthQuake</b>Predictor</a>
  </div>
  
  
  
  <?php if($this->session->flashdata('message')):?>
      <div class="alert alert-danger" role="alert"><?= $this->session->flashdata('message')?></div>
    <?php endif;?>
    
  
   <?php if(validation_errors()):?>
   <div class="callout callout-danger">
        <h4>Errors</h4>
        <?php echo validation_errors();?>
      </div>
    <?php endif;?>
  <!-- /.login-logo -->
  <div class="login-box-body">
  
    <p class="login-box-msg">Sign in to start your session</p>

    <form action="<?php echo base_url();?>admin-login" method="post">
      <div class="form-group has-feedback">
        <input type="email" name="userEmail" class="form-control" placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="userPassword" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn bg-maroon btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
    </div>
<?php $this->load->view('includes/admin_scripts');?>

</body>



