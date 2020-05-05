
# Xtracta Supplier Reader

## Installation

`composer install`

## Usage

`php cmd.php seed`; Import invoice data

`php cmd.php import`; Import supplier data
> `php cmd.php import 100000`; Import supplier data with additional fake data

`php cmd.php read`; Display available words & detected supplier

## Demo

````
> php cmd.php read
Total suppliers: 1000092
+---------------------------------------------------------------------------------+
| Words                                                                           |
+---------------------------------------------------------------------------------+
| INVOICE                                                                         |
| (PI                                                                             |
| 11083                                                                           |
| software                                                                        |
| Invoice No.                                                                     |
| InnOvallkie Soft SOUtiOrt.5 For Sale 131.4i                                     |
| Account #                                                                       |
| C1006                                                                           |
| Days                                                                            |
| re rieS                                                                         |
| 08.14.2008                                                                      |
| 08.31.2008                                                                      |
| Demo Company                                                                    |
| Due By                                                                          |
| Terms                                                                           |
| Phone : 111.222.3333                                                            |
| Noel                                                                            |
| PO No.                                                                          |
| P01234                                                                          |
| E-Mail : 333.444.4444                                                           |
| 1234 Paid Street                                                                |
| Ashland, KY 41102                                                               |
| Sale Rep                                                                        |
| SalesPerson1                                                                    |
| Web : http://www.ksoftware.net                                                  |
| Bill To Ship To                                                                 |
| Gst Customer Gst Customer                                                       |
| 1234 Paid Street 1234 Paid Street                                               |
| Ashland, KY 41101 Ashland, 41101                                                |
| QTY                                                                             |
| Code Description                                                                |
| Price Line Total                                                                |
| SKU1222                                                                         |
| $10.00                                                                          |
| $10.00                                                                          |
| Gst Import Sale - Description Noel Here                                         |
| 1                                                                               |
| Labor - Example labor item. Quantity is number of hours spent,                  |
| 1.5                                                                             |
| $100.00                                                                         |
| $150.00                                                                         |
| price is hourly rate. Quantity accepts decimal Value                            |
| Noel                                                                            |
| An invoice Noel can go here. Multi-line and even multi-page Noel are supported. |
| Payment Details                                                                 |
| Shipping $10.00 Tax $0.78 Subtotal $160.00                                      |
| UPS Ground Total $170.78                                                        |
| Payments (-) $0.00                                                              |
| Balance Due $170.78                                                             |
| An invoice footer can go here                                                   |
+---------------------------------------------------------------------------------+
+--------------+
| Suppliers    |
+--------------+
| Demo Company |
+--------------+
Reading completed!
````

## License

Licensed under the [MIT license](http://opensource.org/licenses/MIT)