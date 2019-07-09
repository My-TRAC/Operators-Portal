#!/bin/bash

git clone https://github.com/My-TRAC/ConfigurationScripts.git
chmod +x ./ConfigurationScripts/*.sh


/root/ConfigurationScripts/waitForSchemaRegistry.sh
/root/ConfigurationScripts/waitForKafkaConenct.sh
/root/ConfigurationScripts/waitForMySQL.sh
setJDBCSinkConnector.sh 

/root/ConfigurationScripts/setJDBCSinkConnector.sh  -c "cigo-jdbc-sink_RatingsVisualizer"\
                                                    -k $KAFKA_CONNECT_HOST\
                                                    -s $SCHEMA_REGISTRY_HOST_NAME\
                                                    -n $SINK_TOPICS\
                                                    -m $MYSQL_HOST\
                                                    -d $MYSQL_DATABASE\
                                                    -u $MYSQL_USER\
                                                    -p $MYSQL_PASSWORD\
                                                    -pk "mytrac_id"\
                                                    -ac "true"
#/root/ConfigurationScripts/infinite.sh
env PORT=8080 /root/webapp-joan
