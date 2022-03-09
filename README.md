# Configuration

Переопределение штатного класса сущности

    contractor:
        db_driver: orm модель данных
        factory: App\Tracker\Factory\TrackerFactory фабрика для создания объектов,
                 недостающие значения можно разрешить на уровне Mediator или переопределив фабрику
        entity: App\Tracker\Entity\Tracker сущность
        dto_class: App\Tracker\Dto\TrackerDto класс dto с которым работает сущность

# CQRS model

Actions в контроллере разбиты на две группы
создание, удаление данных

        1. postAction(POST), deleteAction(DELETE)
Получение данных

        2. getAction(GET), criteriaAction(GET)

Редактирование сущности Tracker не подразумевается

Каждый метод работает со своим менеджером

        1. CommandManagerInterface
        2. QueryManagerInterface

При переопределении штатного класса сущности, дополнение данными осуществляется декорированием, с помощью MediatorInterface


Группы сериализации

    1. api_get_tracker - получение Tracker
    2. api_post_tracker - создание Tracker
    

# Статусы:

    создание:
        Tracker создан HTTP_CREATED 201
    удаление:
        Tracker удален HTTP_ACCEPTED 202
    получение:
        Tracker(ы) найдены HTTP_OK 200
    ошибки:
        если Tracker не найден TrackerNotFoundException возвращает HTTP_NOT_FOUND 404
        если Tracker не уникален UniqueConstraintViolationException возвращает HTTP_CONFLICT 409
        если Tracker не прошел валидацию TrackerInvalidException возвращает HTTP_UNPROCESSABLE_ENTITY 422
        если Tracker не может быть сохранен TrackerCannotBeSavedException возвращает HTTP_NOT_IMPLEMENTED 501
        все остальные ошибки возвращаются как HTTP_BAD_REQUEST 400

# Constraint

Для добавления проверки поля сущности tracker нужно описать логику проверки реализующую интерфейс Evrinoma\UtilsBundle\Constraint\Property\ConstraintInterface и зарегистрировать сервис с этикеткой demoniqus.tracker.constraint.property

    demoniqus.tracker.constraint.property.custom:
        class: App\Tracker\Constraint\Property\Custom
        tags: [ 'demoniqus.tracker.constraint.property' ]

# Тесты:

    composer install --dev

### run all tests

    /usr/bin/php vendor/phpunit/phpunit/phpunit --bootstrap src/Tests/bootstrap.php --configuration phpunit.xml.dist src/Tests --teamcity

### run personal test for example testPost

    /usr/bin/php vendor/phpunit/phpunit/phpunit --bootstrap src/Tests/bootstrap.php --configuration phpunit.xml.dist src/Tests/Functional/Controller/TypeApiControllerTest.php --filter "/::testPost( .*)?$/" 

