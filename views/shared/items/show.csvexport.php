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

$item = get_current_record('item');
$itemID = $item->id;

$simpleCSVExporter = new SimpleCSVExporter();

$flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
if (! isset($itemID)) {
    $flashMessenger->addMessage('ERROR: item ID not set', 'error');
} else {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="Item_' . $itemID . '.csv"');
    
    try {
	echo $simpleCSVExporter->csvHeader($itemID);    
        echo $simpleCSVExporter->exportItem($item);
    } catch (Exception $e) {
        $flashMessenger->addMessage($e->getMessage(), 'error');
    }
}
