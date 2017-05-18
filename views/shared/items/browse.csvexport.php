<?php

//Headers
$omeka = ['Omeka ID','Omeka URL'];
$dc = SimpleCSVExportPlugin::getElements('Dublin Core');
$it = SimpleCSVExportPlugin::getElements('Item Type Metadata');	
$files = ['Files'];
$tags = ['Tags'];

$headers = array(array_merge($omeka,$dc,$it,$files,$tags));
//print_r($headers);


//Rows
$multipleItemMetadata = array();
foreach( loop( 'item' ) as $item )
{
	$itemMetadata = $this->itemCSV( $item , false);
	array_push( $multipleItemMetadata, $itemMetadata );
}
//print_r($multipleItemMetadata);

$data = array_merge($headers,$multipleItemMetadata);
//print_r($data);

SimpleCSVExportPlugin::arrayToTable($data);
