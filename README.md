# laravel-job-visibility
Позволяет получать в событиях очередей экземпляр выполняемой задачи.
Для получения можно воспользоваться следующим примером:
```php
Queue::after(function (JobProcessed $event) {
    if ($event->job instanceof InstanceReturner) {
        $handler = $event->job->getInstance();
        if ($handler instanceof JobReturner) {
            $job = $handler->getJob();
            // $job - экземпляр выполняемой задачи, т.е. сохраняет состояние после выполнения задачи
        }
    }
    
    // ...

    return true;
});
```