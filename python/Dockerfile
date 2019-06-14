FROM python:3.7.3-slim as base
ENV VERSION=0.0.1
ENV PYTHONUNBUFFERED=true
ENV PYTHONDONTWRITEBYTECODE=true
WORKDIR /srv/app
COPY ./python/requirements.txt ./python/requirements.txt
RUN pip install -U pip setuptools wheel \
    && pip install --no-cache-dir -r ./python/requirements.txt
