<?php

namespace message\services;

use message\exceptions\MessageAlreadyExistsException;
use message\models\Message;
use message\repositories\DialogRepository;
use message\repositories\MessageRepository;
use message\repositories\PhoneRepository;

/**
 * Класс для управления сообщениями
 */
readonly class MessageManager
{
    private PhoneRepository $phoneRepo;
    private MessageRepository $messageRepo;
    private DialogRepository $dialogRepo;

    /**
     * @param \PDO $db
     */
    public function __construct(private \PDO $db)
    {
        $this->phoneRepo = new PhoneRepository($this->db);
        $this->messageRepo = new MessageRepository($this->db);
        $this->dialogRepo = new DialogRepository($this->db);
    }

    /**
     * Сохранить сообщение
     * @param Message $message
     * @return void
     * @throws MessageAlreadyExistsException
     */
    public function save(Message $message): void
    {
        $this->db->beginTransaction();

        $this->phoneRepo->upsert($message->external_client_id, $message->client_phone);
        $this->messageRepo->insert($message->external_message_id, $message->external_client_id, $message->message_text, $message->send_at);
        $this->dialogRepo->insert($message->external_client_id);

        $this->db->commit();
    }
}