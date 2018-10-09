#FROM golang:1.7.6-alpine3.6 as builder
#
#COPY . /go/src/bitbucket.org/sparsitytechnologies/webapp-joan
#
#WORKDIR /go/src/bitbucket.org/sparsitytechnologies/webapp-joan
#
#RUN apk --no-cache add git openssh-client glide && \
#    glide cc && glide update && go build
#RUN apk --no-cache add ca-certificates
#
##FROM alpine:3.6 as final
#FROM java:8-jre as final
#
#
#
#
#RUN apt-get update && \
#    apt-get install -y mysql-client
#
#COPY ./docker-build/scripts /opt
#
##RUN chmod -R +x             /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/opt/scripts
##
##RUN /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/opt/scripts/waitForSchemaRegistry.sh
##RUN /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/opt/scripts/waitForKafkaConenct.sh
##RUN /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/opt/scripts/waitForMySQL.sh
##RUN /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/opt/scripts/setJDBCSinkConnector.sh
##
##
#
#WORKDIR /root/
#COPY --from=builder /go/src/bitbucket.org/sparsitytechnologies/webapp-joan ./webapp-joan
#COPY --from=builder /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/conf ./conf
#COPY --from=builder /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/static ./static
#COPY --from=builder /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/views ./views
#EXPOSE 8080
#CMD env PORT=8080 ./webapp-joan



#FROM golang:1.7.6-alpine3.6 as builder
FROM golang:1.8 as builder

COPY . /go/src/bitbucket.org/sparsitytechnologies/webapp-joan

WORKDIR /go/src/bitbucket.org/sparsitytechnologies/webapp-joan
RUN curl https://glide.sh/get | sh

RUN apt-get install git openssh-client  && \
    glide cc && glide update && go build
#RUN apk --no-cache add git openssh-client glide && \
#    glide cc && glide update && go build




FROM java:8-jre as final
RUN apt-get update &&  apt-get install -y mysql-client


WORKDIR /root/
COPY ./docker-build/scripts ./scripts
COPY ./docker-build/run.sh ./
#RUN ./scripts/waitForSchemaRegistry.sh
#RUN ./scripts/waitForKafkaConenct.sh
#RUN ./scripts/waitForMySQL.sh
#RUN ./scripts/setJDBCSinkConnector.sh


#FROM alpine:3.6 as final
#RUN apk --no-cache add ca-certificates
#
#WORKDIR /root/
COPY --from=builder /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/webapp-joan ./webapp-joan
COPY --from=builder /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/conf ./conf
COPY --from=builder /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/static ./static
COPY --from=builder /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/views ./views
EXPOSE 8080
#CMD env PORT=8080 ./webapp-joan
ENTRYPOINT ["/root/run.sh"]
CMD [""]






