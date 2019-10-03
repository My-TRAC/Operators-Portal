#!/bin/bash

kubectl apply -f mysql-operators-platform-deployment.yaml
sleep 60
kubectl apply -f operators-platform-deployment.yaml
