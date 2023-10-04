<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = CategoryResource::collection(Category::get());

        return response()->json([

            'categories' => $categories

        ]);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if ($category) {
            return (new CategoryResource($category))
                ->response()
                ->setStatusCode(200); // Use CategoryResource to transform the model and set the status code to 200 (Success)
        } else {
            return response([
                'message' => 'Data not found',
            ], 404);
        }
    }




    public function store(Request $request)
    {
        try {
            $request->validate([
                'name_ar' => 'required|string',
                'name_en' => 'required|string',
            ]);

            $category = Category::create($request->all());

            return (new CategoryResource($category))
                ->response()
                ->setStatusCode(201);
        } catch (ValidationException $e) {
            return response([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response([
                'message' => 'Data could not be stored',
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name_ar' => 'required|string',
                'name_en' => 'required|string',
            ]);

            $category = Category::findOrFail($id); // Use findOrFail to throw ModelNotFoundException if category not found
            $category->update($request->all());

            return (new CategoryResource($category))
                ->response()
                ->setStatusCode(200);
        } catch (ModelNotFoundException $e) {
            return response([
                'message' => 'Category not found',

            ], 404);
        } catch (ValidationException $e) {
            return response([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response([
                'message' => 'Data could not be updated',
            ], 500);
        }
    }


    public function destroy($id)
{
    try {
        $category = Category::findOrFail($id);
        $category->delete();

        return response([
            'message' => 'Category deleted successfully',
        ]);
    } catch (ModelNotFoundException $e) {
        return response([
            'message' => 'Category not found',
        ]);
    } catch (\Exception $e) {
        return response([
            'message' => 'Category could not be deleted',
        ]);
    }
}
}
