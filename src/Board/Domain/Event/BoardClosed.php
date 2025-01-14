<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Domain\Event;

use Taranto\ListMaker\Shared\Domain\Message\DomainEvent;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Board\Domain\BoardId;

/**
 * Class BoardClosed
 * @package Taranto\ListMaker\Board\Domain\Event
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardClosed extends DomainEvent
{
    private const EVENT_TYPE = 'board-closed';

    /**
     * @return IdentifiesAggregate
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return BoardId::fromString($this->aggregateId);
    }

    /**
     * @return string
     */
    public function eventType(): string
    {
        return self::EVENT_TYPE;
    }
}
