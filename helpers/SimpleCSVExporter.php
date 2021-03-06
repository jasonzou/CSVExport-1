<?php

class SimpleCSVExporter
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
  public function exportItem($item)
  {
	  ob_start();
	  //print_r($this->itemCSV($itemID, false));
	  print_r($this->array2string($this->itemCSV($item, false)));

    return ob_get_clean();
  }

  private function _getElements($set){
	  $NS = "";
	  if ($set == "Dublin Core"){
		  $NS = "dc.";
	  }
    $elementSet= get_record('ElementSet',array('name'=>"$set"));
    $elements=array();
    foreach ($elementSet->getElements() as $element){
      $elements[]= $NS . strtolower($element->name);
    }  
    return $elements;  
  }  


  public function array2string($data){
	  $log_a = "";
	  foreach($data as $key => $value){
		  if (is_array($value)){
			  $log_a .= $this->array2string($value);
		  }else{
			  $log_a .= $value . ",";
		  }
	  }
	  return $log_a;
  }


  /**
   * Returns the header of a given single Omeka item
   *
   */
  public function csvHeader($itemID)
  {
    //Headers
    $omeka = ['filename'];
    $dc = $this->_getElements('Dublin Core');
    $it = $this->_getElements('Item Type Metadata');	
    $files = ['Files'];
    $tags = ['Tags'];

    $headers = array(array_merge($omeka,$dc,$it,$files,$tags));
    $string = $this->array2string($headers) . "\r\n";
    return $string;
  }


  public function itemCSV( $item, $isExtended = false )
  {  
    
    $itemMetadata = array(
      0  => WEB_ROOT.'/items/show/'.$item->id,
    );    
    
    $i=1;
    
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
