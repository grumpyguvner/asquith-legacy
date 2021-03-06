# OPENCART UPGRADE SCRIPT v1.5.x
# WWW.OPENCART.COM
# Qphoria

# THIS UPGRADE ONLY APPLIES TO PREVIOUS 1.5.x VERSIONS. DO NOT RUN THIS SCRIPT IF UPGRADING FROM v1.4.x 

# DO NOT RUN THIS ENTIRE FILE MANUALLY THROUGH PHPMYADMIN OR OTHER MYSQL DB TOOL
# THIS FILE IS GENERATED FOR USE WITH THE UPGRADE.PHP SCRIPT LOCATED IN THE INSTALL FOLDER
# THE UPGRADE.PHP SCRIPT IS DESIGNED TO VERIFY THE TABLES BEFORE EXECUTING WHICH PREVENTS ERRORS

# IF YOU NEED TO MANUALLY RUN THEN YOU CAN DO IT BY INDIVIDUAL VERSIONS. EACH SECTION IS LABELED.
# BE SURE YOU CHANGE THE PREFIX "oc_" TO YOUR PREFIX OR REMOVE IT IF NOT USING A PREFIX

#### START 1.5.1

ALTER TABLE `oc_affiliate` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_affiliate` MODIFY `approved` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_banner` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_category` MODIFY `top` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_category` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_country` MODIFY `postcode_required` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_country` MODIFY `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '';
ALTER TABLE `oc_coupon` MODIFY `logged` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_coupon` MODIFY `shipping` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_coupon` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_currency` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_customer` MODIFY `newsletter` tinyint(1) NOT NULL DEFAULT '0' COMMENT '';
ALTER TABLE `oc_customer`  MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_customer`  MODIFY `approved` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_information` MODIFY `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '';
ALTER TABLE `oc_language` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_order_history` MODIFY `notify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '';
ALTER TABLE `oc_product` MODIFY `shipping` tinyint(1) NOT NULL DEFAULT '1' COMMENT '';
ALTER TABLE `oc_product` MODIFY `subtract` tinyint(1) NOT NULL DEFAULT '1' COMMENT '';
ALTER TABLE `oc_product` MODIFY `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '';
ALTER TABLE `oc_product_option` MODIFY `required` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_product_option_value` MODIFY `subtract` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_return_history` MODIFY `notify` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_return_product` MODIFY `opened` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_review` MODIFY `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '';
ALTER TABLE `oc_user` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_voucher` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '';
ALTER TABLE `oc_zone`  MODIFY `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '';
ALTER TABLE `oc_setting` ADD `serialized` tinyint(1) NOT NULL DEFAULT 0 COMMENT '' AFTER value;


#### START 1.5.1.3

CREATE TABLE IF NOT EXISTS oc_tax_rate_to_customer_group (
    tax_rate_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    customer_group_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    PRIMARY KEY (tax_rate_id, customer_group_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS oc_tax_rule (
    tax_rule_id int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment,
    tax_class_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    tax_rate_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    based varchar(10) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    priority int(5) NOT NULL DEFAULT '1' COMMENT '',
    PRIMARY KEY (tax_rule_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE oc_customer ADD token varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER approved;
ALTER TABLE oc_option_value ADD image varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER option_id;
ALTER TABLE oc_order MODIFY invoice_prefix varchar(26) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin;
ALTER TABLE oc_product_image ADD sort_order int(3) NOT NULL DEFAULT '0' COMMENT '' AFTER image;
ALTER TABLE oc_tax_rate ADD name varchar(32) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER geo_zone_id;
ALTER TABLE oc_tax_rate ADD type char(1) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER rate;
ALTER TABLE oc_tax_rate DROP tax_class_id;
ALTER TABLE oc_tax_rate DROP priority;
ALTER TABLE oc_tax_rate MODIFY rate decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '';
ALTER TABLE oc_tax_rate DROP description;

ALTER TABLE oc_product_tag ADD INDEX product_id (product_id);
ALTER TABLE oc_product_tag ADD INDEX language_id (language_id);
ALTER TABLE oc_product_tag ADD INDEX tag (tag);


#### START 1.5.2


CREATE TABLE IF NOT EXISTS oc_customer_ip_blacklist (
    customer_ip_blacklist_id int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment,
    ip varchar(15) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    PRIMARY KEY (customer_ip_blacklist_id),
    INDEX ip (ip)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS oc_order_fraud (
    order_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    customer_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    country_match varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    country_code varchar(2) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    high_risk_country varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    distance int(11) NOT NULL DEFAULT 0 COMMENT '',
    ip_region varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_city varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_latitude decimal(10,6) NOT NULL DEFAULT '' COMMENT '',
    ip_longitude decimal(10,6) NOT NULL DEFAULT '' COMMENT '',
    ip_isp varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_org varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_asnum int(11) NOT NULL DEFAULT 0 COMMENT '',
    ip_user_type varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_country_confidence varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_region_confidence varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_city_confidence varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_postal_confidence varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_postal_code varchar(10) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_accuracy_radius int(11) NOT NULL DEFAULT 0 COMMENT '',
    ip_net_speed_cell varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_metro_code int(3) NOT NULL DEFAULT 0 COMMENT '',
    ip_area_code int(3) NOT NULL DEFAULT 0 COMMENT '',
    ip_time_zone varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_region_name varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_domain varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_country_name varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_continent_code varchar(2) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ip_corporate_proxy varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    anonymous_proxy varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    proxy_score int(3) NOT NULL DEFAULT 0 COMMENT '',
    is_trans_proxy varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    free_mail varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    carder_email varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    high_risk_username varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    high_risk_password varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    bin_match varchar(10) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    bin_country varchar(2) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    bin_name_match varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    bin_name varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    bin_phone_match varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    bin_phone varchar(32) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    customer_phone_in_billing_location varchar(8) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ship_forward varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    city_postal_match varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    ship_city_postal_match varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    score decimal(10,5) NOT NULL DEFAULT '' COMMENT '',
    explanation text NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    risk_score decimal(10,5) NOT NULL DEFAULT '' COMMENT '',
    queries_remaining int(11) NOT NULL DEFAULT 0 COMMENT '',
    maxmind_id varchar(8) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    error text NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    PRIMARY KEY (order_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS oc_order_voucher (
    order_voucher_id int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment,
    order_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    voucher_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    description varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    code varchar(10) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    from_name varchar(64) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    from_email varchar(96) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    to_name varchar(64) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    to_email varchar(96) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    voucher_theme_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    message text NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    amount decimal(15,4) NOT NULL DEFAULT '' COMMENT '',
    PRIMARY KEY (order_voucher_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE oc_order ADD shipping_code varchar(128) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER shipping_method;
ALTER TABLE oc_order ADD payment_code varchar(128) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER payment_method;
ALTER TABLE oc_order ADD forwarded_ip varchar(15) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER ip;
ALTER TABLE oc_order ADD user_agent varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER forwarded_ip;
ALTER TABLE oc_order ADD accept_language varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER user_agent;
ALTER TABLE oc_order DROP reward;

ALTER TABLE oc_order_product ADD reward int(8) NOT NULL DEFAULT 0 COMMENT '' AFTER tax;

ALTER TABLE oc_product MODIFY `weight` decimal(15,8) NOT NULL DEFAULT '0.00000000' COMMENT '';
ALTER TABLE oc_product MODIFY `length` decimal(15,8) NOT NULL DEFAULT '0.00000000' COMMENT '';
ALTER TABLE oc_product MODIFY `width` decimal(15,8) NOT NULL DEFAULT '0.00000000' COMMENT '';
ALTER TABLE oc_product MODIFY `height` decimal(15,8) NOT NULL DEFAULT '0.00000000' COMMENT '';

ALTER TABLE `oc_return` ADD `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER `order_id`;
ALTER TABLE `oc_return` ADD `product` varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER `telephone`;
ALTER TABLE `oc_return` ADD `model` varchar(64) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER `product`;
ALTER TABLE `oc_return` ADD `quantity` int(4) NOT NULL DEFAULT '0' COMMENT '' AFTER `model`;
ALTER TABLE `oc_return` ADD `opened` tinyint(1) NOT NULL DEFAULT '0' COMMENT '' AFTER `quantity`;
ALTER TABLE `oc_return` ADD `return_reason_id` int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER `opened`;
ALTER TABLE `oc_return` ADD `return_action_id` int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER `return_reason_id`;

DROP TABLE oc_return_product;

ALTER TABLE oc_tax_rate_to_customer_group DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

#### START 1.5.2.1

ALTER TABLE `oc_customer` ADD `recommend_id` int(11) DEFAULT NULL AFTER `token`;
ALTER TABLE `oc_customer` ADD `affiliate_id` int(11) DEFAULT NULL AFTER `recommend_id`;
ALTER TABLE `oc_affiliate` ADD `account_commission` decimal(4,2) NOT NULL DEFAULT '0.00' AFTER `commission`;
ALTER TABLE `oc_affiliate` MODIFY `payment` varchar(10) COLLATE utf8_bin NOT NULL;
ALTER TABLE `oc_affiliate_transaction` ADD `customer_id` int(11) DEFAULT NULL AFTER `affiliate_id`;
ALTER TABLE `oc_order` ADD `recommend_id` int(11) DEFAULT NULL AFTER `order_status_id`;

-- --------------------------------------------------------

--
-- Table structure for table `oc_affiliate_transaction`
--

CREATE TABLE IF NOT EXISTS `oc_recommend` (
  `recommend_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `code` varchar(64) COLLATE utf8_bin NOT NULL,
  `firstname` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `lastname` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `email` varchar(96) COLLATE utf8_bin NOT NULL DEFAULT '',
  `voucher_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`recommend_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

#### START 1.5.2.2

ALTER TABLE `oc_product_image` ADD `video` varchar(255) COLLATE utf8_bin DEFAULT NULL AFTER `image`;