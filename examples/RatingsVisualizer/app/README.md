##### Deploy with docker-dev environment

### Build
    docker build --no-cache -f Dockerfile -t ratingsvisualizer  .
### RUN
    docker run -e BEEGO_RUNMODE=docker-dev -e MS_TO_REFRESH=30000 -e NUM_RANDOM_SAMPLES=5000 -p 8080:8080  --name ratingsvisualizer -d ratingsvisualizer


