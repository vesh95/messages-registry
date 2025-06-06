<?php

namespace message\repositories;

/**
 * Класс репозитория для работы с телефонами клиентов
 */
readonly class PhoneRepository
{
    /**
     * @param \PDO $db
     */
    public function __construct(private \PDO $db)
    {
    }

    /**
     * Вставить или обновить телефон клиента
     * @param string $clientId
     * @param string $phone
     * @return void
     */
    public function upsert(string $clientId, string $phone): void
    {
        $stmt = $this->db->prepare("INSERT INTO clients (client_id, phone) VALUES (:client_id, :phone) ON CONFLICT (client_id) DO UPDATE SET phone = :phone");
        $stmt->bindParam(':client_id', $clientId);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
    }
}