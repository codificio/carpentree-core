<?php

namespace Carpentree\Core\Scout\Engines;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Laravel\Scout\Engines\AlgoliaEngine as ParentEngine;

class LocalizedAlgoliaEngine extends ParentEngine
{
    /**
     * Update the given model in the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection $models
     * @throws \Algolia\AlgoliaSearch\Exceptions\AlgoliaException
     * @return void
     */
    public function update($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        $index = $this->algolia->initIndex($models->first()->searchableAs());

        if ($this->usesSoftDelete($models->first()) && $this->softDelete) {
            $models->each->pushSoftDeleteMetadata();
        }

        $class = get_class($models->first());

        $objects = (in_array(Translatable::class, class_uses_recursive($class))) ?
            $this->getLocalizedObjects($models) :
            $this->getObjects($models);

        $objects = $objects->filter()->values()->all();

        if (!empty($objects)) {
            $index->saveObjects($objects);
        }
    }

    /**
     * @param Collection $models
     * @return Collection|\Illuminate\Support\Collection
     */
    protected function getObjects(Collection $models)
    {
        return $models->map(
            function ($model) {
                if (empty($searchableData = $model->toSearchableArray())) {
                    return;
                }

                return array_merge(
                    ['objectID' => $model->getScoutKey()],
                    $searchableData,
                    $model->scoutMetadata()
                );
            });
    }

    /**
     * @param Collection $models
     * @return \Illuminate\Support\Collection
     */
    protected function getLocalizedObjects(Collection $models)
    {
        $objects = new \Illuminate\Support\Collection();

        foreach ($models as $model) {

            $locales = $model->getTranslationsArray();

            foreach ($locales as $lang => $values) {
                App::setlocale($lang);

                $model->pushLocaleMetadata($lang);

                if (empty($searchableData = $model->toSearchableArray())) {
                    continue;
                }

                $objects->add(array_merge(
                    ['objectID' => $model->getScoutKey() . "-$lang"],
                    $searchableData,
                    $model->scoutMetadata()
                ));

            }

        }

        return $objects;
    }

}
