<?php

namespace message\repositories;

use message\exceptions\MessageAlreadyExistsException;

/**
 * Репозиторий для работы с сообщениями
 */
readonly class MessageRepository
{
    /**
     * @param \PDO $db
     */
    public function __construct(private \PDO $db)
    {
    }

    /**
     * Вставить сообщение
     * @param string $messageId
     * @param string $clientId
     * @param $messageText
     * @param int $sendAt
     * @return void
     * @throws MessageAlreadyExistsException
     */
    public function insert(string $messageId, string $clientId, $messageText, int $sendAt): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO messages (message_id, message_text, send_at, client_id) VALUES (:message_id, :message_text, :send_at, :client_id)"
        );
        $stmt->bindParam(':message_id', $messageId);
        $stmt->bindParam(':client_id', $clientId);
        $stmt->bindParam(':message_text', $messageText);
        $stmt->bindParam(':send_at', $sendAt, \PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (\PDOException $e) {
            if ($e->getCode() === 23505) {
                throw new MessageAlreadyExistsException($messageId);
            }

            throw $e;
        }
    }
}