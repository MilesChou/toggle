workflow "Test the Toggle package" {
  on = "push"
  resolves = [
    "Send Discord message",
  ]
}

action "Install" {
  uses = "MilesChou/composer-action@master"
  args = "install"
}

action "Check coding style" {
  needs = "Install"
  uses = "docker://php:7.3"
  args = "php vendor/bin/phpcs"
}

action "Test on PHP 7.3" {
  needs = "Check coding style"
  uses = "docker://php:7.3"
  args = "php vendor/bin/phpunit"
}

action "Test on PHP 7.2" {
  needs = "Check coding style"
  uses = "docker://php:7.2"
  args = "php vendor/bin/phpunit"
}

action "Test on PHP 7.1" {
  needs = "Check coding style"
  uses = "docker://php:7.1"
  args = "php vendor/bin/phpunit"
}

action "Test on PHP 7.0" {
  needs = "Check coding style"
  uses = "docker://php:7.0"
  args = "php vendor/bin/phpunit"
}

action "Test on PHP 5.6" {
  needs = "Check coding style"
  uses = "docker://php:5.6"
  args = "php vendor/bin/phpunit"
}

action "Test on PHP 5.5" {
  needs = "Check coding style"
  uses = "docker://php:5.5"
  args = "php vendor/bin/phpunit"
}

action "Send Discord message" {
  uses = "appleboy/discord-action@master"
  needs = [
    "Test on PHP 7.3",
    "Test on PHP 7.2",
    "Test on PHP 7.1",
    "Test on PHP 7.0",
    "Test on PHP 5.6",
    "Test on PHP 5.5",
  ]
  secrets = [
    "WEBHOOK_ID",
    "WEBHOOK_TOKEN",
  ]
  args = "A new commit has been push to `MilesChou/toggle`."
}
