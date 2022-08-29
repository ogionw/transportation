<?php
declare(strict_types=1);

namespace App\Application\Query;
use App\Application\Cqrs\QueryHandler;
use App\Domain\Repository\GroupRepositoryInterface;
use App\Presentation\Message\LocateGroupQuery;

final class LocateGroupQueryHandler implements QueryHandler
{
    public function __construct(private readonly GroupRepositoryInterface $repo){}

    public function __invoke(LocateGroupQuery $query)
    {
        /* read query is allowed to bypass aggregate and go directly to repo */
        $group = $this->repo->find($query->getGroupId()->getId());
        if(! $group) {
            return ["id"=>null, "carId"=>null];
        }
        return ["id"=>$group->getId(), "carId" => $group->getCar()?->getId()];
    }
}
