--
-- Database: `logic_invoice`
--

-- --------------------------------------------------------

--
-- Table structure for table `li_account`
--

CREATE TABLE IF NOT EXISTS `li_account` (
  `account_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(32) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `retained_earnings` tinyint(1) NOT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `li_account`
--

INSERT INTO `li_account` (`account_id`, `name`, `description`, `type`, `parent_id`, `status`, `retained_earnings`) VALUES
(2100, 'Accounts Payable', '', 'liability', 0, 1, 0),
(1100, 'Cash', '', 'current_asset', 0, 1, 0),
(1200, 'Accounts Receivable', '', 'current_asset', 0, 1, 0),
(5300, 'Tax Expense', '', 'expense', 0, 1, 0),
(2200, 'Unearned Revenue', '', 'liability', 0, 1, 0),
(3200, 'Retained Earnings', 'Do not use. Do not delete. Do not disable.', 'equity', 0, 1, 1),
(3100, 'Share Capital', '', 'equity', 0, 1, 0),
(5201, 'Sales Staff', '', 'expense', 5200, 1, 0),
(5100, 'Office Equipment', '', 'expense', 0, 1, 0),
(1300, 'Office Equipment', '', 'fixed_asset', 0, 1, 0),
(5200, 'Salaries', '', 'expense', 0, 1, 0),
(4100, 'Shop Sales', '', 'sale', 0, 1, 0),
(5202, 'Finance Staff', '', 'expense', 5200, 1, 0),
(4200, 'Online Sales', '', 'revenue', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `li_activity`
--

CREATE TABLE IF NOT EXISTS `li_activity` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`activity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_article`
--

CREATE TABLE IF NOT EXISTS `li_article` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `top` tinyint(1) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `sort_order` int(3) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `li_article`
--

INSERT INTO `li_article` (`article_id`, `top`, `parent_id`, `sort_order`, `status`) VALUES
(1, 1, 0, 0, 1),
(2, 1, 1, 1, 1),
(3, 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `li_article_description`
--

CREATE TABLE IF NOT EXISTS `li_article_description` (
  `article_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`article_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `li_article_description`
--

INSERT INTO `li_article_description` (`article_id`, `language_id`, `title`, `description`) VALUES
(2, 1, 'History', '&lt;h2&gt;History&lt;/h2&gt;My Company was founded in 2000.&lt;br&gt;'),
(3, 1, 'How to Pay', '&lt;h2&gt;How to Pay&lt;/h2&gt;Please login to pay for your invoices. You can login by clicking the ''login'' link and proceed to the ''my invoices'' page to view the invoices you have. There, you can make payment for the invoices you have.&lt;br&gt;'),
(1, 1, 'About Us', '&lt;p&gt;&lt;h2&gt;About Us&lt;/h2&gt;My Company specialises in software and web applications. Founded in \r\n2000, My Company has grown to a team of 20 developers over the years. We\r\n love the web, and we are dedicated to bring out the best in all our \r\nwork.&lt;/p&gt;&lt;p&gt;We provide many other services such as office networking \r\nand mobile application development. You can get in touch with us through\r\n the ''contact us'' link above.&lt;/p&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `li_blog_category`
--

CREATE TABLE IF NOT EXISTS `li_blog_category` (
  `blog_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `sort_order` int(3) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`blog_category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `li_blog_category`
--

INSERT INTO `li_blog_category` (`blog_category_id`, `parent_id`, `sort_order`, `status`) VALUES
(1, 0, 0, 1),
(2, 1, 0, 1),
(3, 2, 0, 1),
(4, 1, 0, 1),
(5, 0, 0, 1),
(6, 5, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `li_blog_category_description`
--

CREATE TABLE IF NOT EXISTS `li_blog_category_description` (
  `blog_category_description_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  PRIMARY KEY (`blog_category_description_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `li_blog_category_description`
--

INSERT INTO `li_blog_category_description` (`blog_category_description_id`, `blog_category_id`, `language_id`, `name`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
(4, 1, 1, 'Tech News', 'Tech News', '', ''),
(5, 2, 1, 'Mobile', 'Mobile', '', ''),
(6, 3, 1, '4 Inch', '4 Inch', '', ''),
(7, 4, 1, 'Desktop', 'Desktop', '', ''),
(8, 5, 1, 'Company News', 'Company News', '', ''),
(9, 6, 1, 'New Products', 'New Products', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `li_blog_post`
--

CREATE TABLE IF NOT EXISTS `li_blog_post` (
  `blog_post_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `view` int(11) NOT NULL,
  `sort_order` int(3) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`blog_post_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `li_blog_post`
--

INSERT INTO `li_blog_post` (`blog_post_id`, `user_id`, `view`, `sort_order`, `status`, `date_added`, `date_modified`) VALUES
(1, 1, 0, 0, 1, '2015-06-02 15:11:28', '2016-03-29 14:17:42'),
(2, 1, 1, 0, 1, '2016-03-29 14:20:01', '2016-03-29 14:20:01');

-- --------------------------------------------------------

--
-- Table structure for table `li_blog_post_description`
--

CREATE TABLE IF NOT EXISTS `li_blog_post_description` (
  `blog_post_description_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_post_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `short_description` text NOT NULL,
  `description` text NOT NULL,
  `tag` text NOT NULL,
  PRIMARY KEY (`blog_post_description_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `li_blog_post_description`
--

INSERT INTO `li_blog_post_description` (`blog_post_description_id`, `blog_post_id`, `language_id`, `image`, `title`, `meta_title`, `meta_description`, `meta_keyword`, `short_description`, `description`, `tag`) VALUES
(2, 1, 1, '', 'My First Post', 'My First Post', '', '', 'Hello world, this is my first blog post.&lt;br&gt;', '&lt;p&gt;Hello world, this is my first blog post.&lt;/p&gt;&lt;p&gt;&lt;br&gt;Hello everyone and thank you for reading our first post on this blog.&lt;br&gt;&lt;/p&gt;', 'invoice, logic invoice'),
(3, 2, 1, '', 'Cool Phone 2.0', 'Cool Phone 2.0', '', '', 'We present to you, Cool Phone 2.0!&lt;br&gt;', 'Cool Phone 2.0 is My Company latest mobile phone. Read on to find out more details.&lt;br&gt;', '');

-- --------------------------------------------------------

--
-- Table structure for table `li_blog_post_to_blog_category`
--

CREATE TABLE IF NOT EXISTS `li_blog_post_to_blog_category` (
  `blog_post_id` int(11) NOT NULL,
  `blog_category_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_post_id`,`blog_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `li_blog_post_to_blog_category`
--

INSERT INTO `li_blog_post_to_blog_category` (`blog_post_id`, `blog_category_id`) VALUES
(1, 5),
(2, 1),
(2, 2),
(2, 5),
(2, 6);

--
-- Table structure for table `li_currency`
--

CREATE TABLE IF NOT EXISTS `li_currency` (
  `currency_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `code` varchar(3) NOT NULL,
  `symbol_left` varchar(12) NOT NULL,
  `symbol_right` varchar(12) NOT NULL,
  `decimal_place` char(1) NOT NULL,
  `value` decimal(15,8) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`currency_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `li_currency`
--

INSERT INTO `li_currency` (`currency_id`, `title`, `code`, `symbol_left`, `symbol_right`, `decimal_place`, `value`, `status`, `date_modified`) VALUES
(1, 'Singapore Dollar', 'SGD', 'S$', '', '2', '1.00000000', 1, '2015-01-05 14:22:12'),
(2, 'US Dollar', 'USD', 'US$', '', '2', '0.74820000', 1, '2015-01-05 14:22:12');

-- --------------------------------------------------------

--
-- Table structure for table `li_customer`
--

CREATE TABLE IF NOT EXISTS `li_customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `company` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `email` varchar(96) NOT NULL,
  `salt` varchar(9) NOT NULL,
  `password` varchar(40) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `token` varchar(40) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_customer_credit`
--

CREATE TABLE IF NOT EXISTS `li_customer_credit` (
  `customer_credit_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `description` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`customer_credit_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_customer_ip`
--

CREATE TABLE IF NOT EXISTS `li_customer_ip` (
  `customer_ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`customer_ip_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_email_template`
--

CREATE TABLE IF NOT EXISTS `li_email_template` (
  `email_template_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `priority` int(3) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `email` text NOT NULL,
  PRIMARY KEY (`email_template_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `li_email_template`
--

INSERT INTO `li_email_template` (`email_template_id`, `type`, `priority`, `status`, `email`) VALUES
(1, 'new_customer_admin', 0, 1, ''),
(2, 'new_customer_customer', 0, 1, ''),
(3, 'edit_customer_admin', 0, 1, ''),
(4, 'edit_customer_customer', 0, 1, ''),
(5, 'new_credit_admin', 0, 1, ''),
(6, 'new_credit_customer', 0, 1, ''),
(7, 'new_invoice_admin', 0, 1, ''),
(8, 'new_invoice_customer', 0, 1, ''),
(9, 'edit_invoice_admin', 0, 1, ''),
(10, 'edit_invoice_customer', 0, 1, ''),
(11, 'new_recurring_admin', 0, 1, ''),
(12, 'new_recurring_customer', 0, 1, ''),
(13, 'edit_recurring_admin', 0, 1, ''),
(14, 'edit_recurring_customer', 0, 1, ''),
(15, 'new_transaction_admin', 0, 1, ''),
(16, 'edit_transaction_admin', 0, 1, ''),
(17, 'forgotten_password_admin', 0, 1, ''),
(18, 'forgotten_password_customer', 0, 1, ''),
(19, 'status_5', 0, 1, ''),
(20, 'new_quotation_admin', 0, 1, ''),
(21, 'new_quotation_customer', 0, 1, ''),
(22, 'edit_quotation_admin', 0, 1, ''),
(23, 'edit_quotation_customer', 0, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `li_email_template_description`
--

CREATE TABLE IF NOT EXISTS `li_email_template_description` (
  `email_template_description_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_template_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `html` text NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`email_template_description_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `li_email_template_description`
--

INSERT INTO `li_email_template_description` (`email_template_description_id`, `email_template_id`, `language_id`, `subject`, `html`, `text`) VALUES
(1, 1, 1, '[{website_name}] New Account Registration', '&lt;p&gt;Hi there,&lt;/p&gt;&lt;p&gt;A new account was recently created with the following details:&lt;/p&gt;&lt;p&gt;Customer ID: {customer_id}&lt;br&gt;First Name: {firstname}&lt;br&gt;Last Name: {lastname}&lt;br&gt;Company: {company}&lt;br&gt;Email: {email}&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi there,\r\n\r\nA new account was recently created with the following details:\r\n\r\nCustomer ID: {customer_id}\r\nFirst Name: {firstname}\r\nLast Name: {lastname}\r\nCompany: {company}\r\nEmail: {email}\r\n\r\nRegards,\r\n{website_name}'),
(2, 2, 1, 'Welcome to {website_name}', '&lt;p&gt;Hi {firstname},&lt;/p&gt;&lt;p&gt;Welcome to {website_name}. You may login to your account by clicking &lt;a href=&quot;{website_url}index.php?load=account/login&quot; target=&quot;_blank&quot;&gt;here&lt;/a&gt;.&lt;/p&gt;&lt;p&gt;Your password is {password}.&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;/p&gt;', 'Hi {firstname},\r\n\r\nWelcome to {website_name}. You may login to your account at the following URL: {website_url}index.php?load=account/login\r\n\r\nYour password is {password}.\r\n\r\nRegards,\r\n{website_name}'),
(3, 5, 1, '[{website_name}] Credit Added', '&lt;p&gt;Hi there,&lt;br&gt;&lt;/p&gt;&lt;p&gt;{firstname}''s account was recently credited:&lt;br&gt;&lt;/p&gt;&lt;p&gt;Customer ID: {customer_id}&lt;br&gt;Amount: {amount}&lt;br&gt;Description: {description}&lt;br&gt;Date Added: {date_added}&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi there,\r\n\r\n{firstname}''s account was recently credited:\r\n\r\nCustomer ID: {customer_id}\r\nAmount: {amount}\r\nDescription: {description}\r\nDate Added: {date_added}\r\n\r\nRegards,\r\n{website_name}'),
(4, 6, 1, '[{website_name}] Your have new credits', '&lt;p&gt;Hi {firstname},&lt;/p&gt;&lt;p&gt;We have just added credits to your account.&lt;/p&gt;&lt;p&gt;Amount: {amount}&lt;br&gt;Description: {description}&lt;br&gt;Date Added: {date_added}&lt;/p&gt;&lt;p&gt;You can view all your available credits by clicking &lt;a target=&quot;_blank&quot; href=&quot;{website_url}index.php?load=account/credit&quot;&gt;here&lt;/a&gt;.&lt;br&gt;&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;/p&gt;', 'Hi {firstname},\r\n\r\nWe have just added credits to your account.\r\n\r\nAmount: {amount}\r\nDescription: {description}\r\nDate Added: {date_added}\r\n\r\nYou can view all your available credits by clicking on the following link: {website_url}index.php?load=account/credit\r\n\r\nRegards,\r\n{website_name}'),
(5, 10, 1, '[{website_name}] Invoice #{invoice_id} Updated', '&lt;p&gt;Hi {firstname},&lt;/p&gt;&lt;p&gt;Your invoice #{invoice_id} was recently updated.&lt;/p&gt;&lt;p&gt;Status: {status}&lt;br&gt;Total: {total}&lt;br&gt;Date Issued: {date_issued}&lt;br&gt;Date Due: {date_due}&lt;br&gt;Date Modified: {date_modified}&lt;/p&gt;&lt;p&gt;You can view all your invoices by clicking &lt;a target=&quot;_blank&quot; href=&quot;{website_url}index.php?load=account/invoice&quot;&gt;here&lt;/a&gt;.&lt;br&gt;&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi {firstname},\r\n\r\nYour invoice #{invoice_id} was recently updated.\r\n\r\nStatus: {status}\r\nTotal: {total}\r\nDate Issued: {date_issued}\r\nDate Due: {date_due}\r\nDate Modified: {date_modified}\r\n\r\nYou can view all your invoices by clicking on the following link: {website_url}index.php?load=account/invoice\r\n\r\nRegards,\r\n{website_name}'),
(6, 11, 1, '[{website_name}] New Recurring Payment #{recurring_id}', '&lt;p&gt;Hi there,&lt;br&gt;&lt;/p&gt;&lt;p&gt;Recurring payment #{recurring_id} was recently added with the following details:&lt;/p&gt;&lt;p&gt;Cycle: {cycle}&lt;br&gt;Recurring Payment ID: {recurring_id}&lt;br&gt;Total: {total}&lt;br&gt;Customer ID: {customer_id}&lt;br&gt;First Name: {firstname}&lt;br&gt;Date Added: {date_added}&lt;br&gt;Date Due: {date_due}&lt;br&gt;&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi there,\r\n\r\nRecurring payment #{recurring_id} was recently added with the following details:\r\n\r\nCycle: {cycle}\r\nRecurring Payment ID: {recurring_id}\r\nTotal: {total}\r\nCustomer ID: {customer_id}\r\nFirst Name: {firstname}\r\nDate Added: {date_added}\r\nDate Due: {date_due}\r\n\r\nRegards,\r\n{website_name}'),
(7, 12, 1, '[{website_name}] New Recurring Payment #{recurring_id}', '&lt;p&gt;Hi {firstname},&lt;/p&gt;&lt;p&gt;Recurring payment #{recurring_id} was recently added to your account with the following details:&lt;/p&gt;&lt;p&gt;Cycle: {cycle}&lt;br&gt;Status: {status}&lt;br&gt;Recurring Payment ID: {recurring_id}&lt;br&gt;Total: {total}&lt;br&gt;Date Added: {date_added}&lt;br&gt;Date Due: {date_due}&lt;/p&gt;&lt;p&gt;You can view all your recurring payments by clicking &lt;a target=&quot;_blank&quot; href=&quot;{website_url}index.php?load=account/recurring&quot;&gt;here&lt;/a&gt;.&lt;br&gt;&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi {firstname},\r\n\r\nRecurring payment #{recurring_id} was recently added to your account with the following details:\r\n\r\nCycle: {cycle}\r\nStatus: {status}\r\nRecurring Payment ID: {recurring_id}\r\nTotal: {total}\r\nDate Added: {date_added}\r\nDate Due: {date_due}\r\n\r\nYou can view all your recurring payments by clicking on the following link: {website_url}index.php?load=account/recurring\r\n\r\nRegards,\r\n{website_name}'),
(8, 13, 1, '[{website_name}] Recurring Payment #{recurring_id} Updated', '&lt;p&gt;Hi there,&lt;/p&gt;&lt;p&gt;Recurring payment #{recurring_id} was recently updated.&lt;/p&gt;&lt;p&gt;Cycle: {cycle}&lt;br&gt;Status: {status}&lt;br&gt;Total: {total}&lt;br&gt;Date Added: {date_added}&lt;br&gt;Date Due: {date_due}&lt;br&gt;Date Modified: {date_modified}&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi there,\r\n\r\nRecurring payment #{recurring_id} was recently updated.\r\n\r\nCycle: {cycle}\r\nStatus: {status}\r\nTotal: {total}\r\nDate Added: {date_added}\r\nDate Due: {date_due}\r\nDate Modified: {date_modified}\r\n\r\nRegards,\r\n{website_name}'),
(9, 15, 1, '[{website_name}] New Transaction', '&lt;p&gt;Hi there,&lt;br&gt;&lt;/p&gt;&lt;p&gt;A new transaction was recently added:&lt;/p&gt;&lt;p&gt;Linked Invoice ID: {invoice_id}&lt;br&gt;Date: {date}&lt;br&gt;Date Added: {date_added}&lt;br&gt;Date Modified: {date_modified}&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi there,\r\n\r\nA new transaction was recently added:\r\n\r\nLinked Invoice ID: {invoice_id}\r\nDate: {date}\r\nDate Added: {date_added}\r\nDate Modified: {date_modified}\r\n\r\nRegards,\r\n{website_name}'),
(10, 16, 1, '[{website_name}] Transaction Updated', '&lt;p&gt;Hi there,&lt;br&gt;&lt;/p&gt;&lt;p&gt;Someone recently updated a transaction:&lt;/p&gt;&lt;p&gt;Linked Invoice ID: {invoice_id}&lt;br&gt;Date: {date}&lt;br&gt;Date Added: {date_added}&lt;br&gt;Date Modified: {date_modified}&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi there,\r\n\r\nSomeone recently updated a transaction:\r\n\r\nLinked Invoice ID: {invoice_id}\r\nDate: {date}\r\nDate Added: {date_added}\r\nDate Modified: {date_modified}\r\n\r\nRegards,\r\n{website_name}'),
(11, 3, 1, '[{website_name}] Customer Account Updated', '&lt;p&gt;Hi there,&lt;/p&gt;&lt;p&gt;A customer account was updated with the following details:&lt;/p&gt;&lt;p&gt;Customer ID: {customer_id}&lt;br&gt;First Name: {firstname}&lt;br&gt;Last Name: {lastname}&lt;br&gt;Company: {company}&lt;br&gt;Website: {website}&lt;br&gt;Email: {email}&lt;br&gt;Status: {status}&lt;br&gt;&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi there,\r\n\r\nA customer account was updated with the following details:\r\n\r\nCustomer ID: {customer_id}\r\nFirst Name: {firstname}\r\nLast Name: {lastname}\r\nCompany: {company}\r\nWebsite: {website}\r\nEmail: {email}\r\nStatus: {status}\r\n\r\nRegards,\r\n{website_name}'),
(12, 4, 1, '[{website_name}] Account Updated', '&lt;p&gt;Hi {firstname},&lt;/p&gt;&lt;p&gt;Your account was recently updated with the following details:&lt;br&gt;&lt;/p&gt;&lt;p&gt;First Name: {firstname}&lt;br&gt;Last Name: {lastname}&lt;br&gt;Company: {company}&lt;br&gt;Website: {website}&lt;br&gt;Email: {email}&lt;br&gt;Status: {status}&lt;br&gt;&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;/p&gt;', 'Hi {firstname},\r\n\r\nYour account was recently updated with the following details:\r\n\r\nFirst Name: {firstname}\r\nLast Name: {lastname}\r\nCompany: {company}\r\nWebsite: {website}\r\nEmail: {email}\r\nStatus: {status}\r\n\r\nRegards,\r\n{website_name}'),
(13, 9, 1, '[{website_name}] Invoice #{invoice_id} Updated', '&lt;p&gt;Hi there,&lt;/p&gt;&lt;p&gt;Invoice #{invoice_id} was recently updated.&lt;/p&gt;&lt;p&gt;Status: {status}&lt;br&gt;Total: {total}&lt;br&gt;Date Issued: {date_issued}&lt;br&gt;Date Due: {date_due}&lt;br&gt;Date Modified: {date_modified}&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi there,\r\n\r\nInvoice #{invoice_id} was recently updated.\r\n\r\nStatus: {status}\r\nTotal: {total}\r\nDate Issued: {date_issued}\r\nDate Due: {date_due}\r\nDate Modified: {date_modified}\r\n\r\nRegards,\r\n{website_name}'),
(14, 14, 1, '[{website_name}] Recurring Payment #{recurring_id} Updated', '&lt;p&gt;Hi {firstname},&lt;/p&gt;\r\n&lt;p&gt;Your recurring payment #{recurring_id} was recently updated.&lt;/p&gt;\r\n&lt;p&gt;Cycle: {cycle}&lt;br&gt;Status: {status}&lt;br&gt;Total: {total}&lt;br&gt;Date Added: {date_added}&lt;br&gt;Date Due: {date_due}&lt;br&gt;Date Modified: {date_modified}&lt;/p&gt;\r\n&lt;p&gt;You can view all your recurring payments by clicking &lt;a target=&quot;_blank&quot; href=&quot;{website_url}index.php?load=account/recurring&quot;&gt;here&lt;/a&gt;.&lt;br&gt;&lt;/p&gt;\r\n&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi {firstname},\r\n\r\nYour recurring payment #{recurring_id} was recently updated.\r\n\r\nCycle: {cycle}\r\nStatus: {status}\r\nTotal: {total}\r\nDate Added: {date_added}\r\nDate Due: {date_due}\r\nDate Modified: {date_modified}\r\n\r\nYou can view all your recurring payments by clicking on the following link: {website_url}index.php?load=account/recurring\r\n\r\nRegards,\r\n{website_name}'),
(15, 7, 1, '[{website_name}] New Invoice #{invoice_id}', '&lt;p&gt;Hi there,&lt;br&gt;&lt;/p&gt;&lt;p&gt;Invoice #{invoice_id} was recently added with the following details:&lt;/p&gt;&lt;p&gt;Status: {status}&lt;br&gt;Invoice ID: {invoice_id}&lt;br&gt;Total: {total}&lt;br&gt;Customer ID: {customer_id}&lt;br&gt;First Name: {firstname}&lt;br&gt;Date Added: {date_issued}&lt;br&gt;Date Due: {date_due}&lt;br&gt;&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi there,\r\n\r\nInvoice #{invoice_id} was recently added with the following details:\r\n\r\nStatus: {status}\r\nInvoice ID: {invoice_id}\r\nTotal: {total}\r\nCustomer ID: {customer_id}\r\nFirst Name: {firstname}\r\nDate Added: {date_issued}\r\nDate Due: {date_due}\r\n\r\nRegards,\r\n{website_name}'),
(16, 8, 1, '[{website_name}] New Invoice #{invoice_id}', '&lt;p&gt;Hi {firstname},&lt;/p&gt;&lt;p&gt;Invoice #{invoice_id} was recently added to your account with the following details:&lt;/p&gt;&lt;p&gt;Status: {status}&lt;br&gt;Invoice ID: {invoice_id}&lt;br&gt;Total: {total}&lt;br&gt;Date Issued: {date_issued}&lt;br&gt;Date Due: {date_due}&lt;/p&gt;&lt;p&gt;You can view all your invoices by clicking &lt;a target=&quot;_blank&quot; href=&quot;{website_url}index.php?load=account/invoice&quot;&gt;here&lt;/a&gt;.&lt;br&gt;&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi {firstname},\r\n\r\nInvoice #{invoice_id} was recently added to your account with the following details:\r\n\r\nStatus: {status}\r\nInvoice ID: {invoice_id}\r\nTotal: {total}\r\nDate Issued: {date_issued}\r\nDate Due: {date_due}\r\n\r\nYou can view all your invoices by clicking on the following link: {website_url}index.php?load=account/invoice\r\n\r\nRegards,\r\n{website_name}'),
(17, 17, 1, 'Password Change Request at {website_name}', '&lt;p&gt;Hi there,&lt;/p&gt;&lt;p&gt;Someone recently requested to change your account password. If you made the request, you can change your password by clicking &lt;a target=&quot;_blank&quot; href=&quot;{reset_link}&quot;&gt;here&lt;/a&gt;.&lt;/p&gt;&lt;p&gt;The password reset was requested from {ip}&lt;br&gt;&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi there,\r\n\r\nSomeone recently requested to change your account password. If you made the request, you can change your password by clicking on the following link: {reset_link}\r\n\r\nThe password reset was requested from {ip}\r\n\r\nRegards,\r\n{website_name}'),
(18, 18, 1, 'Password Change Request at {website_name}', '&lt;p&gt;Hi {firstname},&lt;/p&gt;&lt;p&gt;Someone recently requested to change your account password. Your account password has been changed to the following:&lt;/p&gt;&lt;p&gt;{password}&lt;/p&gt;&lt;p&gt;The password change was requested from {ip}. If you did not make the change, please inform us immediately.&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi {firstname},\r\n\r\nSomeone recently requested to change your account password. Your account password has been changed to the following:\r\n\r\n{password}\r\n\r\nThe password change was requested from {ip}. If you did not make the change, please inform us immediately.\r\n\r\nRegards,\r\n{website_name}'),
(19, 19, 1, 'Pending', '&lt;p&gt;Pending invoice&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;{history_comment}&lt;/p&gt;', 'Pending invoice'),
(25, 23, 1, '[{website_name}] Quotation #{quotation_id} Updated', '&lt;p&gt;Hi {firstname},&lt;/p&gt;&lt;p&gt;Your quotation #{quotation_id} was recently updated.&lt;/p&gt;&lt;p&gt;Status: {status}&lt;br&gt;Total: {total}&lt;br&gt;Date Issued: {date_issued}&lt;br&gt;Date Due: {date_due}&lt;br&gt;Date Modified: {date_modified}&lt;/p&gt;&lt;p&gt;You can view all your quotations by clicking &lt;a target=&quot;_blank&quot; href=&quot;{website_url}index.php?load=account/quotation&quot;&gt;here&lt;/a&gt;.&lt;br&gt;&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi {firstname},\r\n\r\nYour quotation #{quotation_id} was recently updated.\r\n\r\nStatus: {status}\r\nTotal: {total}\r\nDate Issued: {date_issued}\r\nDate Due: {date_due}\r\nDate Modified: {date_modified}\r\n\r\nYou can view all your invoices by clicking on the following link: {website_url}index.php?load=account/quotation\r\n\r\nRegards,\r\n{website_name}'),
(26, 20, 1, '[{website_name}] New Quotation #{quotation_id}', '&lt;p&gt;Hi there,&lt;br&gt;&lt;/p&gt;&lt;p&gt;Quotation #{quotation_id} was recently added with the following details:&lt;/p&gt;&lt;p&gt;Status: {status}&lt;br&gt;Quotation ID: {quotation_id}&lt;br&gt;Total: {total}&lt;br&gt;Customer ID: {customer_id}&lt;br&gt;First Name: {firstname}&lt;br&gt;Date Added: {date_issued}&lt;br&gt;Date Due: {date_due}&lt;br&gt;&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi there,\r\n\r\nQuotation #{quotation_id} was recently added with the following details:\r\n\r\nStatus: {status}\r\nQuotation ID: {quotation_id}\r\nTotal: {total}\r\nCustomer ID: {customer_id}\r\nFirst Name: {firstname}\r\nDate Added: {date_issued}\r\nDate Due: {date_due}\r\n\r\nRegards,\r\n{website_name}'),
(28, 22, 1, '[{website_name}] Quotation #{quotation_id} Updated', '&lt;p&gt;Hi there,&lt;/p&gt;&lt;p&gt;Invoice #{quotation_id} was recently updated.&lt;/p&gt;&lt;p&gt;Status: {status}&lt;br&gt;Total: {total}&lt;br&gt;Date Issued: {date_issued}&lt;br&gt;Date Due: {date_due}&lt;br&gt;Date Modified: {date_modified}&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi there,\r\n\r\nQuotation #{quotation_id} was recently updated.\r\n\r\nStatus: {status}\r\nTotal: {total}\r\nDate Issued: {date_issued}\r\nDate Due: {date_due}\r\nDate Modified: {date_modified}\r\n\r\nRegards,\r\n{website_name}'),
(29, 21, 1, '[{website_name}] New Invoice #{invoice_id}', '&lt;p&gt;Hi {firstname},&lt;/p&gt;&lt;p&gt;Invoice #{invoice_id} was recently added to your account with the following details:&lt;/p&gt;&lt;p&gt;Status: {status}&lt;br&gt;Invoice ID: {invoice_id}&lt;br&gt;Total: {total}&lt;br&gt;Date Issued: {date_issued}&lt;br&gt;Date Due: {date_due}&lt;/p&gt;&lt;p&gt;You can view all your invoices by clicking &lt;a target=&quot;_blank&quot; href=&quot;{website_url}index.php?load=account/invoice&quot;&gt;here&lt;/a&gt;.&lt;br&gt;&lt;/p&gt;&lt;p&gt;Regards,&lt;br&gt;{website_name}&lt;br&gt;&lt;/p&gt;', 'Hi {firstname},\r\n\r\nInvoice #{invoice_id} was recently added to your account with the following details:\r\n\r\nStatus: {status}\r\nInvoice ID: {invoice_id}\r\nTotal: {total}\r\nDate Issued: {date_issued}\r\nDate Due: {date_due}\r\n\r\nYou can view all your invoices by clicking on the following link: {website_url}index.php?load=account/invoice\r\n\r\nRegards,\r\n{website_name}');

-- --------------------------------------------------------

--
-- Table structure for table `li_email_template_type`
--

CREATE TABLE IF NOT EXISTS `li_email_template_type` (
  `email_template_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `variables` text NOT NULL,
  PRIMARY KEY (`email_template_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `li_email_template_type`
--

INSERT INTO `li_email_template_type` (`email_template_type_id`, `type`, `variables`) VALUES
(1, 'new_customer_admin', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, status'),
(2, 'new_customer_customer', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, status, password'),
(3, 'edit_customer_admin', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, status'),
(4, 'edit_customer_customer', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, status'),
(5, 'new_credit_admin', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, amount, description, date_added'),
(6, 'new_credit_customer', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, amount, description, date_added'),
(7, 'new_invoice_admin', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, invoice_id, comment, total, status, payment_name, date_issued, date_due, date_modified'),
(8, 'new_invoice_customer', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, invoice_id, comment, total, status, payment_name, date_issued, date_due, date_modified'),
(9, 'edit_invoice_admin', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, invoice_id, comment, total, status, payment_name, date_issued, date_due, date_modified'),
(10, 'edit_invoice_customer', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, invoice_id, comment, total, status, payment_name, date_issued, date_due, date_modified'),
(11, 'new_recurring_admin', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, recurring_id, comment, total, status, cycle, payment_name, date_added, date_due, date_modified'),
(12, 'new_recurring_customer', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, recurring_id, comment, total, status, cycle, payment_name, date_added, date_due, date_modified'),
(13, 'edit_recurring_admin', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, recurring_id, comment, total, status, cycle, payment_name, date_added, date_due, date_modified'),
(14, 'edit_recurring_customer', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, recurring_id, comment, total, status, cycle, payment_name, date_added, date_due, date_modified'),
(15, 'new_transaction_admin', 'website_name, website_url, invoice_id, date, date_added, date_modified'),
(16, 'edit_transaction_admin', 'website_name, website_url, invoice_id, date, date_added, date_modified'),
(17, 'forgotten_password_admin', 'website_name, website_url, email, reset_link, ip'),
(18, 'forgotten_password_customer', 'website_name, website_url, firstname, lastname, email, password, ip'),
(19, 'status', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, invoice_id, comment, history_comment, total, status, payment_name, date_issued, date_due, date_modified'),
(20, 'new_quotation_admin', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, quotation_id, comment, total, status, payment_name, date_issued, date_due, date_modified'),
(21, 'new_quotation_customer', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, quotation_id, comment, total, status, payment_name, date_issued, date_due, date_modified'),
(22, 'edit_quotation_admin', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, quotation_id, comment, total, status, payment_name, date_issued, date_due, date_modified'),
(23, 'edit_quotation_customer', 'website_name, website_url, customer_id, firstname, lastname, company, website, email, quotation_id, comment, total, status, payment_name, date_issued, date_due, date_modified');

-- --------------------------------------------------------

--
-- Table structure for table `li_extension`
--

CREATE TABLE IF NOT EXISTS `li_extension` (
  `extension` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `code` varchar(32) NOT NULL,
  PRIMARY KEY (`extension`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `li_extension`
--

INSERT INTO `li_extension` (`extension`, `type`, `code`) VALUES
(1, 'payment', 'pp_standard'),
(2, 'total', 'total'),
(3, 'total', 'sub_total'),
(4, 'payment', 'cheque'),
(5, 'total', 'tax'),
(6, 'payment', 'bank_transfer'),
(7, 'module', 'contact_form');

-- --------------------------------------------------------

--
-- Table structure for table `li_inventory`
--

CREATE TABLE IF NOT EXISTS `li_inventory` (
  `inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `cost` decimal(15,4) NOT NULL,
  `sell` decimal(15,4) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`inventory_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_invoice`
--

CREATE TABLE IF NOT EXISTS `li_invoice` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `recurring_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `company` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `email` varchar(96) NOT NULL,
  `payment_firstname` varchar(32) NOT NULL,
  `payment_lastname` varchar(32) NOT NULL,
  `payment_company` varchar(128) NOT NULL,
  `payment_address_1` varchar(128) NOT NULL,
  `payment_address_2` varchar(128) NOT NULL,
  `payment_city` varchar(128) NOT NULL,
  `payment_postcode` varchar(10) NOT NULL,
  `payment_country` varchar(128) NOT NULL,
  `payment_zone` varchar(128) NOT NULL,
  `total` decimal(15,4) NOT NULL,
  `payment_code` varchar(255) NOT NULL,
  `payment_name` varchar(255) NOT NULL,
  `payment_description` text NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `currency_value` decimal(15,8) NOT NULL DEFAULT '1.00000000',
  `comment` text NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '0',
  `transaction` tinyint(1) NOT NULL DEFAULT '0',
  `date_due` date NOT NULL DEFAULT '0000-00-00',
  `date_issued` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_invoice_history`
--

CREATE TABLE IF NOT EXISTS `li_invoice_history` (
  `invoice_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`invoice_history_id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_invoice_item`
--

CREATE TABLE IF NOT EXISTS `li_invoice_item` (
  `invoice_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `tax_class_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL,
  `tax` decimal(15,4) NOT NULL,
  `discount` decimal(15,4) NOT NULL,
  PRIMARY KEY (`invoice_item_id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_invoice_total`
--

CREATE TABLE IF NOT EXISTS `li_invoice_total` (
  `invoice_total_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `value` decimal(15,4) NOT NULL,
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY (`invoice_total_id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_language`
--

CREATE TABLE IF NOT EXISTS `li_language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `code` varchar(5) NOT NULL,
  `locale` varchar(255) NOT NULL,
  `image` varchar(64) NOT NULL,
  `sort_order` int(3) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `li_language`
--

INSERT INTO `li_language` (`language_id`, `name`, `code`, `locale`, `image`, `sort_order`, `status`) VALUES
(1, 'English', 'en-gb', 'en_US.UTF-8,en_US,en-gb,english', 'gb.png', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `li_quotation`
--

CREATE TABLE IF NOT EXISTS `li_quotation` (
  `quotation_id` int(11) NOT NULL AUTO_INCREMENT,
  `recurring_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `company` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `email` varchar(96) NOT NULL,
  `total` decimal(15,4) NOT NULL,
  `payment_code` varchar(255) NOT NULL,
  `payment_name` varchar(255) NOT NULL,
  `payment_description` text NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `currency_value` decimal(15,8) NOT NULL DEFAULT '1.00000000',
  `comment` text NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '0',
  `date_due` date NOT NULL,
  `date_issued` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`quotation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_quotation_history`
--

CREATE TABLE IF NOT EXISTS `li_quotation_history` (
  `quotation_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `quotation_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`quotation_history_id`),
  KEY `quotation_id` (`quotation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_quotation_item`
--

CREATE TABLE IF NOT EXISTS `li_quotation_item` (
  `quotation_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `quotation_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `tax_class_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL,
  `tax` decimal(15,4) NOT NULL,
  `discount` decimal(15,4) NOT NULL,
  PRIMARY KEY (`quotation_item_id`),
  KEY `quotation_id` (`quotation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_quotation_total`
--

CREATE TABLE IF NOT EXISTS `li_quotation_total` (
  `quotation_total_id` int(11) NOT NULL AUTO_INCREMENT,
  `quotation_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `value` decimal(15,4) NOT NULL,
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY (`quotation_total_id`),
  KEY `quotation_id` (`quotation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_recurring`
--

CREATE TABLE IF NOT EXISTS `li_recurring` (
  `recurring_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `total` decimal(15,4) NOT NULL,
  `payment_code` varchar(255) NOT NULL,
  `payment_name` varchar(255) NOT NULL,
  `payment_description` text NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `currency_value` decimal(15,8) NOT NULL DEFAULT '1.00000000',
  `comment` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `cycle` varchar(15) NOT NULL,
  `date_due` date NOT NULL DEFAULT '0000-00-00',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`recurring_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_recurring_item`
--

CREATE TABLE IF NOT EXISTS `li_recurring_item` (
  `recurring_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `recurring_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `tax_class_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL,
  `tax` decimal(15,4) NOT NULL,
  `discount` decimal(15,4) NOT NULL,
  PRIMARY KEY (`recurring_item_id`),
  KEY `recurring_id` (`recurring_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_recurring_total`
--

CREATE TABLE IF NOT EXISTS `li_recurring_total` (
  `recurring_total_id` int(11) NOT NULL AUTO_INCREMENT,
  `recurring_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `value` decimal(15,4) NOT NULL,
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY (`recurring_total_id`),
  KEY `recurring_id` (`recurring_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_setting`
--

CREATE TABLE IF NOT EXISTS `li_setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(32) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  `serialized` tinyint(1) NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;

--
-- Dumping data for table `li_setting`
--

INSERT INTO `li_setting` (`setting_id`, `group`, `key`, `value`, `serialized`) VALUES
(7, 'bank_transfer', 'bank_transfer_details', '{"1":"Please make transfer to the following:\\r\\n\\r\\nBank: My Bank\\r\\nAccount No.: 1234-5678-90"}', 1),
(11, 'total', 'total_status', '1', 0),
(12, 'total', 'total_sort_order', '9', 0),
(13, 'sub_total', 'sub_total_sort_order', '1', 0),
(264, 'config', 'config_google_analytics', '', 0),
(263, 'config', 'config_cron_user_id', '2', 0),
(21, 'pp_standard', 'pp_standard_voided', '6', 0),
(22, 'pp_standard', 'pp_standard_reversed', '6', 0),
(23, 'pp_standard', 'pp_standard_refunded', '6', 0),
(24, 'pp_standard', 'pp_standard_processed', '5', 0),
(25, 'pp_standard', 'pp_standard_pending', '5', 0),
(26, 'pp_standard', 'pp_standard_failed', '1', 0),
(27, 'pp_standard', 'pp_standard_expired', '5', 0),
(28, 'pp_standard', 'pp_standard_denied', '5', 0),
(29, 'pp_standard', 'pp_standard_completed', '4', 0),
(30, 'pp_standard', 'pp_standard_cancelled', '1', 0),
(31, 'pp_standard', 'pp_standard_debug', '0', 0),
(262, 'config', 'config_error_filename', 'error.log', 0),
(36, 'sub_total', 'sub_total_status', '1', 0),
(37, 'cheque', 'cheque_completed_status_id', '5', 0),
(261, 'config', 'config_error_log', '1', 0),
(260, 'config', 'config_error_display', '0', 0),
(259, 'config', 'config_cache', 'file', 0),
(258, 'config', 'config_compression', '0', 0),
(255, 'config', 'config_secure', '0', 0),
(44, 'cheque', 'cheque_details', '{"1":"Please send cheque to\\r\\n\\r\\n1 Test Street\\r\\nSingapore 123456"}', 1),
(45, 'cheque', 'cheque_payable', 'My Company Ltd', 0),
(46, 'tax', 'tax_status', '1', 0),
(47, 'tax', 'tax_sort_order', '3', 0),
(256, 'config', 'config_seo_url', '0', 0),
(257, 'config', 'config_maintenance', '0', 0),
(254, 'config', 'config_mail', '{"protocol":"mail","parameter":"","smtp_hostname":"","smtp_username":"","smtp_password":"","smtp_port":"25","smtp_timeout":"5"}', 1),
(252, 'config', 'config_recurring_disable_days', '21', 0),
(253, 'config', 'config_recurring_default_status', '5', 0),
(240, 'config', 'config_financial_year', '31/12', 0),
(241, 'config', 'config_auto_update_currency', '0', 0),
(251, 'config', 'config_recurring_invoice_days', '14', 0),
(250, 'config', 'config_default_void_status', '6', 0),
(249, 'config', 'config_default_overdue_status', '3', 0),
(248, 'config', 'config_void_status', '["6"]', 1),
(247, 'config', 'config_pending_status', '["1","5"]', 1),
(246, 'config', 'config_paid_status', '["4"]', 1),
(245, 'config', 'config_overdue_status', '["3"]', 1),
(244, 'config', 'config_draft_status', '["2"]', 1),
(243, 'config', 'config_invoice_void_days', '7', 0),
(242, 'config', 'config_invoice_prefix', 'INV-', 0),
(238, 'config', 'files', '', 0),
(239, 'config', 'config_currency', 'SGD', 0),
(237, 'config', 'config_home', '{"1":"&lt;div class=&quot;header&quot;&gt;\\r\\n  &lt;div class=&quot;container&quot;&gt;\\r\\n    &lt;h1&gt;My Company&lt;br&gt;&lt;\\/h1&gt;\\r\\n  &lt;\\/div&gt;\\r\\n&lt;\\/div&gt;\\r\\n\\r\\n&lt;div id=&quot;content&quot; class=&quot;container&quot;&gt;\\r\\n&lt;h2&gt;&lt;br&gt;&lt;\\/h2&gt;&lt;h2&gt;Welcome&lt;\\/h2&gt;\\r\\nMy Company specialises in software and web applications. Founded in 2000, My Company has grown to a team of 20 developers over the years. We love the web, and we are dedicated to bring out the best in all our work.&lt;br&gt;&lt;br&gt;We provide many other services such as office networking and mobile application development. You can get in touch with us through the ''contact us'' link above.&lt;br&gt;&lt;\\/div&gt;"}', 1),
(233, 'config', 'config_forgotten_application', '1', 0),
(234, 'config', 'config_registration', '1', 0),
(235, 'config', 'config_meta_title', '{"1":"My Company Website"}', 1),
(236, 'config', 'config_meta_description', '{"1":"My Company specialises in software and web applications. Powered by Logic Invoice."}', 1),
(232, 'config', 'config_forgotten_admin', '1', 0),
(231, 'config', 'config_language', 'en-gb', 0),
(230, 'config', 'config_admin_language', 'en', 0),
(54, 'pp_standard', 'pp_standard_transaction', 'sale', 0),
(55, 'pp_standard', 'pp_standard_sandbox', '1', 0),
(56, 'pp_standard', 'pp_standard_email', 'test@example.com', 0),
(57, 'cheque', 'cheque_status', '1', 0),
(228, 'config', 'config_limit_admin', '20', 0),
(229, 'config', 'config_limit_application', '10', 0),
(227, 'config', 'config_icon', 'upload/favi.png', 0),
(226, 'config', 'config_logo', 'upload/logo.png', 0),
(224, 'config', 'config_fax', '', 0),
(225, 'config', 'config_theme', 'default', 0),
(223, 'config', 'config_telephone', '1234 5678', 0),
(221, 'config', 'config_address', '1 Test Street\r\nSingapore 123 456', 0),
(222, 'config', 'config_email', 'test@example.com', 0),
(69, 'cheque', 'cheque_sort_order', '0', 0),
(70, 'bank_transfer', 'bank_transfer_completed_status_id', '5', 0),
(71, 'bank_transfer', 'bank_transfer_status', '1', 0),
(72, 'bank_transfer', 'bank_transfer_sort_order', '0', 0),
(73, 'pp_standard', 'pp_standard_status', '1', 0),
(74, 'pp_standard', 'pp_standard_sort_order', '0', 0),
(219, 'config', 'config_name', 'My Company', 0),
(77, 'contact_form', 'contact_form_receiving_email', 'test@example.com', 0),
(78, 'contact_form', 'contact_form_description', '{"1":""}', 1),
(79, 'contact_form', 'contact_form_status', '1', 0),
(220, 'config', 'config_registered_name', 'My Company Ltd', 0),
(265, 'config', 'config_quotation_void_days', '7', 0),
(266, 'config', 'config_quotation_prefix', 'QT-', 0) ;

-- --------------------------------------------------------

--
-- Table structure for table `li_status`
--

CREATE TABLE IF NOT EXISTS `li_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`status_id`,`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `li_status`
--

INSERT INTO `li_status` (`status_id`, `language_id`, `name`) VALUES
(1, 1, 'Approved'),
(2, 1, 'Draft'),
(3, 1, 'Overdue'),
(4, 1, 'Paid'),
(5, 1, 'Pending'),
(6, 1, 'Void');

-- --------------------------------------------------------

--
-- Table structure for table `li_tax_class`
--

CREATE TABLE IF NOT EXISTS `li_tax_class` (
  `tax_class_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`tax_class_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `li_tax_class`
--

INSERT INTO `li_tax_class` (`tax_class_id`, `name`, `description`) VALUES
(1, 'GST Taxable', '7% GST'),
(4, 'VAT Taxable', '20% VAT');

-- --------------------------------------------------------

--
-- Table structure for table `li_tax_rate`
--

CREATE TABLE IF NOT EXISTS `li_tax_rate` (
  `tax_rate_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `rate` decimal(15,4) NOT NULL,
  `type` char(1) NOT NULL,
  PRIMARY KEY (`tax_rate_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `li_tax_rate`
--

INSERT INTO `li_tax_rate` (`tax_rate_id`, `name`, `rate`, `type`) VALUES
(1, 'GST', '7.0000', 'P'),
(2, 'VAT', '20.0000', 'P');

-- --------------------------------------------------------

--
-- Table structure for table `li_tax_rate_to_tax_class`
--

CREATE TABLE IF NOT EXISTS `li_tax_rate_to_tax_class` (
  `tax_rate_to_tax_class_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_rate_id` int(11) NOT NULL,
  `tax_class_id` int(11) NOT NULL,
  `priority` int(5) NOT NULL,
  PRIMARY KEY (`tax_rate_to_tax_class_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `li_tax_rate_to_tax_class`
--

INSERT INTO `li_tax_rate_to_tax_class` (`tax_rate_to_tax_class_id`, `tax_rate_id`, `tax_class_id`, `priority`) VALUES
(4, 1, 1, 0),
(5, 2, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `li_transaction`
--

CREATE TABLE IF NOT EXISTS `li_transaction` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `currency_value` decimal(15,8) NOT NULL,
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_transaction_account`
--

CREATE TABLE IF NOT EXISTS `li_transaction_account` (
  `transaction_account_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `debit` decimal(15,4) NOT NULL,
  `credit` decimal(15,4) NOT NULL,
  PRIMARY KEY (`transaction_account_id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `account_id` (`account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `li_url_alias`
--

CREATE TABLE IF NOT EXISTS `li_url_alias` (
  `url_alias_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `query` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`url_alias_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `li_url_alias`
--

INSERT INTO `li_url_alias` (`url_alias_id`, `language_id`, `query`, `keyword`) VALUES
(1, 1, 'article_id=1', 'about-us'),
(2, 1, 'article_id=3', 'how-to-pay'),
(3, 1, 'article_id=2', 'history'),
(4, 1, 'blog_category_id=1', 'tech-news'),
(5, 1, 'blog_category_id=2', 'mobile'),
(6, 1, 'blog_category_id=3', '4-inch'),
(7, 1, 'blog_category_id=4', 'desktop'),
(8, 1, 'blog_category_id=5', 'company-news'),
(9, 1, 'blog_category_id=6', 'new-products'),
(10, 1, 'blog_post_id=2', 'cool-phone-20');

-- --------------------------------------------------------

--
-- Table structure for table `li_user`
--

CREATE TABLE IF NOT EXISTS `li_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) NOT NULL,
  `key` varchar(64) NOT NULL,
  `secret` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(96) NOT NULL,
  `username` varchar(32) NOT NULL,
  `salt` varchar(9) NOT NULL,
  `password` varchar(40) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `code` varchar(40) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `li_user`
--

-- --------------------------------------------------------

--
-- Table structure for table `li_user_group`
--

CREATE TABLE IF NOT EXISTS `li_user_group` (
  `user_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `permission` text NOT NULL,
  PRIMARY KEY (`user_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `li_user_group`
--

INSERT INTO `li_user_group` (`user_group_id`, `name`, `permission`) VALUES
(1, 'Top Administrator', '{"access":["accounting\\/account","accounting\\/currency","accounting\\/inventory","accounting\\/journal","accounting\\/tax_class","accounting\\/tax_rate","billing\\/customer","billing\\/invoice","billing\\/quotation","billing\\/recurring","common\\/dashboard","content\\/article","content\\/blog_category","content\\/blog_post","content\\/email_template","extension\\/module","extension\\/payment","extension\\/total","report\\/chart_of_accounts","report\\/invoice","report\\/recurring","report\\/sci","report\\/sfp","system\\/activity","system\\/error","system\\/filemanager","system\\/language","system\\/setting","system\\/status","system\\/user","system\\/user_group","module\\/contact_form","payment\\/bank_transfer","payment\\/cheque","payment\\/pp_standard","total\\/sub_total","total\\/tax","total\\/total"],"modify":["accounting\\/account","accounting\\/currency","accounting\\/inventory","accounting\\/journal","accounting\\/tax_class","accounting\\/tax_rate","billing\\/customer","billing\\/invoice","billing\\/quotation","billing\\/recurring","common\\/dashboard","content\\/article","content\\/blog_category","content\\/blog_post","content\\/email_template","extension\\/module","extension\\/payment","extension\\/total","report\\/chart_of_accounts","report\\/invoice","report\\/recurring","report\\/sci","report\\/sfp","system\\/activity","system\\/error","system\\/filemanager","system\\/language","system\\/setting","system\\/status","system\\/user","system\\/user_group","module\\/contact_form","payment\\/bank_transfer","payment\\/cheque","payment\\/pp_standard","total\\/sub_total","total\\/tax","total\\/total"]}'),
(2, 'System', '');
