<?php include 'c_header.php'; ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/my-appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">My Appointments</li>
      </ol>
	  <div class="row mb-3">
		<ul class="saasappoint-legend">
			<li class="saasappoint_pending"><span></span> Pending</li>
			<li class="saasappoint_confirmed"><span></span> Confirmed</li>
			<li class="saasappoint_rescheduled_by_customer"><span></span> Rescheduled By You</li>
			<li class="saasappoint_cancelled_by_customer"><span></span> Cancelled By You</li>
			<li class="saasappoint_rescheduled_by_you"><span></span> Rescheduled By Admin</li>
			<li class="saasappoint_rejected_by_you"><span></span> Rejected By Admin</li>
			<li class="saasappoint_completed"><span></span> Completed</li>
		</ul>
	 </div>
	  <div class="mb-3">
		<div id='saasappoint-appointments-calendar'></div>
	 </div>
<?php include 'c_footer.php'; ?>