<?php

/* -------------------------------------------------------------------------- */
/*                                FUNCTION                                	  */
/* -------------------------------------------------------------------------- */

/**
 *
 * Return the total amount of tax (percentage)
 * that an item has to pay
 *
 * @param    string $name
 * @param    float $price
 * @return   int 
 *
 */
function calculate_tax(string $name, float $price)
{
	$import_duty = is_item_imported($name);
	$basic_sales_tax = basic_tax($name);

	$total_tax = $import_duty + $basic_sales_tax;

	return $total_tax;
}

/**
 *
 * Verify if an intem is imported 
 * checking if its name contain the word 'imported'
 *
 * @param    string $name
 * @return   int 
 *
 */
function is_item_imported(string $name)
{
	$imported_tax = 5;

	if (strpos($name, 'imported')===false)
	{
		$imported_tax = 0;
	}

	return $imported_tax;
}

/**
 *
 *  If an item is NOT a books, food, or medical
 * 	will apply a basic tax of 10%
 *
 * @param    string $name
 * @return   int 
 *
 */
function basic_tax(string $name)
{
	$basic_tax = 10;

	if(preg_match('(chocolate|chocolates|book|pills)', $name) === 1)
	{
		$basic_tax = 0;
	}

	return $basic_tax;
}


/* -------------------------------------------------------------------------- */
/*                                	MAIN                                	  */
/* -------------------------------------------------------------------------- */

$items = [
	'input1' => [
		[
			'qty' => 2, 
			'name' => 'book', 
			'price' => 12.49
		],
		[
			'qty' => 1, 
			'name' => 'music CD', 
			'price' => 14.99
		],
		[
			'qty' =>1, 
			'name' => 'chocolate bar', 
			'price' => 0.85
		]
	],
	'input2' => [
		[
			'qty' => 1, 
			'name' => 'imported box of chocolates', 
			'price' => 10
		],
		[
			'qty' => 1, 
			'name' => 'imported bottle of perfume', 
			'price' => 47.5
		]
	],
	'input3' => [
		[
			'qty' => 1, 
			'name' => 'imported bottle of perfume', 
			'price' => 27.99
		],
		[
			'qty' => 1, 
			'name' => 'bottle of perfume', 
			'price' => 18.99
		],
		[
			'qty' => 1, 
			'name' => 'packet of headache pills', 
			'price' => 9.75
		],
		[
			'qty' => 3, 
			'name' => 'box of imported chocolates', 
			'price' => 11.25
		],
	],
];


$output = "";
$i = 1;
foreach ($items as $item)
{
	$output .= "Output {$i}:<br>";
	$grand_total = 0;
	$grand_total_tax = 0;

	foreach($item as $single_item)
	{
		$qty = $single_item['qty'];
		$name = $single_item['name'];
		$price = $single_item['price'];

		// Get the total amount of tax per item (percentage)
		$total_tax = calculate_tax($name, $price);
		
		// Store the no formatting value
		$percent_tmp = $total_tax / 100 * $qty * $price;

		// Rounded up to the nearest 0.05
		$round_tax = round($percent_tmp / 0.05) * 0.05; 

		// Formatting the tax with 2 decimal value
		$tax = number_format($round_tax, 2);

		// Get the total price per item 
		$total_price_x_item = number_format($qty * $price + $tax, 2);
		
		// Calculate the grand total and tax
		$grand_total += $total_price_x_item;
		$grand_total_tax += $tax;

		// Adding to output the item
		$output .= "{$qty} {$name}: {$total_price_x_item} <br>";
	}

	// Adding to output the summary
	$output .= "Sales Taxes: ".number_format($grand_total_tax, 2)."<br>";
	$output .= "Total: {$grand_total}";
	$output .= "<br><br>";

	$i++;
}

echo $output;