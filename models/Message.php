<?php

namespace message\models;

use yii\base\Model;

/**
 * Модель сообщения
 */
class Message extends Model
{
    /**
     * @var string уникальный внешний идентификатор сообщения
     */
    public string $external_message_id;

    /**
     * @var string уникальный внешний идентификатор клиента
     */
    public string $external_client_id;

    /**
     * @var string номер телефона
     */
    public string $client_phone;

    /**
     * @var string текст сообщения
     */
    public string $message_text;

    /**
     * @var int время отправки сообщения unixtime
     */
    public int $send_at;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['external_message_id', 'external_client_id', 'client_phone', 'message_text', 'send_at'], 'required'],
            [['external_message_id', 'external_client_id'], 'string', 'max' => 36],
            [['client_phone'], 'string', 'max' => 12],
            [['client_phone'], function ($attribute, $params, $validator) {
                if (!preg_match('/^\+7\d{10}$/', $this->$attribute)) {
                    $this->addError($attribute, 'Invalid phone number');
                }
            }],
            [['message_text'], 'string', 'max' => 4096],
            [['send_at'], 'integer']
        ];
    }
}