<?php include 'header.php'; ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Feedbacks</li>
      </ol>
      <!-- Feedback DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-book"></i> Feedback List
		  </div>
        <div class="card-body">
          <div class="table-responsive">
            <table width="100%" cellspacing="0" id="saasappoint_feedback_list_table">
              <thead>
				<tr>
				  <th>Name</th>
				  <th>Email</th>
				  <th>Rating</th>
				  <th>Review</th>
				  <th>Review On</th>
				  <th>Status</th>
				</tr>
			  </thead>
			  <tbody>
			  </tbody>
           </table>
          </div>
        </div>
      </div>
<?php include 'footer.php'; ?>