#!/bin/bash

cp -r ../src ./src

docker build -t sparsitytechnologies/operatorsportal:latest .
docker push sparsitytechnologies/operatorsportal:latest

rm -rf ./src

