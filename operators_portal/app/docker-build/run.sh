#!/bin/bash

git clone https://github.com/My-TRAC/ConfigurationScripts.git 

chmod +x ./ConfigurationScripts/*.sh

/ConfigurationScripts/waitForSchemaRegistry.sh
/ConfigurationScripts/waitForKafkaConenct.sh
/ConfigurationScripts/waitForMySQL.sh

mysql -h$MYSQL_HOST -uroot -p$MYSQL_PASSWORD connect_test<create.sql


/ConfigurationScripts/setJDBCConnector.sh/setJDBCSourceConnector.sh -c "cigo-jdbc-source_OperatorsPlatform"\
                                                -k $KAFKA_CONNECT_HOST\
                                                -m $MYSQL_HOST\
                                                -d $MYSQL_DATABASE\
                                                -u $MYSQL_USER\
                                                -p $MYSQL_PASSWORD\
                                                -i "mytrac_id"\
                                                -t "mytrac_last_modified"

sh /startup.sh
