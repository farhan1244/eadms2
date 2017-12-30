<!DOCTYPE html>
<html>


<?php $this->load->view('includes/admin_css.php');?>
<?php if($this->session->flashdata('message')):?>
    <div class="alert alert-success text-center" role="alert"><?= $this->session->flashdata('message')?></div>
<?php endif;?>
<?php $this->load->view('includes/admin_header.php');?>

<?php $this->load->view('includes/admin_navbar.php');?>


<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">



    <div class="content-wrapper">  <!--content-wrapper div starts-->
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= base_url()?>admin-dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?= $results[2]['count']?></h3>

                  <p>Registered Users</p>
                </div>
                <div class="icon">
                  <i class="ion-ios-people-outline"></i>
                </div>
    <!--            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?= $results[3]['count']?></h3>

                  <p>Normal Users</p>
                </div>
                <div class="icon">
                  <i class="ion-ios-body-outline"></i>
                </div>
    <!--            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?= $results[1]['count']?></h3>

                  <p>Zoo Registered</p>
                </div>
                <div class="icon">
                  <i class="ion-ios-paw"></i>
                </div>
    <!--            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-blue">
                <div class="inner">
                  <h3><?= $results[0]['count']?></h3>

                  <p>Volunteer Registered</p>
                </div>
                <div class="icon">
                  <i class="ion-code-working"></i>
                </div>
    <!--            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
              </div>
            </div>
            <!-- ./col -->
          </div>


          <!-- /.row -->
            <div class="row">
                <div id="chartContainer">

                </div>
            </div>
        </section>

    </div>  <!--content-wrapper div ends-->

</div><!--wrapper div ends  -->

<!--Modal For View-->
 <div class="modal modal-info fade" id="modal-show">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">View</h4>
              </div>
              <div class="modal-body">
                <table class="table table-bordered">
                    <tbody id ="showId"></tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
</div>





<?php
$dataPoints = array(
    array("y" => 6, "label" => "Gulshan", "link"=>"http://google.com/", "color"=>"#C70039"),
    array("y" => 4, "label" => "Nagan", "link"=>"http://google.com/", "color"=>"#48E398"),
    array("y" => 5, "label" => "Defence", "link"=>"http://google.com/", "color"=>"#C70039"),
    array("y" => 7, "label" => "Clifton", "link"=>"http://google.com/", "color"=>"#48E398"),
    array("y" => 4, "label" => "North Karachi", "link"=>"http://google.com/", "color"=>"#48E398"),
    array("y" => 6, "label" => "New Karachi", "link"=>"http://google.com/", "color"=>"#48E398")
);
?>



<?php $this->load->view('includes/admin_footer.php');?>

<?php $this->load->view('includes/admin_scripts.php');?>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<script type="text/javascript">

    $(function () {
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: "Latest Zone Charts Data"
            },
            axisY: {
                title: "Percentage",
            },
            data: [
                {
                    type: "column",
                    showInLegend: true,
                    legendMarkerColor: "transparent",
                    legendText: "Zone Names",
                    cursor:"pointer",
                    click: onClick,
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>

                }
            ]
        });
        chart.render();
    });

    function onClick(e){
        window.open(e.dataPoint.link,'_blank');
    };
</script>





</body>
</html>