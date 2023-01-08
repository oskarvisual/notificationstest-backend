<?php

namespace App\Domain\Api;

use App\Domain\Entity\Category;
use App\Domain\Service\CategoryService;
use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\JsonResponse;

class CategoriesApi
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request): JsonResponse
    {
        $categories = array();
        foreach ($this->categoryService->getAll() as $category) {
            $categories[] = array(
                "id" => $category->getId(),
                "title" => $category->getTitle(),
                "content" => $category->getContent()
            );
        }
        return new JsonResponse(200, $categories);
    }

    public function show(Request $request): JsonResponse
    {
        $categoryId = intval(@$request->getQuery("id"));
        $category = $this->categoryService->getById($categoryId);
        if (!$category) {
            return new JsonResponse(404, array("error" => "Category not found"));
        }
        return new JsonResponse(200, array(
            "id" => $category->getId(),
            "title" => $category->getTitle(),
            "content" => $category->getContent()
        ));
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->getRequests();
        $category = $this->categoryService->add(
            @$data["title"],
            @$data["content"]
        );
        return new JsonResponse(201, array(
            "id" => $category->getId(),
            "title" => $category->getTitle(),
            "content" => $category->getContent()
        ));
    }

    public function update(Request $request): JsonResponse
    {
        $categoryId = intval(@$request->getQuery("id"));
        $data = $request->getRequests();
        $category = $this->categoryService->update(
            @$categoryId,
            @$data["title"],
            @$data["content"]
        );
        if (!$category) {
            return new JsonResponse(204, array("error" => "Category not found or not updated"));
        }
        return new JsonResponse(200, array(
            "id" => $category->getId(),
            "title" => $category->getTitle(),
            "content" => $category->getContent()
        ));
    }

    public function delete(Request $request): JsonResponse
    {
        $categoryId = intval(@$request->getQuery("id"));
        $category = $this->categoryService->delete($categoryId);
        if (!$category) {
            return new JsonResponse(204, array("error" => "Category not found or not deleted"));
        }
        return new JsonResponse(200, array("success" => "Category deleted"));
    }
}
