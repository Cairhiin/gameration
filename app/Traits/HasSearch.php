<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

trait HasSearch
{
    /**
     * Perform a search on the given model or model name.
     *
     * This method accepts either an Eloquent model instance or a fully
     * qualified class name of a model as its first argument. It delegates
     * the search operation to the appropriate method based on the type
     * of the first argument.
     *
     * @return Collection|null The search results, or null if no valid model
     * or model name is provided.
     */

    public function search(): ?Collection
    {
        $arguments = func_get_args();

        if (!$arguments) {
            return null;
        }

        if ($arguments[0] instanceof \Illuminate\Database\Eloquent\Model) {
            return $this->searchWithModelAsEloquentModel(...$arguments);
        }

        if (is_string($arguments[0])) {
            return $this->searchWithModelAsString(...$arguments);
        }

        return null;
    }

    /**
     * Perform a search on the given model.
     *
     * @param string $model_name Fully qualified class name of the model to search.
     * @param string $needle The search string.
     * @param string $field The field to search in. Defaults to 'name'.
     * @param int $limit The number of results to return. Defaults to 5.
     *
     * @return Collection|null The search results, or null if the model is not an Eloquent model.
     */
    public function searchWithModelAsString(string $model_name, string $needle, string $field = 'name', int $limit = 5, array $where = []): ?Collection
    {
        $model = new $model_name();

        if ($model instanceof \Illuminate\Database\Eloquent\Model) {
            if (!empty($where)) {
                return $model->where($field, 'like', "%$needle%")->where(
                    function ($query) use ($where) {
                        foreach ($where as $key => $value) {
                            $query->where($key, $value);
                        }
                    }
                )->get()->take($limit);
            }

            return $model->where($field, 'like', "%$needle%")->get()->take($limit);
        }

        return null;
    }

    /**
     * Perform a search on the given model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model The model to search.
     * @param string $needle The search string.
     * @param string $field The field to search in. Defaults to 'name'.
     * @param int $limit The number of results to return. Defaults to 5.
     *
     * @return Collection|null The search results, or null if the model is not an Eloquent model.
     */
    public function searchWithModelAsEloquentModel(\Illuminate\Database\Eloquent\Model $model, string $needle, array $exclude = [], string $field = 'name', int $limit = 5, array $where = []): ?Collection
    {
        if (!empty($where)) {
            return $model->where($field, 'like', "%$needle%")->where(
                function ($query) use ($where) {
                    foreach ($where as $key => $value) {
                        $query->where($key, $value);
                    }
                }
            )->get()->take($limit);
        }

        return $model->where($field, 'like', "%$needle%")->get()->take($limit);
    }
}
