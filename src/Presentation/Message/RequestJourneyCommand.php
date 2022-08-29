<?php
declare(strict_types=1);

namespace App\Presentation\Message;

use App\Presentation\Dto\GroupId;

final class RequestJourneyCommand implements Command
{
    private readonly GroupId $groupId;
    private readonly int $people;
    public function __construct(int $id, int $people)
    {
        $this->groupId = new GroupId($id);
        $this->people = $people;
    }

    public function getPeople(): int
    {
        return $this->people;
    }

    public function getGroupId(): GroupId
    {
        return $this->groupId;
    }
}
