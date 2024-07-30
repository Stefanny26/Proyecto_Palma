#!/bin/bash

# Variables
BACKUP_FILE="/C/xampp/htdocs/ProyectoU1_Gestión/backup/respaldo_$(date +'%Y%m%d%H%M%S').sql"
REMOTE_USER="admin123"
REMOTE_HOST="172.29.219.212"
REMOTE_BACKUP_DIR="/home/admin123/backups"
REMOTE_RESTORE_SCRIPT="/home/admin123/restore.sh"

# Ejecutar el respaldo
docker exec proyectou1_gestin-postgres-1 pg_dumpall -U postgres > $BACKUP_FILE

# Transferir el respaldo al servidor remoto
scp $BACKUP_FILE ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_BACKUP_DIR}

# Ejecutar el script de restauración en el servidor remoto
ssh ${REMOTE_USER}@${REMOTE_HOST} "bash ${REMOTE_RESTORE_SCRIPT} ${REMOTE_BACKUP_DIR}/$(basename $BACKUP_FILE)"
