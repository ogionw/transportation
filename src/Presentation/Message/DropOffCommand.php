<?php
declare(strict_types=1);

namespace App\Presentation\Message;

use App\Presentation\Dto\GroupId;

final class DropOffCommand implements Command
{
    private readonly GroupId $groupId;
    public function __construct(int $id)
    {
        $this->groupId = new GroupId($id);
    }

    public function getGroupId(): GroupId
    {
        return $this->groupId;
    }
}
