REGISTRY ?= registry.gitlab.com/aristidesbneto/image-registry/fcontrol-api
GIT_HASH ?= $(shell git log --format="%h" -n 1)
BRANCH_NAME ?= $(shell git rev-parse --abbrev-ref HEAD)

build: docker-build docker-push

docker-build:
	docker build . -t ${REGISTRY}/${BRANCH_NAME}:${GIT_HASH}
	docker build . -t ${REGISTRY}-cli/${BRANCH_NAME}:${GIT_HASH} --target cli

docker-push:
	docker push ${REGISTRY}/${BRANCH_NAME}:${GIT_HASH}
	docker push ${REGISTRY}-cli/${BRANCH_NAME}:${GIT_HASH}