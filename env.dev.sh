PROJECT="montcelard"
DB_MIGRATION="./docker/mysql/migrations"
DB_USER="${PROJECT}_user"
DB_PASSWORD="${PROJECT}_password"
DB_NAME="$PROJECT"
DB_URL="docker-db"

JWT_SECRET="supersecretjwtpassword"

env_list=("PROJECT" "DB_MIGRATION" "DB_USER" "DB_PASSWORD" "DB_NAME" "DB_URL")


