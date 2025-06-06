<?php

namespace message\exceptions;

/**
 * Сообщение уже было сохранено в таблицу messages
 */
class MessageAlreadyExistsException extends \RuntimeException
{
    /**
     * @param string $messageId
     */
    public function __construct(private readonly string $messageId)
    {
        parent::__construct('Message already exists');
    }

    /**
     * @return string
     */
    public function getMessageId(): string
    {
        return $this->messageId;
    }
}