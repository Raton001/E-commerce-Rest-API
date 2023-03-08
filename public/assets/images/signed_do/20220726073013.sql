-- -------------------------------------------------------------
-- TablePlus 4.6.6(422)
--
-- https://tableplus.com/
--
-- Database: leapcom_cms
-- Generation Time: 2022-07-26 11:14:28.2430
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE TABLE `notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `notes` longtext,
  `created_dt` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

INSERT INTO `notes` (`id`, `uuid`, `type`, `title`, `notes`, `created_dt`, `created_by`, `updated_dt`, `updated_by`) VALUES
(1, '390a6e28-78eb-4b39-8f44-7837b7bbc329', 'Quotation', 'Quotation Terms & Condition', '<b>Terms and Conditions\r\n</b><ol><li>This quotation valid for 30 days from the date of the quotation for acceptance.</li><li>Delivery date to be confirm upon buyer’s confirmation / acceptance on the quotation</li><li>Price quoted are in Ringgit Malaysia (RM), exclusive of tax. Any service incurred are subject to SST / GST, wherever applicable.</li><li>The prices, quantities and delivery time stated in any quotation are not binding on #COMPANY_NAME# (company). They are commercial estimates only which company will make reasonable efforts to achieve.</li><li>The price and delivery terms in this quotation are subject to change if buyer wishes to change any terms of this quotation\r\n</li></ol>', '2020-05-29 11:10:53', 1, '2020-07-06 10:24:58', 1),
(2, '22ea1131-388b-460d-bc3d-144dd29bead1', 'Deliveryorder', 'Delivery Order (DO) Terms & Condition', '<p><b>Terms &amp; conditions</b></p><ol><li>This document describes the quantity of goods order by customer</li><li>Upon receipt of the goods, the customer shall examine them for defects without undue delay.</li><li>Written notification of any obvious defects shall be given without undue delay, but no later than within 7 days of receipt of the goods</li><li>At our option, we shall rectify or exchange defective goods complained of correctly and in due time, or shall appropriately reduce the purchase price.</li></ol>', '2020-06-04 15:08:39', 1, '2020-07-08 15:35:21', 8),
(3, '3949cd3c-eb25-41c1-9ad7-91f0c941f83a', 'Invoice', 'Invoice Foot Notes', '<b>Notes</b><br><ol><li>Standard 60-day payment term from month end of the invoice date, unless stated in the invoice payment term section.\r\n</li><li>Payment should be made to #COMPANY_NAME#., payment options include,<ol type=\"a\"><li>Cash or Online transfer to #COMPANY_BANK_NAME# Account #COMPANY_BANK_ACCNO# or	</li><li>Paypal	</li><li>Cheque made payable to #COMPANY_NAME#.  \r\n</li></ol></li><li>Kindly e-mail / fax the bank transfer receipt to #COMPANY_EMAIL# as soon as the payment is made.\r\n</li></ol>', '2020-06-04 15:09:16', 1, '2020-07-09 17:11:39', 8),
(4, '276e35b7-9243-4479-9948-5bdda97a1c98', 'Creditnote', 'Credit Note Terms & Condition', '<p><b>Terms and Conditions</b></p><p><ol><li>These terms and conditions are applicable to Axis valid credit notes (CN).</li><li>CN can be used / off-set through purchase of #COMPANY_NAME#\'s goods; exclusive of tax</li><li>Amounts shown on the CN denominated in Ringgit Malaysia (RM), and is not transferable, replaceable or exchangeable for cash.</li></ol></p>', '2020-06-05 16:21:47', 1, '2021-01-27 18:13:26', 48),
(5, '3db2a4da-13ee-4cd3-8d8c-2c46c9daa41c', 'Debitnote', 'Debit Note Terms & Condition', '<b>Terms and Conditions</b><br><ol><li>This quotation valid for 30 days from the date of the quotation for acceptance.\r\n</li><li>Delivery date to be confirm upon buyer’s confirmation / acceptance on the quotation\r\n</li><li>Price quoted are in Ringgit Malaysia (RM), exclusive of tax. Any service incurred are subject to SST / GST, wherever applicable.\r\n</li><li>The prices, quantities and delivery time stated in any quotation are not binding on #COMPANY_NAME# (company). They are commercial estimates only which company will make reasonable efforts to achieve.\r\n</li><li>The price and delivery terms in this quotation are subject to change if buyer wishes to change any terms of this quotation\r\n</li></ol>', '2020-06-05 16:22:07', 1, '2020-07-06 10:33:36', 1),
(6, 'af63cedb-02d9-413c-9654-1479bcd1bbb3', 'Purchase', 'Purchase Order Terms & Condition', '<p><b>Terms and Conditions</b></p><ol><li>The following terms and conditions (\"Terms\") provide you (\"Seller\") with the guidelines and legal stipulations of your purchase order (\"Order\") with #COMPANY_NAME# (\"Purchaser\") for the goods and/or services that are described on the Order.</li><li><b>Acceptance and terms and conditions</b>:&nbsp;Seller accepts this Order and any amendments by signing the acceptance copy of the Order and returning it to Purchaser promptly.&nbsp;</li><li><b>Price</b>:&nbsp;This Order must be filled with a price. If no price is set forth, the goods or services will be billed at the price last quoted or at the prevailing market price, whichever is lower.&nbsp;</li><li><b>Invoice</b>&nbsp;:&nbsp;Invoices shall be rendered on delivery of goods and/or completion of services; with the Order number. Each invoice must refer to one, and only one, purchase order.</li><li><b>Payment</b>&nbsp;: Payment shall be made on the terms of net 30 days from the date of invoice.</li><li><b>Warranties:</b>&nbsp;Seller represents and warrants that all goods are free of any claim by any third parties and all services are performed in an accepted standards, free from all defects, and fit for particular purposes for which they are acquired.</li></ol>', '2020-06-10 11:08:26', 1, '2021-04-19 11:42:23', 48),
(7, '29f5fc3b-2b5d-4e4f-9d31-017be35480eb', 'Member Tax Invoice', 'Member Tax Invoice Foot Notes', '<p><b>Notes</b><br></p><ol><li>Standard 2-day payment term from the invoice date, unless stated in the invoice payment term section.</li><li>Payment should be made to #COMPANY_NAME#., payment options include,<ol type=\"a\"><li>Cash or Online transfer to #COMPANY_BANK_NAME# Account #COMPANY_BANK_ACCNO# or</li><li>Paypal</li><li>Cheque made payable to #COMPANY_NAME#.</li></ol></li><li>Kindly e-mail / fax the bank transfer receipt to&nbsp;billing@axisnet.asia as soon as the payment is made.</li></ol>', '2021-02-18 15:47:47', 37, '2021-02-18 15:47:47', 37);


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;