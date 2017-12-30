
<script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>

<script src="<?php echo base_url();?>assets/js/jquery-ui.min.js"></script>

<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>

<script src="<?php echo base_url();?>assets/js/daterangepicker.js"></script>

<script src="<?php echo base_url();?>assets/js/bootstrap-datepicker.min.js"></script>

<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>

<script src="<?php echo base_url();?>assets/js/jquery.dataTables.min.js"></script>

<script src="<?php echo base_url();?>assets/js/dataTables.bootstrap.min.js"></script>

<script src="<?php echo base_url();?>assets/js/fastclick.js"></script>

<script src="<?php echo base_url();?>assets/js/adminlte.min.js"></script>

<script src="<?php echo base_url();?>assets/js/demo.js"></script>

<script>
    $('#c_expirydate').datepicker({
        autoclose: true,
        format: 'mm/dd/yyyy',
        startDate: "today",
    });
</script>

<script>
  $(function () {
    $('#example1').DataTable({
        'bSort'       : false,

    });

  });
</script>

<script>
$(document).ready(function(){
    $('#example1').on('click', '.delete_data', function(){
        var idCoupan = $(this).attr('id');
        bootbox.confirm({
            title: "Delete Coupan?",
            message: "Do you want to delete this coupan?",

            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel',
                    className: 'btn-danger'

                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirm',
                    className: 'btn-success'
                }
            },
            callback: function (result) {
                if(result)
                {
                    var BASE_URL = '<?php echo base_url();?>';
                    var full_url = BASE_URL + 'Admin/deleteCoupan';
                    $.ajax({
                        method: 'POST',
                        url: full_url,
                        data: {idCoupan: idCoupan},
                        success: function(data){
                            if(data){
                                console.log('Successfully Delete');
                                $('#'+idCoupan).remove();
                            }
                            else{
                                console.log('Error On Delete');
                            }
                        }
                    });
                }
            }
        });
    });
});
</script>

<script>

$(document).ready(function(){

    $('#example1').on('click', '.view_data', function(){

        var BASE_URL = '<?php echo base_url();?>';
        var full_url = BASE_URL + 'Admin/getCoupan';
        var idCoupan = $(this).attr('id');

        $.ajax({
            method: 'POST',
            url: full_url,
            data: {idCoupan: idCoupan},
            success: function(data){
                var data = jQuery.parseJSON(data);
                //alert(data.name);
                dropdown = $('#dropdown');
                $("#c_id").val(data['coupanData']['t_coupan_id']);
                $("#c_name").val(data['coupanData']['name']);
                $('#c_quantity').val(data['coupanData']['quantity']);
                $('#c_image').attr('src', data['coupanData']['image']);
                $('#c_amount').val(data['coupanData']['totalAmount']);
                $('#c_save').val(data['coupanData']['save']);
                $('#c_expirydate').val(data['coupanData']['expiryDate']);

                dropdown.html('');
                $.each(data['categories'], function(k, v) {
                    dropdown.append('<option value="' + v['t_category_id'] + '">' + v.name + '</option>');
                });

                $('#modal-show').modal("show");
            }
        });
    });
});

</script>

<script>

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#c_image').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<script>
    $(document).ready(function(){
        //var oTable = $('#example1').dataTable();
        $('#updateCoupan').click(function(){

            var stuff = {};
            var BASE_URL = '<?php echo base_url();?>';
            var full_url = BASE_URL + 'Admin/updateCoupan';

            stuff['t_coupan_id']    = document.getElementById("c_id").value;
            stuff['name']           = document.getElementById("c_name").value;
            stuff['coupanQuantity'] = document.getElementById("c_quantity").value;
            stuff['category']       = document.getElementById("dropdown").value;
            stuff['coupanType']     = document.getElementById("c_coupantype").value;
            stuff['featured']       = document.getElementById("c_featured").value;
            stuff['totalAmount']    = document.getElementById("c_amount").value;
            stuff['save']           = document.getElementById("c_save").value;
            stuff['expiryDate']     = document.getElementById("c_expirydate").value;

            $.ajax({
                method: 'POST',
                url: full_url,
                data: stuff,
                success: function(data){
                    console.log("File"+ data);
                    if(data == 1){
                        bootbox.confirm({
                            message: "<h3>Success!<h3> <br><h4>Data Update<h4>",
                            buttons: {
                                confirm: {
                                    label: 'Yes',
                                    className: 'btn-success'
                                }
                            },
                            callback: function (result) {
                                window.location.reload();
                               // $('#example1').reload();
                                
                            }
                        });
                    }
                    else if(data='expireDate'){
                        bootbox.alert("<h3>Error!<h3> <br><h4>Expired Date Must Be A Future's Date<h4>");
                    }
                    else{
                        bootbox.alert("<h3>Error!<h3> <br><h4>Please Fill The Complete Form<h4>");
                    }
                }
            });


        });
    });
</script>
