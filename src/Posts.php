<?php

namespace Wateringcart;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Cache;
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
       $this->table = \Config::get('posts.posts_table');
    }

    public function getListWithPaginate($where = [], $page = 0)
    {
        $cacheKey = sprintf( 'cache_%_%s_%s', $this->table, date('YmdHis'), json_encode(func_get_args()) );
        if ( ($result = Cache::get($cacheKey) ) === null ) {
            info('cache: ' . $cacheKey);
            // $result Array(
            //     [per_page] => 15
            //     [current_page] => 1
            //     [next_page_url] => http://wang123.app/link?page=2
            //     [prev_page_url] => 
            //     [from] => 1
            //     [to] => 15
            //     [data] => Array()
            // )
            $condition = [];
            // if (isset($where['area']) && $where['area']) {
            //     $condition['area'] = $where['area'];
            // }
            if (isset($where['category']) && $where['category']) {
                $condition['category'] = $where['category'];
            }
            $condition['status'] = 1;

            if (isset($where['select']) && $where['select']) {
                $select = $where['select'];
            } else {
                $select = '*';
            }

            $result = self::select( DB::raw($select) )
                ->where($condition)
                ->orderBy('id', 'desc')
                ->simplePaginate();

            Cache::put($cacheKey, $result, $minutes=1);
        }

        return $result->toArray();
    }

    /**
     * 获取列表数据
     *
     * @param Collection $where
     * @param int $limit
     * @return mixed
     */
    public function getList(Collection $where, $limit = 10) : Collection
    {
        $cacheKey = sprintf( 'cache_%_%s_%s', $this->table, date('YmdHis'), json_encode(func_get_args()) );
        if ( ($result = Cache::get($cacheKey) ) === null ) {
            info('cache: ' . $cacheKey);
            $condition = [];
            // if (isset($where['area']) && $where['area']) {
            //     $condition['area'] = $where['area'];
            // }
            if (isset($where['category']) && $where['category']) {
                $condition['category'] = $where['category'];
            }
            $condition['status'] = 1;

            if (isset($where['select']) && $where['select']) {
                $select = $where['select'];
            } else {
                $select = '*';
            }

            $result = self::select( DB::raw($select) )
                ->where($condition)
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();

            Cache::put($cacheKey, $result, $minutes=1);
        }

        return $result;
    }

    /**
     * 根据分类获取每个分类的文章
     *
     * @return static
     */
    public function getListDataGroupByCategory()
    {
        $cacheKey = sprintf( 'cache_%_%s_%s', $this->table, date('YmdHis'), json_encode(func_get_args()) );
        if (($result = Cache::get($cacheKey) ) === null ) {

            $categoryResult = $this->getListCountGroupByCategory();

            $result = collect($categoryResult)->map(function($item, $key){
                $where = collect([
                    'select'   => 'id, title, category',
                    'category' => $key,
                ]);

                $res = $this->getList($where);

                if ($res->isEmpty()) {
                    return false;
                }

                return $res->sortByDesc('id')->toArray();
            });

            if ($result) {
                Cache::put($cacheKey, $result, $minutes=10);
            }
        }

        return $result;
    }

    /**
     * 获取每个分类下的文章数量
     * @return mixed
     */
    public function getListCountGroupByCategory()
    {
        $cacheKey = sprintf( 'cache_%_%s_%s', $this->table, date('YmdHis'), json_encode(func_get_args()) );
        if ( ($result = Cache::get($cacheKey) ) === null ) {
            info('cache: ' . $cacheKey);
            $condition = [];
            if (isset($where['category']) && $where['category']) {
                $condition['category'] = $where['category'];
            }
            // if (isset($where['industry']) && $where['industry']) {
            //     $condition['industry'] = $where['industry'];
            // }
            // $condition['status'] = 1;

            $result = self::select(DB::raw('category, count(*) AS total'))
                ->where($condition)
                ->groupBy('category')
                ->get();

            if ($result) {
                $result = $result->mapWithKeys(function($item){
                    return [$item['category'] => $item['total']];
                })->toArray();

                Cache::put($cacheKey, $result, $minutes=1);
            }
        }

        return $result;
    }
}
