<?php

namespace App;

class Utils 
{
  
    public static function normalizeName($input) {
        $output = str_replace('-', '_', $input);
        $output = str_replace(' ', '', $output);
        $output = iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $output );
        $output = str_replace('\'', '', $output);
         $output = str_replace('`', '', $output);
        $output = strtolower($output);
        return $output;
  }

}
