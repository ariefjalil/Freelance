<?php 
/** Set default sessions **/
$_SESSION['saasappoint_mb_cart_items'] = array();
$_SESSION['mb_customer_id'] = "";
$_SESSION['saasappoint_mb_cart_category_id'] = "";
$_SESSION['saasappoint_mb_cart_service_id'] = "";
$_SESSION['saasappoint_mb_cart_datetime'] = "";
$_SESSION['saasappoint_mb_cart_end_datetime'] = "";
$_SESSION['saasappoint_mb_cart_freqdiscount_label'] = "";
$_SESSION['saasappoint_mb_cart_freqdiscount_key'] = "";
$_SESSION['saasappoint_mb_cart_freqdiscount_id'] = "";
$_SESSION['saasappoint_mb_cart_subtotal'] = 0;
$_SESSION['saasappoint_mb_cart_freqdiscount'] = 0;
$_SESSION['saasappoint_mb_cart_coupondiscount'] = 0;
$_SESSION['saasappoint_mb_cart_couponid'] = "";
$_SESSION['saasappoint_mb_cart_tax'] = 0;
$_SESSION['saasappoint_mb_cart_nettotal'] = 0;

/* Include class files */
include(dirname(dirname(__FILE__))."/classes/class_manual_booking.php");
include(dirname(dirname(__FILE__))."/classes/class_customers.php");

/* Create object of classes */
$obj_frontend = new saasappoint_manual_booking();
$obj_frontend->conn = $conn; 
$obj_frontend->business_id = $_SESSION['business_id']; 

$obj_customers = new saasappoint_customers();
$obj_customers->conn = $conn;
$obj_customers->business_id = $_SESSION['business_id'];

$saasappoint_location_selector_status = $obj_settings->get_option("saasappoint_location_selector_status"); 
$all_customers = $obj_customers->get_business_customers();
$all_categories = $obj_frontend->get_all_categories(); 
?>

	<div class="saasappoint-mb">
		<section class="saasappoint-booking-detail-block saasappoint-center-block saasappoint-main-block-before">
			<div class="container">
				<div class="row">
					<div class="col-md-12 saasappoint-set-sm-fit mb-4">
						<div class="saasappoint-booking-detail-main">
							<div class="saasappoint-radio-group-block saasappoint-company-services-blocks">
								<div class="saasappoint-radio-group-block-content saasappoint-no-border-bottom">
									<h4>Jenis Servis?</h4>
								</div>
								<?php 
								$i=0;
								$total_cat = mysqli_num_rows($all_categories);
								if($total_cat>0){
									while($category = mysqli_fetch_array($all_categories)){ 
										$i++;
										if($i==1){
											echo '<div class="row">';
										} 
										?>
										<div class="col-xs-12 col-md-4 saasappoint-sm-box">
											<div class="saasappoint-styled-radio">
												<input type="radio" id="saasappoint-categories-radio-<?php echo $category['id']; ?>" value="<?php echo $category['id']; ?>" name="saasappoint-categories-radio" class="saasappoint-categories-radio-change">
												<label for="saasappoint-categories-radio-<?php echo $category['id']; ?>"><?php echo ucwords($category['cat_name']); ?></label>
											</div>
										</div>
										<?php 
										if($i==3){
											echo "</div>";
											$i=0;
										}
										if($total_cat==$i && $i!=3){
											echo "</div>";
										}
									}
								}else{ 
									?>
									<div class="row">
										<div class="col-xs-12 col-md-12 saasappoint-sm-box">
											<label>Sila tetapkan Servis di Halaman Admin</label>
										</div>
									</div>
									<?php 
								} 
								?>
							</div>
							<div class="row saasappoint_show_hide_services">
								<div class="col-md-12">
									<div class="saasappoint-radio-group-block-content saasappoint-no-border-bottom">
										<h4>Package Servis :-</h4>
									</div>
								</div>
							</div>
							<div class="saasappoint-radio-group-block saasappoint-no-border-bottom saasappoint-mb-minus2 saasappoint_show_hide_services">
								<div id="saasappoint_services_html_content" class="row">
									<!-- services will go here -->
								</div>
							</div>
							<div class="row saasappoint-mb-minus4 saasappoint_show_hide_addons">
								<div class="col-md-12">
									<div class="saasappoint-radio-group-block-content saasappoint-no-border-bottom">
										<h4>Tambahan Servis :-</h4>
									</div>
								</div>
							</div>
							<div id="saasappoint_multipleqty_addon_html_content" class="saasappoint_show_hide_addons">
								<!-- multipleqty addons will go here -->
							</div>
							<div class="saasappoint-radio-group-block mt-4 saasappoint_show_hide_addons">
								<div class="row">
									<div class="col-md-12">
										<ul id="saasappoint_singleqty_addon_html_content" class="saasappoint-addons-singleqty-items d-flex flex-wrap">
											<!-- singleqty addons will go here -->
										</ul>
									</div>
								</div>
							</div>
							<div class="saasappoint-radio-group-block mt-4 show_hide_frequently_discount">
								<p>How often would you like cleaning?</p>
								<div id="saasappoint_frequently_discount_content" class="show_hide_frequently_discount">
									<!-- frequently discount will go here -->
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="saasappoint-radio-group-block-content">
										<h4>Pilih Masa Yang Anda Inginkan :-</h4>
									</div>
								</div>
							</div>
							<div class="row pt-0">
								<div class="col-md-12">
									<div class="saasappoint-inline-calendar">
										<div class="saasappoint-inline-calendar-container saasappoint-inline-calendar-container-boxshadow">
											<center><h3>Sila Tunggu...</h3></center>
										</div>
										<div class="saasappoint-inline-calendar-container-boxshadow saasappoint_selected_slot_detail pl-5 pr-5 pb-2 pt-3 text-center">
											
										</div>
									</div>
									<input type="hidden" id="saasappoint_time_slots_selection_date" value="" />
									<input type="hidden" id="saasappoint_time_slots_selection_starttime" value="" />
									<input type="hidden" id="saasappoint_time_slots_selection_endtime" value="" />
									<input type="hidden" id="saasappoint_fdate" value="" />
									<input type="hidden" id="saasappoint_fstime" value="" />
									<input type="hidden" id="saasappoint_fetime" value="" />
								</div>
							</div>
							<?php 
							$useremail = "";
							$userpassword = "";
							$userfirstname = "";
							$userlastname = "";
							$userzip = "";
							$userphone = "";
							$useraddress = "";
							$usercity = "";
							$userstate = "";
							$usercountry = "";
							?>
							<div class="row">
								<div class="col-md-12">
									<div class="saasappoint-radio-group-block-content saasappoint-no-border-bottom">
										<h4>Personal information</h4>
										<div class="saasappoint-users-selection-div">
											<input type="radio" class="saasappoint-user-selection" id="saasappoint-existing-user" name="saasappoint-user-selection" checked value="ec" />
											<label class="saasappoint-user-selection-label" for="saasappoint-existing-user">Mempunyai Account</label>

											<input type="radio" class="saasappoint-user-selection" id="saasappoint-new-user" name="saasappoint-user-selection" value="nc" />
											<label class="saasappoint-user-selection-label" for="saasappoint-new-user">Pelanggan Baru</label>
											
											<input type="radio" class="saasappoint-user-selection" id="saasappoint-guest-user" name="saasappoint-user-selection" value="gc" />
											<label class="saasappoint-user-selection-label" for="saasappoint-guest-user">Pelawat</label>
										</div>
										<div class="saasappoint-logout-div mt-2">
											<label>You selected <b class="saasappoint_loggedin_name"></b>. <a href="javascript:void(0)" id="saasappoint_change_customer_btn">Change Customer?</a></label>
										</div>
									</div>
								</div>
							</div>
							<div class="saasappoint-radio-group-block mt24" id="saasappoint-existing-user-box">
								<div class="row">
									<div class="col-md-12">
										<select class="form-control" name="saasappoint_existing_customer_selection" id="saasappoint_existing_customer_selection">
											<option value="0" disabled selected>Select Customer</option>
											<?php 
											if(mysqli_num_rows($all_customers)>0){ 
												while($customer = mysqli_fetch_array($all_customers)){ 
													?>
													<option value="<?php echo $customer['id']; ?>"><?php echo ucwords($customer['firstname']." ".$customer["lastname"])." [".$customer["email"]."]"; ?></option>
													<?php 
												}
											} 
											?>
										</select>
									</div>
								</div>
							</div>
							<form method="post" name="saasappoint_user_detail_form" id="saasappoint_user_detail_form">
								<div class="saasappoint-radio-group-block mt24" id="saasappoint-new-user-box">
									<div class="row saasappoint_hide_after_login">
										<div class="col-md-6">
											<div class="saasappoint-input-class-div">
												<input type="hidden" id="saasappoint_user_customer_id" name="saasappoint_user_customer_id" value="">
												<input type="email" id="saasappoint_user_email" name="saasappoint_user_email" placeholder="Email Address" value="<?php echo $useremail; ?>" class="saasappoint-input-class">
											</div>
										</div>
										<div class="col-md-6">
											<div class="saasappoint-input-class-div">
												<input type="password" id="saasappoint_user_password" name="saasappoint_user_password" placeholder="Password" value="<?php echo $userpassword; ?>" class="saasappoint-input-class">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_user_firstname" name="saasappoint_user_firstname" placeholder="First Name" value="<?php echo $userfirstname; ?>" class="saasappoint-input-class">
											</div>
										</div>
										<div class="col-md-6">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_user_lastname" name="saasappoint_user_lastname" placeholder="Last Name" value="<?php echo $userlastname; ?>" class="saasappoint-input-class">
											</div>
										</div>
									</div>
									<div class="row">
										<?php 
										$show_zip_input = "";
										$show_phone_div = "6";
										if($saasappoint_location_selector_status == "N" || $saasappoint_location_selector_status == ""){ 
											$show_zip_input= "saasappoint_hide";
											$show_phone_div= "12";
										}
										?>
										<div class="col-md-<?php echo $show_phone_div; ?>">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_user_phone" name="saasappoint_user_phone" placeholder="Phone Number" value="<?php echo $userphone; ?>" class="saasappoint-input-class">
											</div>
										</div>
										<div class="col-md-6 <?php echo $show_zip_input; ?>">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_user_zip" name="saasappoint_user_zip" placeholder="Zip" value="<?php echo $userzip; ?>" class="saasappoint-input-class">
											</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col-md-12">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_user_address" name="saasappoint_user_address" placeholder="Address" value="<?php echo $useraddress; ?>" class="saasappoint-input-class">
											</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col-md-4">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_user_city" name="saasappoint_user_city" placeholder="City" value="<?php echo $usercity; ?>" class="saasappoint-input-class">
											</div>
										</div>
										<div class="col-md-4">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_user_state" name="saasappoint_user_state" placeholder="State" value="<?php echo $userstate; ?>" class="saasappoint-input-class">
											</div>
										</div>
										<div class="col-md-4">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_user_country" name="saasappoint_user_country" placeholder="Country" value="<?php echo $usercountry; ?>" class="saasappoint-input-class">
											</div>
										</div>
									</div>
								</div>
							</form>
							<form method="post" name="saasappoint_guestuser_detail_form" id="saasappoint_guestuser_detail_form">
								<div class="saasappoint-radio-group-block mt24" id="saasappoint-guest-user-box">
									<div class="row">
										<div class="col-md-6">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_guest_firstname" name="saasappoint_guest_firstname" placeholder="First Name" class="saasappoint-input-class">
											</div>
										</div>
										<div class="col-md-6">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_guest_lastname" name="saasappoint_guest_lastname" placeholder="Last Name" class="saasappoint-input-class">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_guest_email" name="saasappoint_guest_email" placeholder="Email Address" class="saasappoint-input-class">
											</div>
										</div>
										<div class="col-md-6">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_guest_phone" name="saasappoint_guest_phone" placeholder="Phone Number" class="saasappoint-input-class">
											</div>
										</div>
									</div>
									<div class="row mt-3">
										<?php 
										$show_gzip_input = "";
										$show_gaddress_div = "9";
										if($saasappoint_location_selector_status == "N" || $saasappoint_location_selector_status == ""){ 
											$show_gzip_input= "saasappoint_hide";
											$show_gaddress_div= "12";
										}
										?>
										<div class="col-md-<?php echo $show_gaddress_div; ?>">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_guest_address" name="saasappoint_guest_address" placeholder="Address" class="saasappoint-input-class">
											</div>
										</div>
										<div class="col-md-3 <?php echo $show_gzip_input; ?>">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_guest_zip" name="saasappoint_guest_zip" placeholder="Zip" class="saasappoint-input-class">
											</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col-md-4">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_guest_city" name="saasappoint_guest_city" placeholder="City" class="saasappoint-input-class">
											</div>
										</div>
										<div class="col-md-4">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_guest_state" name="saasappoint_guest_state" placeholder="State" class="saasappoint-input-class">
											</div>
										</div>
										<div class="col-md-4">
											<div class="saasappoint-input-class-div">
												<input type="text" id="saasappoint_guest_country" name="saasappoint_guest_country" placeholder="Country" class="saasappoint-input-class">
											</div>
										</div>
									</div>
								</div>
							</form>
							<div class="saasappoint-radio-group-block">
								<div class="row mt-4">
									<div class="col-md-12">
										<button id="saasappoint_book_appointment_btn" class="btn btn-block saasappoint-big-block-btn" type="submit"><span class="fa fa-calendar-check-o"></span>Book Now</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 saasappoint-set-sm-fit">
						<div>
							<div class="saasappoint-sidebar-block-title">
								<h4>Booking Summary</h4>
							</div>
							<div id="saasappoint_refresh_cart" class="saasappoint-sidebar-block-content">
								<label>No items in cart</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>