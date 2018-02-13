# Symfony integration for the DateTimeProvider

This package provides integration of the [DateTimeProvider](https://github.com/Kdyby/DateTimeProvider) library into Symfony.

## Configuration

Integration is done by configuring the `type` parameter:
* `current_time`:
  * will register CurrentProvider and provide a current time at call-time
  * this is the default behavior
* `request_time`:
  * will register ConstantProvider and provide a fixed time obtained from the request
* `mutable_request_time`:
  * will register MutableProvider and provide a fixed time obtained from the request

For example:
```yaml
kdyby_datetime_provider:
    type: current_time
```
