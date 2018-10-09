#!/bin/bash



docker build -t sparsitytechnologies/ratingsvisualizer:latest -f Dockerfile ..
docker push sparsitytechnologies/ratingsvisualizer:latest

