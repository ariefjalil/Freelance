<?php include 'header.php'; ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Appointments</li>
      </ol>
	  <div class="row mb-3">
		<ul class="saasappoint-legend">
			<li class="saasappoint_pending"><span></span> Pending</li>
			<li class="saasappoint_confirmed"><span></span> Confirmed</li>
			<li class="saasappoint_rescheduled_by_customer"><span></span> Rescheduled By Customer</li>
			<li class="saasappoint_cancelled_by_customer"><span></span> Cancelled By Customer</li>
			<li class="saasappoint_rescheduled_by_you"><span></span> Rescheduled By You</li>
			<li class="saasappoint_rejected_by_you"><span></span> Rejected By You</li>
			<li class="saasappoint_completed"><span></span> Completed</li>
		</ul>
	 </div>
	  <div class="mb-3">
		<div id='saasappoint-appointments-calendar'></div>
	 </div>
	 
	 <!-- Manual Booking modal -->
	 <div class="modal fade" id="saasappoint_manual_booking_modal" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Manual Booking</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					<?php include(dirname(__FILE__)."/manual-booking.php"); ?>
				</div>

				<!-- Modal footer -->
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
<?php include 'footer.php'; ?>