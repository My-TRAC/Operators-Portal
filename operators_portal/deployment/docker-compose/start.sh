#!/bin/bash

#
#docker-machine create --driver virtualbox --virtualbox-memory 12000 operatorsplatform
#
#eval $(docker-machine env operatorsplatform)
#
#docker-machine ssh operatorsplatform -- "sudo sysctl -w vm.max_map_count=262144"
#
#docker network create oper-compose_default
#
#export CONNECT_HOST=`docker-machine ip operatorsplatform`
#
#
#docker-compose up -d
##docker run --name myXampp -p 41061:22 -p 41062:80 -d -v /Users/joan/DAMA/My-TRAC/Operators-Portal/operators_portal/app/src:/www/operators_portal -network tomsik68/xampp
#
#
#~
#                                                                                                                                                                                                                                                            1,1           All

if [[ $OSTYPE == *"darwin"* ]]
then
  eval $(docker-machine env cigo)
fi

docker-compose up -d

