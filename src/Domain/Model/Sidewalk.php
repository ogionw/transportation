<?php

namespace App\Domain\Model;

use App\Application\Cqrs\EventBusInterface;
use App\Domain\Event\JourneyRequestedEvent;
use App\Domain\Exception\DuplicateGroupIdException;
use App\Domain\Exception\InvalidPeopleNumberException;
use App\Domain\Repository\GroupRepositoryInterface;
use App\Infrastructure\Entity\Gang;
use SplQueue;

class Sidewalk implements Location
{
    public SplQueue $queue;
    private GroupRepositoryInterface $repo;
    private Group $group;
    private EventBusInterface $eventBus;

    /**
     * @throws DuplicateGroupIdException
     */
    public function __construct(GroupRepositoryInterface $repo, EventBusInterface $eventBus)
    {
        $this->repo = $repo;
        $this->queue = new SplQueue();
        $this->group = new Gang();
        $this->eventBus = $eventBus;
        foreach ($repo->getQueue() as $group){
            $this->accept($group);
        }
    }

    /**
     * @throws DuplicateGroupIdException
     */
    public function accept(Visitor $group): void
    {
        foreach ($this->queue as $existingGroup){
            if($group->getId() === $existingGroup->getId()){
                throw new DuplicateGroupIdException($group->getId());
            }
        }
        $group->setLocation($this);
        $this->queue->enqueue($group);
    }

    public function share(Parking $parking, Road $road)
    {
        $waiting = new SplQueue();
        while (! $this->queue->isEmpty()){
            /** @var Group $group */
            $group = $this->queue->dequeue();
            $vehicle = $parking->findMinimalCarForGroup($group);
            $vehicle
                ? $group->board($this, $vehicle)
                : $waiting->enqueue($group);
        }
        $this->queue = $waiting;
        $parking->depart($road);
    }

    /**
     * @throws DuplicateGroupIdException
     * @throws InvalidPeopleNumberException
     */
    public function formGroup(int $id, int $people): void
    {
        $group = $this->group->gather($id, $people, $this);
        $this->accept($group);
        $this->save($group);
        $this->eventBus->dispatch(new JourneyRequestedEvent($group->getId()));
    }

    public function dissolveGroupById(int $id): bool
    {
        $queue2 = new SplQueue();
        $dissolved = false;
        while (! $this->queue->isEmpty()){
            $el = $this->queue->dequeue();
            if($id === $el->getId()){
                $dissolved = true;
                $this->repo->deleteGroup($el);
            } else {
                $queue2->enqueue($el);
            }
        }
        $this->queue = $queue2;
        return $dissolved;
    }

    public function locateGroup(int $id): ?Group
    {
        /** @var \App\Infrastructure\Entity\Gang $group */
        foreach ($this->queue as $group){
            if($group->getId() === $id){
                return $group;
            }
        }
        return null;
    }
    public function save(Group $group)
    {
        $this->repo->save($group);
    }

    public function delete(Group $group)
    {
        $this->repo->deleteGroup($group);
    }
}