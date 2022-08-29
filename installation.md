Run the containers:
```
docker-compose up -d
```
Install dependencies:
```
symfony composer install
```
create relations:
```
symfony console doctrine:migrations:migrate
```
create test database:
```
symfony console doctrine:database:create --env=test
```
create relations in test database:
```
symfony console doctrine:migrations:migrate -n --env=test
```
start local server:
```
symfony server:start -d
```
run tests:
```
symfony php bin/phpunit
```
