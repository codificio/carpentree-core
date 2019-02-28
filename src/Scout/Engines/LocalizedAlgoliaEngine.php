<?php

namespace Carpentree\Core\Scout\Engines;

use Illuminate\Support\Facades\App;
use Laravel\Scout\Engines\AlgoliaEngine as ParentEngine;

class LocalizedAlgoliaEngine extends ParentEngine
{
    /**
     * Update the given model in the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $models
     * @throws \Algolia\AlgoliaSearch\Exceptions\AlgoliaException
     * @return void
     */
    public function update($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        $temp = $models->first();

        $baseIndex = $temp->searchableAs();
        $localizedIndexes = $temp::localizedSearchable();

        if ($this->usesSoftDelete($models->first()) && config('scout.soft_delete', false)) {
            $models->each->pushSoftDeleteMetadata();
        }

        if ($localizedIndexes) {
            $this->updateLocalized($models, $baseIndex);
        } else {
            $this->standardUpdate($models, $baseIndex);
        }

    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $models
     * @param string $indexName
     * @throws \Algolia\AlgoliaSearch\Exceptions\MissingObjectId
     */
    protected function standardUpdate($models, string $indexName)
    {
        $objects = $models->map(function ($model) {
            $array = array_merge(
                $model->toSearchableArray(), $model->scoutMetadata()
            );

            if (empty($array)) {
                return;
            }

            return array_merge(['objectID' => $model->getScoutKey()], $array);
        })->filter()->values()->all();

        if (! empty($objects)) {
            $index = $this->algolia->initIndex($indexName);

            $index->saveObjects($objects);
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $models
     * @param string $indexName
     * @throws \Algolia\AlgoliaSearch\Exceptions\MissingObjectId
     */
    protected function updateLocalized($models, string $indexName)
    {
        $objectsByLocale = [];

        foreach ($models as $model) {

            foreach (config('carpentree.core.locales') as $locale) {

                App::setLocale($locale);

                $array = array_merge(
                    $model->toSearchableArray(), $model->scoutMetadata()
                );

                if (empty($array)) {
                    continue;
                }

                $objectsByLocale[$locale][] = array_merge(['objectID' => $model->getScoutKey()], $array);

            }

        }

        foreach ($objectsByLocale as $locale => $objects) {

            $index = $this->algolia->initIndex($indexName . "_$locale");

            if (! empty($objects)) {
                $index->saveObjects($objects);
            }

        }
    }

}
