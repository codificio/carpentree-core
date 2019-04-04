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

        if ($this->usesSoftDelete($models->first()) && $this->softDelete) {
            $models->each->pushSoftDeleteMetadata();
        }

        $class = get_class($models->first());

        if (in_array(Translatable::class, class_uses_recursive($class))) {
            // Localized
            $this->updateLocalized($models);
        } else {
            // Non localized
            $this->updateStandard($models);
        }
    }


    protected function updateStandard(Collection $models)
    {
        $index = $this->algolia->initIndex($models->first()->searchableAs());

        $objects = $models->map(function ($model) {
            if (empty($searchableData = $model->toSearchableArray())) {
                return;
            }

            return array_merge(
                ['objectID' => $model->getScoutKey()],
                $searchableData,
                $model->scoutMetadata()
            );
        })->filter()->values()->all();

        if (!empty($objects)) {
            $index->saveObjects($objects);
        }
    }

    /**
     * @param Collection $models
     * @throws \Algolia\AlgoliaSearch\Exceptions\MissingObjectId
     */
    protected function updateLocalized(Collection $models)
    {
        $localizedCollection = [];

        foreach ($models as $model) {
            $locales = $model->getTranslationsArray();

            foreach ($locales as $lang => $values) {
                App::setlocale($lang);

                if (empty($searchableData = $model->toSearchableArray())) {
                    continue;
                }

                $localizedCollection[$lang][] = array_merge(
                    ['objectID' => $model->getScoutKey()],
                    $searchableData,
                    $model->scoutMetadata()
                );
            }
        }

        $indexName = $models->first()->searchableAs();

        foreach ($localizedCollection as $locale => $objects) {
            $index = $this->algolia->initIndex($indexName . "_$locale");

            if (!empty($objects)) {
                $index->saveObjects($objects);
            }
        }
    }

}
