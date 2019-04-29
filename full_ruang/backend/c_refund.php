<?php 
include 'c_header.php'; 
$obj_refund_request->customer_id = $_SESSION["customer_id"];
$all_refund_requests = $obj_refund_request->readall_refund_request_detail_for_customer(); 

$saasappoint_currency_symbol = $obj_settings->get_option('saasappoint_currency_symbol');
$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
$time_format = $obj_settings->get_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
} 
$saasappoint_datetime_format = $saasappoint_date_format." ".$saasappoint_time_format; 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/my-appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Pengembalian Wang</li>
      </ol>
	  <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-exchange"></i> Jumlah Pengembalian Wang
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="saasappoint_refund_request_list_table" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th>#</th>
				  <th>Nama Syarikat</th>
				  <th>Alamat Emel Bisnes</th>
				  <th>Nombor Telefon Bisnes</th>
				  <th>Maklumat Tempahan</th>
				  <th>Jumlah Yang Dikembalikan</th>
				  <th>Dimohon pada</th>
				  <th>Status</th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				if(mysqli_num_rows($all_refund_requests)>0){
					while($refund_request = mysqli_fetch_array($all_refund_requests)){ 
						$obj_settings->business_id = $refund_request['business_id'];
						$saasappoint_currency_symbol = $obj_settings->get_option('saasappoint_currency_symbol');
						$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
						$time_format = $obj_settings->get_option('saasappoint_time_format');
						if($time_format == "24"){
							$saasappoint_time_format = "H:i";
						}else{
							$saasappoint_time_format = "h:i A";
						}
						$saasappoint_datetime_format = $saasappoint_date_format." ".$saasappoint_time_format;
						$appointment = $obj_refund_request->get_appointment_detail_by_order_id($refund_request["order_id"]); 
						?>
						<tr>
						  <td><?php echo $refund_request['order_id']; ?></td>
						  <td><?php echo ucwords($obj_settings->get_option('saasappoint_company_name')); ?></td>
						  <td><?php echo $obj_settings->get_option('saasappoint_company_email'); ?> </td>
						  <td><?php echo $obj_settings->get_option('saasappoint_company_phone'); ?> </td>
						  <td>
							<?php echo ucwords($appointment['cat_name']." - ".$appointment['title'])." on ".date($saasappoint_datetime_format, strtotime($appointment["booking_datetime"]));  
							?>
						  </td>
						  <td><?php echo $saasappoint_currency_symbol.$refund_request['amount']; ?></td>
						  <td><?php echo date($saasappoint_datetime_format, strtotime($refund_request['requested_on'])); ?></td>
						  <td><?php if($refund_request['status'] == "refunded"){ ?><label class="text-success"><?php echo ucwords($refund_request['status']); ?></label><?php }else if($refund_request['status'] == "cancelled_by_admin"){ ?><label class="text-primary"><?php echo "Cancelled by Admin"; ?></label><?php }else if($refund_request['status'] == "cancelled_by_customer"){ ?><label class="text-danger"><?php echo "Cancelled by You"; ?></label><?php }else{ ?><label class="text-primary"><?php echo ucwords($refund_request['status']); ?></label><?php } ?></td>
						</tr>
						<?php 
					}
				} 
				?>
			  </tbody>
           </table>
          </div>
        </div>
      </div>
<?php include 'c_footer.php'; ?>