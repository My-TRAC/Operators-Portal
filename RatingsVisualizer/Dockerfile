FROM golang:1.7.6-alpine3.6 as builder

COPY . /go/src/bitbucket.org/sparsitytechnologies/webapp-joan

WORKDIR /go/src/bitbucket.org/sparsitytechnologies/webapp-joan
RUN apk --no-cache add git openssh-client glide && \
    glide cc && glide update && go build

FROM alpine:3.6 as final
RUN apk --no-cache add ca-certificates
WORKDIR /root/
COPY --from=builder /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/webapp-joan ./webapp-joan
COPY --from=builder /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/conf ./conf
COPY --from=builder /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/static ./static
COPY --from=builder /go/src/bitbucket.org/sparsitytechnologies/webapp-joan/views ./views
EXPOSE 8080
CMD env PORT=8080 ./webapp-joan
