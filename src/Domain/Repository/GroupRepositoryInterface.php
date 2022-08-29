<?php

namespace App\Domain\Repository;


use App\Domain\Model\Group;

interface GroupRepositoryInterface
{
    public function save(Group $gang);

    public function getQueue();
}