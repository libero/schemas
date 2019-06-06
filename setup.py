from collections import defaultdict
from pathlib import Path
from setuptools import setup, find_packages


# copy data files to package using bdist_wheel
# sdist uses MANIFEST.in
data_files = defaultdict(list)
for path in Path('.').rglob('*.rng'):
    if 'tests' not in path.parts:
        data_files[str(path.parent)].append(str(path))


setup(
    name='libero-schemas',
    version='0.0.1',
    description='Libero data model schemas',
    url='https://github.com/libero/schemas',
    maintainer='eLife Sciences Publications, Ltd',
    license='MIT',
    packages= find_packages(exclude='tests'),
    data_files=[(k, v) for k, v in data_files.items()],
    zip_safe=False,
)
