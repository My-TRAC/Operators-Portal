#!/bin/bash

kubectl apply -f mysql-ratingsvisualizer-deployment.yaml
sleep 60
kubectl apply -f ratingsvisualizer-deployment.yaml
