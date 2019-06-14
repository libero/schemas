# Libero Schemas Python Package

Libero Schemas can be installed locally as a python package providing offline
access to schemas and access to schema utilities by adding 
`import libero_schemas` at the top of your python files.

## Dependencies

* [Docker](https://www.docker.com/)

## Run tests
From the project root run the following command to run tests on all distribution types:
```bash
docker-compose -f python/docker-compose.yml up
```
Or run the following command to run tests on the git distribution type:
```bash
docker-compose -f python/docker-compose.yml rm --rm test-git
```

## Getting help

- Report a bug or request a feature on [GitHub](https://github.com/libero/libero/issues/new/choose).
- Ask a question on the [Libero Community Slack](https://libero-community.slack.com/).
- Read the [code of conduct](https://libero.pub/code-of-conduct).
