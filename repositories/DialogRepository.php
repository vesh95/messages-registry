<?php

namespace message\repositories;

/**
 * Репозиторий для работы с диалогами
 */
readonly class DialogRepository
{
    /**
     * @param \PDO $db
     */
    public function __construct(private \PDO $db)
    {
    }

    /**
     * Вставить или обновить диалог
     * @param string $clientId
     * @return void
     */
    public function insert(string $clientId): void
    {
        $stmt = $this->db->prepare("INSERT INTO dialogs (client_id) VALUES (:client_id) ON CONFLICT DO NOTHING");
        $stmt->bindParam(':client_id', $clientId);
        $stmt->execute();
    }
}