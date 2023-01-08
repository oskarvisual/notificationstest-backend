<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: 
Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('X-Frame-Options: SAMEORIGIN');

require_once 'config.php';

require_once APP_DIR . '/app/infrastructure/Http/Router.php';
require_once APP_DIR . '/app/infrastructure/Http/Request.php';
require_once APP_DIR . '/app/infrastructure/Http/Response.php';
require_once APP_DIR . '/app/infrastructure/Http/HtmlResponse.php';
require_once APP_DIR . '/app/infrastructure/Http/JsonResponse.php';

require_once APP_DIR . '/app/domain/Observer/Subject.php';
require_once APP_DIR . '/app/domain/Observer/Observer.php';

require_once APP_DIR . '/app/domain/Entity/Article.php';
require_once APP_DIR . '/app/domain/Entity/Category.php';
require_once APP_DIR . '/app/domain/Entity/User.php';
require_once APP_DIR . '/app/domain/Entity/Notification.php';
require_once APP_DIR . '/app/domain/Entity/Channel.php';

require_once APP_DIR . '/app/domain/Service/ArticleService.php';
require_once APP_DIR . '/app/domain/Service/CategoryService.php';
require_once APP_DIR . '/app/domain/Service/UserService.php';
require_once APP_DIR . '/app/domain/Service/NotificationService.php';

require_once APP_DIR . '/app/domain/Repository/ArticleRepository.php';
require_once APP_DIR . '/app/domain/Repository/CategoryRepository.php';
require_once APP_DIR . '/app/domain/Repository/UserRepository.php';
require_once APP_DIR . '/app/domain/Repository/NotificationRepository.php';

require_once APP_DIR . '/app/infrastructure/Persistence/MySqlArticleRepository.php';
require_once APP_DIR . '/app/infrastructure/Persistence/MySqlCategoryRepository.php';
require_once APP_DIR . '/app/infrastructure/Persistence/MySqlUserRepository.php';
require_once APP_DIR . '/app/infrastructure/Persistence/MySqlNotificationRepository.php';

require_once APP_DIR . '/app/domain/Api/ArticlesApi.php';
require_once APP_DIR . '/app/domain/Api/CategoriesApi.php';
require_once APP_DIR . '/app/domain/Api/UsersApi.php';
require_once APP_DIR . '/app/domain/Api/NotificationsApi.php';
require_once APP_DIR . '/app/domain/Api/ChannelsApi.php';

use App\Domain\Entity\Channel;

use App\Domain\Service\ArticleService;
use App\Domain\Service\CategoryService;
use App\Domain\Service\UserService;
use App\Domain\Service\NotificationService;

use App\Domain\Api\ArticlesApi;
use App\Domain\Api\CategoriesApi;
use App\Domain\Api\UsersApi;
use App\Domain\Api\NotificationsApi;
use App\Domain\Api\ChannelsApi;

use App\Infrastructure\Persistence\MySqlArticleRepository;
use App\Infrastructure\Persistence\MySqlCategoryRepository;
use App\Infrastructure\Persistence\MySqlUserRepository;
use App\Infrastructure\Persistence\MySqlNotificationRepository;

use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Router;

try {
    $pdo = new PDO(sprintf('mysql:host=%s;dbname=%s', DB_HOST, DB_NAME), DB_USERNAME, DB_PASSWORD);
} catch (Exception $e) {
    die("Error: " . $e->GetMessage());
}

$request = new Request();

$channel = new Channel();
$channel->addChannel("email", "E-Mail");
$channel->addChannel("sms", "SMS");
$channel->addChannel("push", "Push Notification");

$articleRepository = new MySqlArticleRepository($pdo);
$categoryRepository = new MySqlCategoryRepository($pdo);
$userRepository = new MySqlUserRepository($pdo);
$notificationRepository = new MySqlNotificationRepository($pdo);

$categoryService = new CategoryService($categoryRepository);
$articleService = new ArticleService($articleRepository, $channel->getChannels());
$userService = new UserService($userRepository);
$notificationService = new NotificationService($notificationRepository);

$articlesApi = new ArticlesApi(
    $articleService,
    $categoryService,
    $userRepository,
    $notificationRepository
);
$categoriesApi = new CategoriesApi($categoryService);
$usersApi = new UsersApi($userService);
$notificationsApi = new NotificationsApi($notificationService);
$channelsApi = new ChannelsApi($channel->getChannels());

$router = new Router();
$router->addRoute("GET", "/api/articles", array($articlesApi, "index"));
$router->addRoute("GET", "/api/articles/show", array($articlesApi, "show"));
$router->addRoute("POST", "/api/articles", array($articlesApi, "add"));
$router->addRoute("PUT", "/api/articles", array($articlesApi, "update"));
$router->addRoute("DELETE", "/api/articles", array($articlesApi, "delete"));
$router->addRoute("GET", "/api/categories", array($categoriesApi, "index"));
$router->addRoute("GET", "/api/categories/show", array($categoriesApi, "show"));
$router->addRoute("POST", "/api/categories", array($categoriesApi, "add"));
$router->addRoute("PUT", "/api/categories", array($categoriesApi, "update"));
$router->addRoute("DELETE", "/api/categories", array($categoriesApi, "delete"));
$router->addRoute("GET", "/api/users", array($usersApi, "index"));
$router->addRoute("GET", "/api/users/show", array($usersApi, "show"));
$router->addRoute("POST", "/api/users", array($usersApi, "add"));
$router->addRoute("PUT", "/api/users", array($usersApi, "update"));
$router->addRoute("DELETE", "/api/users", array($usersApi, "delete"));
$router->addRoute("GET", "/api/notifications", array($notificationsApi, "index"));
$router->addRoute("GET", "/api/notifications/show", array($notificationsApi, "show"));
$router->addRoute("DELETE", "/api/notifications", array($notificationsApi, "delete"));
$router->addRoute("GET", "/api/channels", array($channelsApi, "index"));
$router->run($request);
