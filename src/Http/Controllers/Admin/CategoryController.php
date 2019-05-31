<?php

namespace Carpentree\Core\Http\Controllers\Admin;

use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Http\Requests\Admin\Category\CreateCategoryRequest;
use Carpentree\Core\Http\Requests\Admin\Category\UpdateCategoryRequest;
use Carpentree\Core\Http\Resources\CategoryResource;
use Carpentree\Core\Models\Category;
use Carpentree\Core\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CategoryController extends Controller
{

    /**
     * @var CategoryRepository $repository
     */
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $type
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getByType($type)
    {
        if (!Auth::user()->can('categories.read')) {
            throw UnauthorizedException::forPermissions(['categories.read']);
        }

        $categories = $this->repository->findByField('type', $type);
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
            $category = new Category($attributes);
            $category->save();

            $parentData = $request->input('relationships.parent.data', null);

            if (!is_null($parentData)) {
                $parent = Category::findOrFail($parentData['id']);
                $category->parent()->associate($parent);
            }

            // Check tree consinstency and fix
            if (Category::isBroken()) {
                Category::fixTree();
            }

            $category->save();

            return $category;
        });

        return CategoryResource::make($category)->response()->setStatusCode(201);
    }


    /**
     * @param UpdateCategoryRequest $request
     * @return CategoryResource
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
                    $category->parent_id = $parent->id;
                }
            }

            // Check tree consinstency and fix
            if (Category::isBroken()) {
                Category::fixTree();
            }

            $category->save();

            return $category;
        });

        return CategoryResource::make($category);
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

        if ($this->repository->delete($id)) {
            return response()->json(null, 204);
        } else {
            return response()->json(null, 202);
        }
    }

}
