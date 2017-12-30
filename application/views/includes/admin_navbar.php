<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel" >
        <div class="pull-left image">
          <img src="<?= $this->session->adminImage?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info" style="margin-bottom: 20px;">
          <p><?= $this->session->adminName;?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        
        <li class="active">
          <a href="<?php echo base_url();?>admin-dashboard">
            <i class="fa fa-dashboard"></i> <span>Home</span>
          </a>
        </li>

          <li class="">
              <a href="#">
                  <i class="fa fa-dashboard"></i> <span>Users</span>
              </a>
          </li>

          <li class="">
              <a href="#">
                  <i class="fa fa-dashboard"></i> <span>Report</span>
              </a>
          </li>
    </ul>
    
    </section>
    <!-- /.sidebar -->
  </aside>