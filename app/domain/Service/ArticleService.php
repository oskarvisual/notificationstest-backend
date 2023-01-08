<?php

namespace App\Domain\Service;

use App\Domain\Entity\Article;
use App\Domain\Entity\Notification;
use App\Domain\Entity\Channel;
use App\Domain\Repository\ArticleRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\Repository\NotificationRepository;

use App\Domain\Observer\Subject;
use App\Domain\Observer\Observer;

class ArticleService implements Subject
{
    private ArticleRepository $articleRepository;
    private array $channels = array();
    private array $observers = array();

    public function __construct(ArticleRepository $articleRepository, array $channels)
    {
        $this->channels = $channels;
        $this->articleRepository = $articleRepository;
    }

    public function getAll(): array
    {
        return $this->articleRepository->getAll();
    }

    public function getById(int $id): ?Article
    {
        return $this->articleRepository->getById($id);
    }

    public function add(
        string $title,
        string $content,
        int $categoryId,
        UserRepository $userRepository,
        NotificationRepository $notificationRepository
    ): ?Article {
        $article = new Article(0, $title, $content, $categoryId);
        $articleId = $this->articleRepository->add($article);
        $article->setId($articleId);
        $users = $userRepository->getBySubscribed($categoryId);
        $subject = sprintf("New Article: %s", $article->getTitle());
        $body = sprintf("Article: %s", $article->getContent());
        $sentAt = date('Y-m-d H:i:s');
        foreach ($users as $user) {
            if ($user->getChannels() == null) {
                continue;
            }
            foreach ($this->channels as $key => $channel) {
                if (in_array($key, $user->getChannels())) {
                    $this->addObserver(array(
                        "channel" => $key,
                        "subject" => $subject,
                        "body" => $body,
                        "sentAt" => $sentAt,
                        "userId" => $user->getId()
                    ));
                }
            }
        }
        $this->notifyObservers($notificationRepository);

        return $article;
    }

    public function update(int $id, string $title, string $content, int $categoryId): ?Article
    {
        $article = new Article($id, $title, $content, $categoryId);
        return ($this->articleRepository->update($article)) ? $article : null;
    }

    public function delete(int $id): bool
    {
        return $this->articleRepository->delete($id);
    }

    public function addObserver(array $observer): void
    {
        $this->observers[] = $observer;
    }

    public function removeObserver(array $observer): void
    {
        $key = array_search($observer, $this->observers);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    public function notifyObservers(NotificationRepository $notificationRepository): void
    {
        foreach ($this->observers as $observer) {
            $notification = new Notification(
                0,
                $observer["channel"],
                $observer["subject"],
                $observer["body"],
                $observer["sentAt"],
                $observer["userId"]
            );
            $notificationId = $notificationRepository->add($notification);
            $notification->setId($notificationId);
        }
    }
}
