<?php

namespace Wateringcart;

use Illuminate\Database\Eloquent\Model;

/**
 * CountryList
 *
 */
class Posts extends Model {

	/**
	 * @var string
	 * The table name
	 */
	protected $table;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
       $this->table = \Config::get('posts.table_name');
    }
  
}
