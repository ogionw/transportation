<?php
declare(strict_types=1);
namespace App\Application\Cqrs;

use App\Presentation\Message\Query;

interface QueryBusInterface
{
    public function handle(Query $query): mixed;
}
