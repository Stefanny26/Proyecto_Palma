#!/bin/bash

# Variables
BACKUP_DIR=/backups
TIMESTAMP=$(date +%F-%H-%M-%S)
BACKUP_FILE=$BACKUP_DIR/db-backup-$TIMESTAMP.sql
REMOTE_SERVER=user@remote-server-ip
REMOTE_DIR=/remote/backup/dir
REMOTE_DB_CONTAINER=my_postgres_container_remote
REMOTE_DB_USER=myuser
REMOTE_DB_NAME=mydatabase

# Crear directorio de backup si no existe
mkdir -p $BACKUP_DIR

# Realizar backup
docker exec -t my_postgres_container pg_dumpall -c -U $REMOTE_DB_USER > $BACKUP_FILE

# Transferir backup al servidor remoto
scp -i /root/.ssh/id_rsa $BACKUP_FILE $REMOTE_SERVER:$REMOTE_DIR

# Restaurar backup en el servidor remoto
ssh -i /root/.ssh/id_rsa $REMOTE_SERVER "docker exec -i $REMOTE_DB_CONTAINER psql -U $REMOTE_DB_USER -d $REMOTE_DB_NAME < $REMOTE_DIR/$(basename $BACKUP_FILE)"

# Eliminar backups antiguos (opcional, mantener solo los últimos 7 backups)
find $BACKUP_DIR -type f -mtime +7 -name '*.sql' -exec rm {} \;
