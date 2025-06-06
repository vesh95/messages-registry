<?php

namespace message\controllers;

use message\exceptions\MessageAlreadyExistsException;
use message\models\Message;
use message\services\MessageManager;
use yii\caching\Cache;
use yii\db\Exception;
use yii\filters\ContentNegotiator;
use yii\log\Logger;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * POST /
     * @param Cache $cache
     * @return Response
     * @throws Exception
     */
    public function actionIndex(Cache $cache): Response
    {
        $logger = \Yii::$app->log->getLogger();

        $message = new Message();
        $message->load($this->request->post(), '');
        if (!$message->validate()) {
            // Если данные не прошли валидацию, записываем ошибки в лог
            $logger->log(['message' => 'Data error', 'errors' => $message->errors], Logger::LEVEL_WARNING, 'message');

            return $this->asJson(['message' => 'Data error', 'errors' => $message->errors])->setStatusCode(400);
        }

        // Проверка, что сообщение не было отправлено ранее по кэшу
        if ($cache->exists('exists_message_id:' . $message->external_message_id)) {
            return $this->asJson(['message' => 'Data already exists'])->setStatusCode(409);
        }

        $connection = \Yii::$app->db;
        try {
            $connection->open();
            $manager = $this->createManager($connection->pdo);
            $manager->save($message); // Сохранение сообщения
            $cache->set('exists_message_id:' . $message->external_message_id, true); // Кэширование сохраненного сообщения

            return $this->asJson(['message' => 'Message received'])->setStatusCode(201);
        } catch (MessageAlreadyExistsException) {
            $connection->pdo->rollBack(); // Откат транзакции при ошибке сохранения сообщения
            $logger->log(['message' => 'Data already exists', 'external_message_id'], Logger::LEVEL_WARNING, 'message');

            return $this->asJson(['message' => 'Data already exists'])->setStatusCode(409);
        } finally {
            $connection->close();
        }

    }

    /**
     * @param \PDO $pdo
     * @return MessageManager
     */
    protected function createManager(\PDO $pdo): MessageManager
    {
        return new MessageManager($pdo);
    }
}