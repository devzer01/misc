<?php
require_once('../extensions/lib/Chargify.php');

$chargify = new ChargifyConnector();

$test = TRUE;
$customer = new ChargifyCustomer(NULL, $test);


/**
 * Create a customer
 */
$new_customer = new ChargifyCustomer(NULL, $test);
$new_customer->first_name = 'Joe';
$new_customer->last_name = 'Smith';
$new_customer->email = 'joe.smith@example.com';
$saved_customer = $new_customer->create();
echo '<h2>Just created a single customer</h2>';
print_r($saved_customer);


/**
 * Get 50 customers at a time.
 */
$customer = new ChargifyCustomer(NULL, $test);
$customers = $customer->getAllCustomers();
echo '<h2>Array of 50 customer objects</h2>';
print_r($customers);


$test = TRUE;
$product = new ChargifyProduct(NULL, $test);


/**
 * Get details about all products available through your Chargify site.
 */
$products = $product->getAllProducts();
echo '<h2>Array of all product objects</h2>';
print_r($products);


/**
 * Get the first product based by ID. Will fail if there are no products.
 */
$product_x = new ChargifyProduct(NULL, $test);
$product_x->id = $products[0]->id;
echo '<h2>Single product object by ID</h2>';
print_r($product_x->getByID());


/**
 * Get the first product based by handle. Will fail if there are no products.
 */
$product_y = new ChargifyProduct(NULL, $test);
$product_y->handle = $products[0]->handle;
echo '<h2>Single product object by handle</h2>';
print_r($product_y->getByHandle());

$customer = new ChargifyCustomer(NULL, $test);
$customer->email = "jane@smith.com";
$customer->first_name = "Jane";
$customer->last_name = "Smith";

$card = new ChargifyCreditCard(NULL, $test);
$card->first_name = "Jane";
$card->last_name = "Smith";
// 1 is used in test mode for a vald credit card.
$card->full_number = '1';
$card->cvv = '123';
$card->expiration_month = "02";
$card->expiration_year = "2013";
$card->billing_address = "123 any st";
$card->billing_city = "Anytown";
$card->billing_state = "CA";
$card->billing_zip = "55555";
$card->billing_country = 'US';

$product = new ChargifyProduct(NULL, $test);
$products = $product->getAllProducts();

$subscription = new ChargifySubscription(NULL, $test);
$subscription->customer_attributes = $customer;
$subscription->credit_card_attributes = $card;
// Uses the first exising product. Replace with a product handle string.
$subscription->product_handle = $products[0]->handle;

echo '<h2>Just created a new subscription</h2>';
try {
	$new_subscription = $subscription->create();
	print_r($new_subscription);
} catch (ChargifyValidationException $cve) {
	// Error handling code.
	echo $cve->getMessage();
}
