#!/bin/bash

git clone https://github.com/My-TRAC/ConfigurationScripts.git
chmod +x ./ConfigurationScripts/*.sh


/root/ConfigurationScripts/waitForSchemaRegistry.sh
/root/ConfigurationScripts/waitForKafkaConenct.sh
/root/ConfigurationScripts/waitForMySQL.sh
/root/ConfigurationScripts/setJDBCSinkConnector.sh cigo-jdbc-sink_RatingsVisualizer
#/root/ConfigurationScripts/infinite.sh
env PORT=8080 /root/webapp-joan
