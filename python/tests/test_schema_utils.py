from pathlib import Path
from xml.etree import ElementTree

import pytest

from libero_schemas.utils import MultipleFilesFound, get_schema_file_path


@pytest.mark.parametrize('file_name', [
    'bold.rng',  # extensions/inline
    'core.rng',  # core
    'lang.rng',  # core/attributes
    'error.rng',  # api
    'item.rng',  # api/content
    'meta.rng'  # api/content/parts
])
def test_get_schema_file_path_returns_file_path(file_name) -> None:
    """
    Checks that the schemas are installed (including those in subdirectories),
    a file path is returned and can be parsed by lxml.
    """
    path = get_schema_file_path(file_name)
    assert Path(path).is_file()
    ElementTree.parse(path)  # will raise exception is file cannot be parsed


def test_get_schema_file_path_raises_multiple_files_found_exception(mocker) -> None:
    mocker.patch.object(Path,'rglob', return_value=['/path/to/schemas/test-file',
                                                    '/path/to/schemas/folder/test-file'])
    with pytest.raises(MultipleFilesFound):
        get_schema_file_path('test-file')


def test_get_schema_file_path_raises_file_not_found_exception(mocker) -> None:
    mocker.patch.object(Path, 'rglob', return_value=[])
    with pytest.raises(FileNotFoundError):
        get_schema_file_path('test-file')
