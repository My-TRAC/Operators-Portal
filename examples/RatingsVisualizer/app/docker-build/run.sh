#!/bin/bash

/root/scripts/waitForSchemaRegistry.sh
/root/scripts/waitForKafkaConenct.sh
/root/scripts/waitForMySQL.sh
/root/scripts/setJDBCSinkConnect.sh
#/root/scripts/infinite.sh
env PORT=8080 /root/webapp-joan
