<?php 
include 'header.php';
$time_format = $obj_settings->get_option('saasappoint_time_format');
$time_interval = $obj_settings->get_option('saasappoint_timeslot_interval');
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Schedule</li>
      </ol>
	  <div class="mb-3">
		<div class="pull-left">
			<h4> Manage Weekly Schedule </h4>
		</div>
		<div class="pull-right mb-3">
			<a href="<?php echo SITE_URL; ?>backend/manage-blockoff.php" class="btn btn-info text-white btn-sm"><i class="fa fa-calendar-o"></i> Manage Block Off</a>
		</div>
		<div class="table-responsive">
            <table class="table" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Day</th>
                  <th>Working Day</th>
                  <th>Start Time</th>
                  <th>End Time</th>
                </tr>
              </thead>
              <tbody>
				<?php 
				$day_array = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
				$get_schedule = $obj_schedule->get_schedule();
				while($schedule = mysqli_fetch_array($get_schedule)){ 
					?>
					<tr>
					  <td><?php echo $day_array[$schedule['weekday_id']-1]; ?></td>
					  <td>
						<label class="saasappoint-toggle-switch">
						<input type="checkbox" class="saasappoint-toggle-switch-input saasappoint_change_schedule_status" data-id="<?php echo $schedule['id']; ?>" <?php if($schedule['offday'] == 'N'){ echo "checked"; } ?> />
						  <span class="saasappoint-toggle-switch-slider"></span>
						</label>
					  </td>
					  <td>
						<input type="hidden" class="saasappoint_starttime_dropdown_hidden_<?php echo $schedule['id']; ?>" value="<?php echo $schedule['starttime']; ?>" />
						<select class="form-control selectpicker saasappoint_starttime_dropdown" data-id="<?php echo $schedule['id']; ?>" id="saasappoint_starttime_dropdown_<?php echo $schedule['id']; ?>">
							<?php 
							$schedule_starttime = $schedule['starttime'];
							echo $obj_schedule->generate_slot_dropdown_options($time_interval, $time_format, $schedule_starttime);
							?>
						</select>
					  </td>
					  <td>
						<input type="hidden" class="saasappoint_endtime_dropdown_hidden_<?php echo $schedule['id']; ?>" value="<?php echo $schedule['endtime']; ?>" />
						<select class="form-control selectpicker saasappoint_endtime_dropdown" data-id="<?php echo $schedule['id']; ?>" data-db_endtime="<?php echo $schedule['endtime']; ?>" id="saasappoint_endtime_dropdown_<?php echo $schedule['id']; ?>">
							<?php 
							$schedule_endtime = $schedule['endtime'];
							echo $obj_schedule->generate_slot_dropdown_options($time_interval, $time_format, $schedule_endtime);
							?>
						</select>
					  </td>
					</tr>
					<?php 
				} 
				?>
              </tbody>
            </table>
		</div>
      </div>
<?php include 'footer.php'; ?>