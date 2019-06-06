FROM python:3.7.3-slim as base

ENV VERSION=0.0.1

ENV PYTHONUNBUFFERED=true
ENV PYTHONDONTWRITEBYTECODE=true

WORKDIR /src/app

COPY . .

RUN pip install -U pip setuptools wheel \
    && pip install --no-cache-dir -r ./python/requirements.txt \
    && python setup.py sdist bdist_wheel --universal \
    && rm -rf build/ libero_schemas.egg-info/

CMD ["pytest"]


FROM base as test_sdist

RUN rm -rf api/ core/ extensions/ libero_schemas/ \
    && pip install dist/libero-schemas-${VERSION}.tar.gz


FROM base as test_bdist

RUN rm -rf api/ core/ extensions/ libero_schemas/ \
    && pip install dist/libero_schemas-${VERSION}-py2.py3-none-any.whl


FROM base as test_git

RUN pip install -e .
