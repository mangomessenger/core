<?php

namespace App;

use App\Services\Chat\ChannelService;
use App\Services\Chat\ChatService;
use App\Services\Chat\DirectChatService;
use App\Services\Chat\GroupService;
use App\Services\Message\MessageService;
use App\Services\User\UserService;

class Chat
{
    /**
     * ChannelService instance
     *
     * @var ChannelService
     */
    protected ChannelService $channelService;

    /**
     * DirectChatService instance
     *
     * @var DirectChatService
     */
    protected DirectChatService $directChatService;

    /**
     * GroupService instance
     *
     * @var GroupService
     */
    protected GroupService $groupService;

    /**
     * MessageService instance
     *
     * @var MessageService
     */
    protected MessageService $messageService;

    /**
     * ChatService instance
     *
     * @var ChatService
     */
    protected ChatService $chatService;

    /**
     * UserService instance
     *
     * @var UserService
     */
    protected UserService $userService;

    /**
     * Chat constructor.
     *
     * @param ChannelService $channelService
     * @param DirectChatService $directChatService
     * @param GroupService $groupService
     * @param MessageService $messageService
     * @param ChatService $chatService
     * @param UserService $userService
     */
    public function __construct(
        ChannelService $channelService,
        DirectChatService $directChatService,
        GroupService $groupService,
        MessageService $messageService,
        ChatService $chatService,
        UserService $userService
    )
    {
        $this->channelService = $channelService;
        $this->directChatService = $directChatService;
        $this->groupService = $groupService;
        $this->messageService = $messageService;
        $this->chatService = $chatService;
        $this->userService = $userService;
    }

    /**
     * Gets DirectChatService.
     *
     * @return DirectChatService
     */
    public function directChats(): DirectChatService
    {
        return $this->directChatService;
    }

    /**
     * Gets ChannelService.
     *
     * @return ChannelService
     */
    public function channels(): ChannelService
    {
        return $this->channelService;
    }

    /**
     * Gets GroupService.
     *
     * @return GroupService
     */
    public function groups(): GroupService
    {
        return $this->groupService;
    }

    /**
     * Gets MessageService.
     *
     * @return MessageService
     */
    public function messages(): MessageService
    {
        return $this->messageService;
    }

    /**
     * Gets ChatService.
     *
     * @return ChatService
     */
    public function chats(): ChatService
    {
        return $this->chatService;
    }

    /**
     * Sets message.
     *
     * @param string $message
     *
     * @return MessageService
     */
    public function message($message)
    {
        return $this->messageService->setMessage($message);
    }

    /**
     * Gets UserService.
     *
     * @return UserService
     */
    public function users(): UserService
    {
        return $this->userService;
    }
}
