#!/bin/bash

git clone https://github.com/My-TRAC/ConfigurationScripts.git 

chmod +x ./ConfigurationScripts/*.sh

/ConfigurationScripts/waitForSchemaRegistry.sh
/ConfigurationScripts/waitForKafkaConenct.sh
/ConfigurationScripts/waitForMySQL.sh

mysql -h$MYSQL_HOST -uroot -p$MYSQL_PASSWORD connect_test<create.sql

/ConfigurationScripts/setJDBCConnector.sh  cigo-jdbc-source_OperatorsPlatform CigoJdbc 

sh /startup.sh
