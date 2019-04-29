<?php include 'header.php'; ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Coupons</li>
      </ol>
      <!-- Coupon DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-book"></i> Coupon List
		  <a class="btn btn-success btn-sm saasappoint-white pull-right" data-toggle="modal" data-target="#saasappoint_add_coupon_modal"><i class="fa fa-plus"></i> Add Coupon</a>
		  </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="saasappoint_coupons_table" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th>Coupon Code</th>
				  <th>Coupon Type</th>
				  <th>Coupon Value</th>
				  <th>Expiry Date</th>
				  <th>Coupon Status</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				
			</tbody>
           </table>
          </div>
        </div>
        <div class="card-footer small text-muted"></div>
      </div>
	 <!-- Add Modal-->
	<div class="modal fade" id="saasappoint_add_coupon_modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint_add_coupon_modal_label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint_add_coupon_modal_label">Add Coupon</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="saasappoint_add_coupon_form" id="saasappoint_add_coupon_form" method="post">
			  <div class="form-group">
				<label for="saasappoint_couponcode">Coupon Code</label>
				<input class="form-control" id="saasappoint_couponcode" name="saasappoint_couponcode" type="text" placeholder="Enter Coupon Code" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_coupontype">Coupon Type</label>
				<select class="form-control" id="saasappoint_coupontype" name="saasappoint_coupontype">
				  <option value="percentage">Percentage</option>
				  <option value="flat">Flat</option>
				</select>
			  </div>
			  <div class="form-group">
				<label for="saasappoint_couponvalue">Coupon Value</label>
				<input class="form-control" id="saasappoint_couponvalue" name="saasappoint_couponvalue" type="text" placeholder="Enter Coupon Value" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_couponexpiry">Coupon Expiry</label>
				<input class="form-control" id="saasappoint_couponexpiry" name="saasappoint_couponexpiry" type="date" value="<?php echo date('Y-m-d', $currDateTime_withTZ); ?>" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_couponstatus">Coupon Status</label>
				<div>
					<label class="text-success"><input type="radio" name="saasappoint_couponstatus" class="saasappoint_couponstatus" value="Y" checked> Activate</label> &nbsp; &nbsp;<label class="text-danger"><input type="radio" name="saasappoint_couponstatus" class="saasappoint_couponstatus" value="N"> Deactivate</label>
				</div>
			  </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a class="btn btn-primary add_coupon_btn" href="javascript:void(0);">Add</a>
		  </div>
		</div>
	  </div>
	</div>
	<!-- Update Modal-->
	<div class="modal fade" id="saasappoint_update_coupon_modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint_update_coupon_modal_label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint_update_coupon_modal_label">Update Coupon</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body saasappoint_update_coupon_modal_body">
			<h2>Please wait...</h2>
		  </div>
		  <div class="modal-footer"> </div>
		</div>
	  </div>
	</div>
<?php include 'footer.php'; ?>