# Checker-App-Issues

В случае появления ошибки:

> [!CAUTION]
> ✘ mysql Error Get "`https://registry-1.docker.io/v2/mysql/mysql-server/manifests/sha256:d6c8301b7834c5b9c2b733b10b7e6...`              3.9s <br>
> Error response from daemon: Get "`https://registry-1.docker.io/v2/mysql/mysql-server/manifests/sha256:d6c8301b7834c5b9c2b733b10b7e630f441af7bc917c74dba379f24eeeb6a313`": dial tcp 2600:1f18:2148:bc02:2640:1b90:cea6:b6b5:443: connect: network is unreachable

нужно в файл ```/etc/resolv.conf``` добавить следующие строки:
```
nameserver 8.8.8.8
nameserver 8.8.4.4
```
При появлении следующих ошибок:
> [!CAUTION]
> mysql-1         | [Entrypoint] running /docker-entrypoint-initdb.d/10-create-testing-database.sh <br>
> mysql-1         | mysql: [Warning] Using a password on the command line interface can be insecure. <br>
> mysql-1         | ERROR 1410 (42000) at line 2: You are not allowed to create a user with GRANT <br>

> [!CAUTION]
> mysql-1         | 2024-08-28T10:24:21.895384Z 0 [ERROR] [MY-010259] [Server] Another process with pid 60 is using unix socket file. <br>
> mysql-1         | 2024-08-28T10:24:21.895401Z 0 [ERROR] [MY-010268] [Server] Unable to setup unix socket lock file. <br>
> mysql-1         | 2024-08-28T10:24:21.895406Z 0 [ERROR] [MY-010119] [Server] Aborting <br>
> mysql-1 exited with code 1 <br>

Нужно очистить docker:
```
docker stop $(docker ps -qa) && docker rm $(docker ps -qa) && docker rmi -f $(docker images -qa) && docker volume rm $(docker volume ls -q) && docker network rm $(docker network ls -q)
```
И повторить команду:
```
./vendor/bin/sail up --build
```

