```composer install```
В файле .env нужно указать подключение к базе данных:
DATABASE_URL=pgsql://postgres:postgres@localhost:5432/cmexpert

```bin/console doctrine:database:create```

```bin/console doctrine:schema:update --force```

```bin/console database:fill```

```bin/phpunit``` -- убедиться, что всё в порядке

`/car/create` заведение автомобиля                      
`/car/{id}` просмотр автомобиля
`/car/{id}/update` изменение автомобиля

`/appraising-cars/create/{carId}` создание оценки автомобиля
`/appraising-cars/{id}` просмотр композитной сущности
`/appraising-cars/{id}/refresh` повторный расчёт медианной стоимости
`/appraising-cars/{id}/update` обновление композитной сущности
`/appraising-cars/list/{page}` просмотр списка сущностей
     