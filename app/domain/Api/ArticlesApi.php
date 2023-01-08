<?php

namespace App\Domain\Api;

use App\Domain\Entity\Article;
use App\Domain\Service\ArticleService;
use App\Domain\Entity\Category;
use App\Domain\Service\CategoryService;
use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\JsonResponse;
use App\Domain\Repository\UserRepository;
use App\Domain\Repository\NotificationRepository;

class ArticlesApi
{
    private ArticleService $articleService;
    private CategoryService $categoryService;
    private UserRepository $userRepository;
    private NotificationRepository $notificationRepository;

    public function __construct(
        ArticleService $articleService,
        CategoryService $categoryService,
        UserRepository $userRepository,
        NotificationRepository $notificationRepository
    ) {
        $this->articleService = $articleService;
        $this->categoryService = $categoryService;
        $this->userRepository = $userRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $articles = array();
        foreach ($this->articleService->getAll() as $article) {
            $category = $this->categoryService->getById($article->getCategoryId());
            if (!$category) {
                return new JsonResponse(404, array("error" => "Category not found"));
            }
            $articles[] = array(
                "id" => $article->getId(),
                "title" => $article->getTitle(),
                "content" => $article->getContent(),
                "categoryId" => $article->getCategoryId(),
                "categoryTitle" => $category->getTitle()
            );
        }
        return new JsonResponse(200, $articles);
    }

    public function show(Request $request): JsonResponse
    {
        $id = intval(@$request->getQuery("id"));
        $article = $this->articleService->getById($id);
        if (!$article) {
            return new JsonResponse(404, array("error" => "Article not found"));
        }
        $category = $this->categoryService->getById($article->getCategoryId());
        if (!$category) {
            return new JsonResponse(404, array("error" => "Category not found"));
        }
        return new JsonResponse(200, array(
            "id" => $article->getId(),
            "title" => $article->getTitle(),
            "content" => $article->getContent(),
            "categoryId" => $article->getCategoryId(),
            "categoryTitle" => $category->getTitle()
        ));
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->getRequests();
        $category = $this->categoryService->getById(intval(@$data["categoryId"]));
        if (!$category) {
            return new JsonResponse(404, array("error" => "Category not found"));
        }
        $article = $this->articleService->add(
            @$data["title"],
            @$data["content"],
            intval(@$data["categoryId"]),
            $this->userRepository,
            $this->notificationRepository
        );
        return new JsonResponse(201, array(
            "id" => $article->getId(),
            "title" => $article->getTitle(),
            "content" => $article->getContent(),
            "categoryId" => $article->getCategoryId(),
            "categoryTitle" => $category->getTitle()
        ));
    }

    public function update(Request $request): JsonResponse
    {
        $articleId = intval(@$request->getQuery("id"));
        $data = $request->getRequests();
        $category = $this->categoryService->getById(intval(@$data["categoryId"]));
        if (!$category) {
            return new JsonResponse(404, array("error" => "Category not found"));
        }
        $article = $this->articleService->update(
            $articleId,
            @$data["title"],
            @$data["content"],
            intval(@$data["categoryId"])
        );
        if (!$article) {
            return new JsonResponse(204, array("error" => "Article not found or not updated"));
        }
        return new JsonResponse(200, array(
            "id" => $article->getId(),
            "title" => $article->getTitle(),
            "content" => $article->getContent(),
            "categoryId" => $article->getCategoryId(),
            "categoryTitle" => $category->getTitle()
        ));
    }

    public function delete(Request $request): JsonResponse
    {
        $articleId = intval(@$request->getQuery("id"));
        $article = $this->articleService->delete($articleId);
        if (!$article) {
            return new JsonResponse(204, array("error" => "Article not found or not deleted"));
        }
        return new JsonResponse(200, array("success" => "Article deleted"));
    }
}
