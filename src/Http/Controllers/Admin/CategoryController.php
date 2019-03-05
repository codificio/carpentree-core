<?php

namespace Carpentree\Core\Http\Controllers\Admin;

use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Http\Requests\Admin\Category\CreateCategoryRequest;
use Carpentree\Core\Http\Requests\Admin\Category\UpdateCategoryRequest;
use Carpentree\Core\Http\Resources\CategoryResource;
use Carpentree\Core\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CategoryController extends Controller
{

    /**
     * @param $type
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getByType($type)
    {
        $categories = Category::where('type', $type)->get();
        return CategoryResource::collection($categories);
    }

    /**
     * @param CreateCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateCategoryRequest $request)
    {
        if (!Auth::user()->can('categories.create')) {
            throw UnauthorizedException::forPermissions(['categories.create']);
        }

        // TODO: refactoring of categories creation
        $category = DB::transaction(function() use ($request) {
            $attributes = $request->input('attributes');
            /** @var Category $category */
            $category = Category::create($attributes);

            $parentData = $request->input('relationships.parent.data', null);

            if (!is_null($parentData)) {
                $parent = Category::findOrFail($parentData['id']);
                $category->parent()->associate($parent);
            }

            $category->save();

            return $category;
        });

        return CategoryResource::make($category)->response()->setStatusCode(201);
    }

    /**
     * @param UpdateCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCategoryRequest $request)
    {
        if (!Auth::user()->can('categories.update')) {
            throw UnauthorizedException::forPermissions(['categories.update']);
        }

        // TODO: refactoring of categories update
        $category = DB::transaction(function() use ($request) {

            $id = $request->input('id');

            /** @var Category $category */
            $category = Category::findOrFail($id);

            if ($request->has('attributes')) {
                $attributes = $request->input('attributes');
                $category = $category->fill($attributes);
            }

            if ($request->has('relationships.parent')) {
                $_data = $request->input('relationships.parent.data', null);

                if ($_data === null) {
                    $category->parent_id = null;
                } else {
                    $parent = Category::findOrFail($_data['id']);
                    $category->parent()->associate($parent);
                }
            }

            $category->save();

            return $category;
        });

        return CategoryResource::make($category)->response()->setStatusCode(201);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        if (!Auth::user()->can('categories.delete')) {
            throw UnauthorizedException::forPermissions(['categories.delete']);
        }

        /** @var Category $user */
        $category = Category::findOrFail($id);

        if ($category->delete($id)) {
            return response()->json(null, 204);
        } else {
            return response()->json(null, 202);
        }
    }

}
