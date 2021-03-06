<?php

class SimpleCSVExport_View_Helper_ItemCSV extends Zend_View_Helper_Abstract
{
  public function __construct()
  {
    //$this->storage = Zend_Registry::get('storage');
  }


  /**
   * Returns csv file for a given single Omeka item
   *
   * @param int $itemID
   *            The ID of the Omeka item
   * @return string $csv The contents of the CSV file
   */
  public function exportItem($itemID)
  {
    ob_start();
    $this->itemCSV($itemID, false);
    return ob_get_clean();
  }

  public function itemCSV( $item, $isExtended = false )
  {  
    
    $itemMetadata = array(
      0   => $item->id,
      1  => WEB_ROOT.'/items/show/'.$item->id,
    );    
    
    $i=2;
    
    /* Dublin Core metadata */
    foreach(SimpleCSVExportPlugin::getElements('Dublin Core') as $element){
      $itemMetadata[$i] = htmlentities( implode('; ',metadata( 'item', array( 'Dublin Core', "$element" ), array( 'all' => true ) ) ));
      $i++;
    }
    
    /* Item Type metadata */
    foreach(SimpleCSVExportPlugin::getElements('Item Type Metadata') as $element){
      $itemMetadata[$i] = htmlentities( implode('; ',metadata( 'item', array( 'Item Type Metadata', "$element" ), array( 'all' => true ) ) ));
      $i++;
    }    

    // Files
    $files = array();
    $fi=0;
    foreach( $item->Files as $file )
      {
        $files[ $fi ] = $file->getWebPath( 'original' );
        $fi++;
      }
  
      if( count( $files ) > 0 ) {
        $files = implode('; ',$files);
      }else{
        $files = null;
    }
    $itemMetadata[ $i ] = $files;
    $i++;
    
    // Tags
    $itemMetadata[ $i ] = tag_string($item,null,', ');

    return $itemMetadata;
  }
}
