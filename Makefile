docker-up:
		sudo sysctl -w vm.max_map_count=262144
		sudo docker-compose up -d

docker-build:
		sudo sysctl -w vm.max_map_count=262144
		sudo docker-compose up --build -d

docker-down:
		sudo docker-compose down
