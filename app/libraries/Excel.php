<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . "vendor/phpoffice/phpexcel/Classes/PHPExcel.php";

class Excel extends PHPExcel
{
    public function __construct()
    {
        parent::__construct();
    }
}
