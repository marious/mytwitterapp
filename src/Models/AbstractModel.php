<?php
namespace MyApp\Models;

use DB;

class AbstractModel
{
    protected $db;

    public function __construct()
    {
       $this->db = DB::connect();
    }
}