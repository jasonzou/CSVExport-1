<?php
/**
 * Simple CSV Export item view script
 *
 * Output a single csv file for an Omeka item.
 *
 * @copyright Copyright 2014 UCSC Library Digital Initiatives
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */
include_once (dirname(dirname(dirname(dirname(__FILE__)))) . '/helpers/SimpleCSVExporter.php');

//Headers
$omeka = ['Omeka ID','Omeka URL'];
$dc = SimpleCSVExportPlugin::getElements('Dublin Core');
$it = SimpleCSVExportPlugin::getElements('Item Type Metadata');	
$files = ['Files'];
$tags = ['Tags'];

$headers = array();
//print_r($headers);

//Rows
$multipleItemMetadata = array();
$itemId = "";
foreach( loop( 'item' ) as $item )
{
	$simpleCSVExporter = new SimpleCSVExporter();
	//print_r($item);
	if ($item != null){
        $itemMetadata = $simpleCSVExporter->exportItem($item);
	array_push( $multipleItemMetadata, $itemMetadata );
	$itemID = $item->id;
	}
}
//print_r($multipleItemMetadata);
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="Items.csv"');
echo $simpleCSVExporter->csvHeader($itemID);
    
$data = array_merge($headers,$multipleItemMetadata);
foreach($data as $line){
	echo $line."\r\n";
}
