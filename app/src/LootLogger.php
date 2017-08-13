<?php

namespace LootLogger;

class LootLogger {

  const PARTYKEYWORD = 'party';

  private $partyKeyword;
  private $pathToCsvFile;
  private $PC_TABLE;
  private $PARTY_ARRAY;

  /**
   * Get the path to csv file, and optionally the "party" keyword
   * @param [type] $pathToCsvFile [description]
   * @param [type] $partyKeyword  [description]
   */
  public function __construct($pathToCsvFile, $partyKeyword = self::PARTYKEYWORD){
    $this->pathToCsvFile = $pathToCsvFile;
    if(isset($partyKeyword)){
      $this->partyKeyword = $partyKeyword;
    }
    return $this;
  }

  /**
   * Parse the CSV and organize the results in an array
   * @return instance of LootLogger
   */
  public function parseCsv(){
    //main loop
    if (($handle = @fopen($this->pathToCsvFile, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
        //skip empty lines in the csv
        if(!array_filter($data)){
            continue;
        }
        $pcs = explode('+', $data[0]);
        foreach($pcs as $pc){
          //set name if pc is found for the first time
          if(!isset($this->PC_TABLE[$pc])){
            $this->PC_TABLE[$pc] = array();
          }
          //set stat value if stat is not found on pc array
          if(!isset($this->PC_TABLE[$pc][$data[1]])){
            $this->PC_TABLE[$pc][$data[1]] = 0;
          }
          $this->PC_TABLE[$pc][$data[1]] += round(intval($data[2])/count($pcs)); //it may involve many pcs!
        }
        unset($pcs);
      }
      fclose($handle);
      //divide Party results
      $this->dividePartyResults();
      //re-sort the array
      ksort($this->PC_TABLE);
      return $this;
    }else{
      throw new \Exception("Unable to open CSV file {$this->pathToCsvFile}");
    }
  }

  /**
   * Divide the results assigned to the party to each other member mentioned
   * @return void
   */
  private function dividePartyResults(){
    if(isset($this->PC_TABLE[$this->partyKeyword])){
      //separate Party stats
      $this->PARTY_ARRAY = $this->PC_TABLE[$this->partyKeyword];
      unset($this->PC_TABLE[$this->partyKeyword]);
      $partyMembers = count($this->PC_TABLE);
      //parse stats and modifiers for PARTY entries and apply to PCs:
      foreach($this->PARTY_ARRAY as $stat=>$value){
        $this->PARTY_ARRAY[$stat] = round($value/$partyMembers);
      }
      //apply them to each PC
      foreach($this->PC_TABLE as $pc => $pcStats){
        foreach($this->PARTY_ARRAY as $stat=>$value){
          if(!isset($this->PC_TABLE[$pc][$stat])){
            $this->PC_TABLE[$pc][$stat] = 0;
          }
          $this->PC_TABLE[$pc][$stat] += $value;
        }
        ksort($this->PC_TABLE[$pc]);
      }
    }
  }

  public function getPCsResults(){
    return $this->PC_TABLE;
  }

}
