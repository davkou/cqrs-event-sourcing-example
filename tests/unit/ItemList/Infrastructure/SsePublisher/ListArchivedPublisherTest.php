<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\ItemList\Infrastructure\SsePublisher;

use Codeception\Test\Unit;
use Taranto\ListMaker\ItemList\Domain\Event\ListArchived;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Infrastructure\SsePublisher\ListArchivedPublisher;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ListArchivedPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\ItemList\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ListArchivedPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/lists';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var ListArchivedPublisher
     */
    private $listArchivedPublisher;

    /**
     * @var ListArchived
     */
    private $listArchivedEvent;

    protected function _before(): void
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->listArchivedPublisher = new ListArchivedPublisher($this->ssePublisher, self::URL);

        $this->listArchivedEvent = new ListArchived((string) ListId::generate());
    }

    /**
     * @test
     */
    public function it_publishes_the_list_archived_event(): void
    {
        ($this->listArchivedPublisher)($this->listArchivedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->listArchivedEvent->eventType(),
            'payload' => [
                'id' => (string) $this->listArchivedEvent->aggregateId()
            ]
        ]));
    }
}
