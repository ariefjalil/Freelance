<?php include 'header.php'; ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Frequently Discount</li>
      </ol>
      <!-- Frequently Discount DataTables Card-->
      <div class="mb-3">
          <div class="table-responsive">
            <table width="100%" class="table" cellspacing="0">
              <thead>
				<tr>
				  <th>Label</th>
				  <th>Type</th>
				  <th>Value</th>
				  <th>Description</th>
				  <th>Status</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody class="saasappoint_frequently_discount_tbody">
				<?php
				$all_frequently_discount = $obj_frequently_discount->get_all_frequently_discount();
				while($frequently_discount = mysqli_fetch_array($all_frequently_discount)){
					?>
					<tr>
					  <td><?php echo $frequently_discount['fd_label']; ?></td>
					  <td><?php echo ucwords($frequently_discount['fd_type']); ?></td>
					  <td><?php if($frequently_discount['fd_type'] == 'flat'){ echo $obj_settings->get_option('saasappoint_currency_symbol').$frequently_discount['fd_value']; }else{ echo $frequently_discount['fd_value'].'%'; } ?></td>
					  <td><?php echo $frequently_discount['fd_description']; ?></td>
					  <td>
						<label class="saasappoint-toggle-switch">
						  <input type="checkbox" class="saasappoint-toggle-switch-input saasappoint_change_fd_status" data-id="<?php echo $frequently_discount['id']; ?>" <?php if($frequently_discount['fd_status'] == 'Y'){ echo 'checked'; } ?> />
						  <span class="saasappoint-toggle-switch-slider"></span>
						</label>
					  </td>
					  <td>
						<a class="btn btn-primary saasappoint-white btn-sm saasappoint-update-fdmodal" data-id="<?php echo $frequently_discount['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a>
					  </td>
					</tr>
					<?php
				}
				?>
			</tbody>
           </table>
          </div>
        </div>
	 <!-- Update Modal-->
	<div class="modal fade" id="saasappoint_update_fd_modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint_update_fd_modal_label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint_update_fd_modal_label">Update Frequently Discount</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body saasappoint_update_fd_modal_body">
			<h2>Please wait...</h2>
		  </div>
		  <div class="modal-footer"> </div>
		</div>
	  </div>
	</div>
<?php include 'footer.php'; ?>