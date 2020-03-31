```
# コンテナ表示
docker ps

# コンテナに入る
docker exec -it {コンテナID} bash

# コンテナに入らずコマンド実行
docker exec -it {コンテナ名} /bin/bash -c "コマンド"

# 停止&削除
docker-compose down

# 滅びの呪文 (全削除)
docker-compose down --rmi all --volumes
```