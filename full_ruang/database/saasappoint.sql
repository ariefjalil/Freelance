-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_addons`
--

CREATE TABLE IF NOT EXISTS `saasappoint_addons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `rate` double NOT NULL,
  `image` varchar(255) NOT NULL,
  `multiple_qty` enum('Y','N') NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_admins`
--

CREATE TABLE IF NOT EXISTS `saasappoint_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `country` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `points` int(11) NOT NULL,
  `pointstatus` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_appointment_feedback`
--

CREATE TABLE IF NOT EXISTS `saasappoint_appointment_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `rating` varchar(255) NOT NULL,
  `review` text NOT NULL,
  `review_datetime` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_block_off`
--

CREATE TABLE IF NOT EXISTS `saasappoint_block_off` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `pattern` varchar(255) NOT NULL,
  `blockoff_type` enum('fullday','custom') NOT NULL,
  `from_time` time NOT NULL,
  `to_time` time NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_bookings`
--

CREATE TABLE IF NOT EXISTS `saasappoint_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `booking_datetime` datetime NOT NULL,
  `booking_end_datetime` datetime NOT NULL,
  `order_date` date NOT NULL,
  `cat_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `addons` text NOT NULL,
  `booking_status` enum('pending','confirmed','rescheduled_by_customer','rescheduled_by_you','cancelled_by_customer','rejected_by_you','completed') NOT NULL,
  `reschedule_reason` text NOT NULL,
  `reject_reason` text NOT NULL,
  `cancel_reason` text NOT NULL,
  `reminder_status` enum('Y','N') NOT NULL,
  `read_status` enum('R','U') NOT NULL,
  `lastmodified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_businesses`
--

CREATE TABLE IF NOT EXISTS `saasappoint_businesses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_type_id` int(11) NOT NULL,
  `registered_on` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_business_type`
--

CREATE TABLE IF NOT EXISTS `saasappoint_business_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_type` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_categories`
--

CREATE TABLE IF NOT EXISTS `saasappoint_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_coupons`
--

CREATE TABLE IF NOT EXISTS `saasappoint_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `coupon_code` varchar(255) NOT NULL,
  `coupon_type` enum('percentage','flat') NOT NULL,
  `coupon_value` double NOT NULL,
  `coupon_expiry` date NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_customers`
--

CREATE TABLE IF NOT EXISTS `saasappoint_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `country` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `refferral_code` varchar(2000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_customer_orderinfo`
--

CREATE TABLE IF NOT EXISTS `saasappoint_customer_orderinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `c_firstname` varchar(255) NOT NULL,
  `c_lastname` varchar(255) NOT NULL,
  `c_email` varchar(255) NOT NULL,
  `c_phone` varchar(20) NOT NULL,
  `c_address` text NOT NULL,
  `c_city` varchar(255) NOT NULL,
  `c_state` varchar(255) NOT NULL,
  `c_country` varchar(255) NOT NULL,
  `c_zip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_customer_referrals`
--

CREATE TABLE IF NOT EXISTS `saasappoint_customer_referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `ref_customer_id` int(11) NOT NULL,
  `coupon` varchar(255) NOT NULL,
  `discount` double NOT NULL,
  `discount_type` varchar(255) NOT NULL,
  `used` enum('Y','N') NOT NULL,
  `completed` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_feedback`
--

CREATE TABLE IF NOT EXISTS `saasappoint_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `rating` varchar(10) NOT NULL,
  `review` text NOT NULL,
  `review_datetime` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_frequently_discount`
--

CREATE TABLE IF NOT EXISTS `saasappoint_frequently_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `fd_key` varchar(255) NOT NULL,
  `fd_label` varchar(255) NOT NULL,
  `fd_type` enum('percentage','flat') NOT NULL,
  `fd_value` double NOT NULL,
  `fd_description` text NOT NULL,
  `fd_status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_payments`
--

CREATE TABLE IF NOT EXISTS `saasappoint_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `payment_date` varchar(255) NOT NULL,
  `transaction_id` varchar(500) NOT NULL,
  `sub_total` double NOT NULL,
  `discount` double NOT NULL,
  `tax` double NOT NULL,
  `net_total` double NOT NULL,
  `fd_key` varchar(255) NOT NULL,
  `fd_amount` double NOT NULL,
  `lastmodified` datetime NOT NULL,
  `refer_discount` double NOT NULL,
  `refer_discount_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_refund_request`
--

CREATE TABLE IF NOT EXISTS `saasappoint_refund_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `requested_on` datetime NOT NULL,
  `status` varchar(500) NOT NULL,
  `read_status` enum('U','R') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_schedule`
--

CREATE TABLE IF NOT EXISTS `saasappoint_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `week_id` int(11) NOT NULL,
  `weekday_id` int(11) NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  `offday` enum('Y','N') NOT NULL,
  `service_id` varchar(255) NOT NULL,
  `no_of_booking` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_services`
--

CREATE TABLE IF NOT EXISTS `saasappoint_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `duration` varchar(255) NOT NULL,
  `padding_before` varchar(255) NOT NULL,
  `padding_after` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_settings`
--

CREATE TABLE IF NOT EXISTS `saasappoint_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `option_name` text NOT NULL,
  `option_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_sms_plans`
--

CREATE TABLE IF NOT EXISTS `saasappoint_sms_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(256) NOT NULL,
  `plan_rate` double NOT NULL,
  `credit` int(11) NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_sms_subscriptions_history`
--

CREATE TABLE IF NOT EXISTS `saasappoint_sms_subscriptions_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `credit` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `extended_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_subscriptions`
--

CREATE TABLE IF NOT EXISTS `saasappoint_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `transaction_id` varchar(500) NOT NULL,
  `subscribed_on` datetime NOT NULL,
  `expired_on` datetime NOT NULL,
  `joined_on` datetime NOT NULL,
  `renewal` enum('monthly','yearly') NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_subscriptions_history`
--

CREATE TABLE IF NOT EXISTS `saasappoint_subscriptions_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `transaction_id` varchar(500) NOT NULL,
  `subscribed_on` datetime NOT NULL,
  `expired_on` datetime NOT NULL,
  `renewal` enum('monthly','yearly') NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_subscription_plans`
--

CREATE TABLE IF NOT EXISTS `saasappoint_subscription_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(500) NOT NULL,
  `plan_rate` double NOT NULL,
  `plan_period` int(11) NOT NULL,
  `sms_credit` int(11) NOT NULL,
  `plan_features` longtext NOT NULL,
  `renewal_type` enum('monthly','yearly') NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_superadmins`
--

CREATE TABLE IF NOT EXISTS `saasappoint_superadmins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `country` varchar(255) NOT NULL,
  `role` enum('superadmin','sub_superadmin') NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_superadmin_settings`
--

CREATE TABLE IF NOT EXISTS `saasappoint_superadmin_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` text NOT NULL,
  `option_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_support_tickets`
--

CREATE TABLE IF NOT EXISTS `saasappoint_support_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `generated_by_id` int(11) NOT NULL,
  `ticket_title` varchar(1000) NOT NULL,
  `description` longtext NOT NULL,
  `generated_on` datetime NOT NULL,
  `generated_by` enum('admin','customer') NOT NULL,
  `status` enum('active','completed') NOT NULL,
  `read_status` enum('U','R') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_support_ticket_discussions`
--

CREATE TABLE IF NOT EXISTS `saasappoint_support_ticket_discussions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `replied_by_id` int(11) NOT NULL,
  `reply` longtext NOT NULL,
  `replied_on` datetime NOT NULL,
  `replied_by` enum('superadmin','admin','customer') NOT NULL,
  `read_status` enum('U','R') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_templates`
--

CREATE TABLE IF NOT EXISTS `saasappoint_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `template` varchar(255) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `email_content` longtext NOT NULL,
  `sms_content` longtext NOT NULL,
  `template_for` varchar(255) NOT NULL,
  `email_status` enum('Y','N') NOT NULL,
  `sms_status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_used_coupons_by_customer`
--

CREATE TABLE IF NOT EXISTS `saasappoint_used_coupons_by_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `is_expired` enum('Y','N') NOT NULL,
  `used_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saasappoint_used_fd_by_customer`
--

CREATE TABLE IF NOT EXISTS `saasappoint_used_fd_by_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `fd_id` int(11) NOT NULL,
  `is_expired` enum('Y','N') NOT NULL,
  `used_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;