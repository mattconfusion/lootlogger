<?php

namespace LootLogger;
use \League\CLImate\CLImate;

class LootLoggerCli {

  private $climate;
  private $lootLogger;

  public function __construct(){
    $this->climate = new CLImate();
    $this->climate->forceAnsiOn();
    $this->climate->description("LootLogger, a loot log parser really.");
    //$this->climate->clear();
    $this->climate->addArt(__DIR__. DIRECTORY_SEPARATOR);
    return $this;
  }

  public function run(){
    try{
      //definitions of arguments + options
      $this->climate->arguments->add([
        'path' => [
          'description'  => 'CSV file containing the log of the loot.',
          'required'    => true,
        ],
        'partykeyword' => [
          'prefix'      => 'p',
          'longPrefix'  => 'partykeyword',
          'description' => 'The keyword identifying a full party action in the log.',
          'defaultValue' => LootLogger::PARTYKEYWORD
          ]]);

          $this->climate->br()->draw('ll-title');
          $this->climate->arguments->parse();
          $this->lootLogger = new LootLogger($this->climate->arguments->get('path'),$this->climate->arguments->get('partykeyword'));
          $this->climate->whisper('CSV loaded.')->br();
          $results = $this->lootLogger->parseCsv()->getPCsResults();
          //print description
          $this->printDescription($results);
          //print table
          $this->printTable($results);
        }catch(\Exception $e){
          $this->climate->br()->shout("Error! {$e->getMessage()}");
          $this->climate->usage();
        }
      }

      protected function printTable($results){
        $tableArray = array();
        foreach($results as $pcName => $resultsArray){
          $tableArray[] = array('PC'=>$pcName) + $resultsArray;
        }
        $this->climate->br()->table($tableArray);
        unset($tableArray);
      }

      protected function printDescription($results){
        foreach($results as $pcName => $resultsArray){
          $this->climate->inline("<bold><invert>$pcName</invert></bold> - ");
          foreach($resultsArray as $stat=>$value){
            $this->climate->inline("<bold>$stat:</bold> $value; ");
          }
          $this->climate->br();
        }
      }

    }
