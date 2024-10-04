<?php 
namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
trait CanLoadRelationships{
// xử lý việc tải realation cho các Model hoặc cho các Query 1 cách linh hoạt
    public function loadRelationships( Model| QueryBuilder |EloquentBuilder $for , ?array $relations = null){
        $relations =  $relations??$this->relations ?? [];
        foreach($relations as $relation){
            // tải relationship dựa vào điều kiện
            $for->when(
                $this->shouldIncludeRelation($relation) , 
                fn($query) => 
                $for instanceof Model ? $for->load($relation):$for->with($relation)
            );
        }
        return $for;
    }

    // hàm này để xử lý việc tải relation dựa theo query string 
    public function shouldIncludeRelation(string $relation)
    {

        $include = request()->query("include"); // get relation load from path 

        if (!$include) {
            return false;
        }

        $relations = array_map("trim", explode(",", $include));
        return in_array($relation, $relations);
    }

}
