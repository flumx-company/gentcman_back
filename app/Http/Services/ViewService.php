<?php

namespace Gentcmen\Http\Services;

use Gentcmen\Models\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
class ViewService
{
    /**
     * Add a view to the given entity.
     * @param Model $entity
     */

    public function add(Model $entity, $date)
    {
        $view = $entity->views()
            ->where('user_id', auth('api')->id())
            ->first();
//dd($date,Carbon::parse($date)->toDateTimeString());
        // Increment view counter if model exists
        if ($view) {
            $view->increment('views');
	    $view->update(['created_at' => Carbon::now()->toDateTimeString()]);
            return;
        }

        $element = $entity->views()->create([
            'views' => 1,
            'user_id' => auth('api')->id()
        ]);
//dd($date,Carbon::parse($date)->toDateTimeString());
	if($date) {
	    $element->update(['created_at' => Carbon::parse($date)->toDateTimeString()]);
	}
    }

    /**
     * @param mixed $page
     * @param mixed $perPage
     * @param array $options
     * @return LengthAwarePaginator
     */

    public  function getUserViewedEntries(mixed $page = 1, mixed $perPage = 10, array $options = []): LengthAwarePaginator
    {
        if (!$page) $page = 1;
        if (!$perPage) $perPage = 10;

        $fieldValue = $options['text'];
	$costValue = $options['cost'];
	$published = $options['published'] ?? 1;
        $sort = $options['sort'] ?? 'created_at';
        $sortBy = $options['sort-by'] ?? 'desc';
        $isProductRelation = strpos($sort, '.');
//	dd($fieldValue);
        $query = View::with('product')
                ->whereHas('product', function ($query) use ($fieldValue, $isProductRelation, $sort, $costValue, $published) {
                    $query->when($fieldValue, function ($query) use ($fieldValue, $sort) {
                        $query->where('name', 'like', '%' . $fieldValue . '%')
			    ->orWhere('description', 'like', '%' . $fieldValue . '%');
                    })
		    ->when($costValue, function ($query) use ($costValue) {
                        $query->where('cost', 'like', '%' . $costValue . '%');
                    })->where('published', $published);

                })
		->where('user_id', auth()->id());

        if ($isProductRelation) {
            $sorByFunctionEnumerableArray = [
                'desc' => 'sortByDesc',
                'asc' => 'sortBy',
            ];

            $sortByFunction = $sorByFunctionEnumerableArray[$sortBy];

            $query = $query
                        ->get()
                        ->$sortByFunction(function($query){
                            return $query->product->cost;
                        });
        } else {
            $query = $query
                    ->orderBy($sort, $sortBy)
                    ->get();
        }

        if ($options['groupByDate']) {
                $query = $query->groupBy(function ($item) {
                    return $item->created_at->format('Y-m-d');
                });

		$query = collect($query)->map(function ($elem) {
		    return collect($elem)->map(function ($item) {
			$item['product']['viewable_id'] = $item['viewable_id'];
			$item['product']['viewable_created_at'] = $item['created_at'];
			return $item['product']; 
		    });
		});
        } else {
	    $query = $query->unique('product.name');
	}

        return new LengthAwarePaginator(
            array_slice($query->toArray(), max(0, ($page - 1) * $perPage), $perPage),
            $query->count(),
            $perPage,
            $page,
            $options
        );
    }

    public function destroy(int $viewableId, $date = null): bool
    {

        View::where([
            ['user_id', auth('api')->id()],
            ['viewable_id', $viewableId]
        ])->when($date, function ($query) use ($date) {
	    $query->whereDate('created_at', Carbon::parse()->toDateString());
	})->delete();

        return true;
    }

    /**
     * Reset all view counts by deleting all views that related to user.
     */
    public function resetAllUserViews(): bool
    {
        View::where('user_id', auth('api')->id())->delete();

        return true;
    }

    /**
     * Reset all view counts by deleting all views.
     */

    public function resetAll()
    {
        View::truncate();

        return true;
    }
}
