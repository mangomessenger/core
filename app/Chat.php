<?php

namespace App;

use App\Services\Chat\ChannelService;
use App\Services\Chat\DirectChatService;
use App\Services\Chat\GroupService;
use App\Services\Message\MessageService;

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
     * Chat constructor.
     *
     * @param ChannelService $channelService
     * @param DirectChatService $directChatService
     * @param GroupService $groupService
     * @param MessageService $messageService
     */
    public function __construct(
        ChannelService $channelService,
        DirectChatService $directChatService,
        GroupService $groupService,
        MessageService $messageService
    )
    {
        $this->channelService = $channelService;
        $this->directChatService = $directChatService;
        $this->groupService = $groupService;
        $this->messageService = $messageService;
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
}
