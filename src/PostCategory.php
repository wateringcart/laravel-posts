<?php

namespace Wateringcart;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * CountryList
 *
 */
class PostCategory extends Model {

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
       $this->table = \Config::get('posts.posts_category_table');
    }
  
    public function getArticleCategory(){
        $where = [
            'type' => '1',
        ];
        return $this->getCategory($where);
    }

    public function getCategoryAll(){
        $where = [
            'type' => '1',
        ];
        return $this->getCategory($where);
    }

    /**
     * 获取分类
     *
     * @param array $where
     * @return mixed
     */
    private function getCategory($where = [])
    {
        $cacheKey = sprintf( 'cache_%_%s_%s', $this->table, date('YmdHis'), json_encode(func_get_args()) );
        if ( ($result = Cache::get($cacheKey) ) === null ) {
            info('cache: '. $this->table .' '. $cacheKey);
            $condition = [];
            if (isset($where['id']) && $where['id']) {
                 $condition['id'] = $where['id'];
            }
            if (isset($where['pid']) && $where['pid']) {
                $condition['pid'] = $where['pid'];
            }
            if (isset($where['type']) && $where['type']) {
                $condition['type'] = $where['type'];
            }
            if (isset($where['name']) && $where['name']) {
                 $condition['name'] = $where['name'];
            }
            if (isset($where['name_en']) && $where['name_en']) {
                $condition['name_en'] = $where['name_en'];
            }
            $condition['status'] = 1;

            $result = self::select('*')->where($condition)->get();
            Cache::put($cacheKey, $result, $minutes=1);
        }

        return $result->toArray();
    }
}
