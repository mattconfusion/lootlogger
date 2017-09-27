<?php

namespace LootLogger;
use \League\CLImate\CLImate;

class LootLoggerCli {

  const OUTPUT_LIST = 'list';
  const OUTPUT_TABLE = 'table';

  private $climate;
  private $lootLogger;

  public function __construct() {
    $this->climate = new CLImate();
    $this->climate->forceAnsiOn();
    $this->climate->description("LootLogger, a loot log parser really.");

    $this->climate->addArt(__DIR__. DIRECTORY_SEPARATOR);
    return $this;
  }

  public function run() {
    try{
      //definitions of arguments + options
      $this->climate->arguments->add([
        'path' => [
          'description'  => 'CSV file containing the log of the loot.',
          'required'     => true,
        ],
        'partykeyword'  => [
          'prefix'       => 'p',
          'longPrefix'   => 'party-keyword',
          'description'  => 'The keyword identifying a full party action in the log.',
          'defaultValue' => LootLogger::PARTYKEYWORD
        ],
        'output' => [
          'prefix'       => 'o',
          'longPrefix'   => 'output',
          'description'  => 'Output format, either '.self::OUTPUT_LIST.' or '.self::OUTPUT_TABLE,
          'defaultValue' => self::OUTPUT_LIST
        ]
        ]);
          //draw title
          $this->climate->br()->green()->draw('ll-title');

          //parse args
          $this->climate->arguments->parse();

          //load CSV
          $this->lootLogger = new LootLogger($this->climate->arguments->get('path'),$this->climate->arguments->get('partykeyword'));
          $this->climate->whisper('CSV loaded.')->br();

          //parse CSV
          $results = $this->lootLogger->parseCsv()->getPCsResults();

          //print results
          $this->printResults($this->climate->arguments->get('output'), $results);

        }catch(\Exception $e) {
          $this->climate->br()->shout("Error! {$e->getMessage()}");
          $this->climate->usage();
        }
      }

      /**
       * Print the results in the specified format
       *
       * @param [string] $outputFormat 'table' or 'list'
       * @param [array] $results
       * @return void
       */
      private function printResults($outputFormat, $results) {
        $outputFormat === self::OUTPUT_TABLE ? $this->printTable($results) : $this->printList($results);
      }

      /**
       * Print a result table from the parsed results
       *
       * @param [array] $results the assoc array of the results
       * @return void
       */
      private function printTable($results) {
        $tableArray = array();
        foreach($results as $pcName => $resultsArray){
          $tableArray[] = array('PC'=>$pcName) + $resultsArray;
        }
        $this->climate->br()->table($tableArray);
        unset($tableArray);
      }

      /**
       * Print a text description of the parsed results
       *
       * @param [array] $results the assoc array of the results
       * @return void
       */
      private function printList($results) {
        foreach($results as $pcName => $resultsArray){
          $this->climate->inline("<yellow>$pcName</yellow> - ");
          foreach($resultsArray as $stat=>$value){
            $this->climate->inline("<green>$stat:</green> $value; ");
          }
          $this->climate->br();
        }
      }
    }
