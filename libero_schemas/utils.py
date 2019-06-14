from pathlib import Path


class MultipleFilesFound(Exception):
    pass


def get_schema_file_path(name: str) -> str:
    path = Path(Path().cwd().root)
    matches = [match for match in path.rglob(name)]
    if len(matches) > 1:
        message = 'Multiple files found: ' + ' '.join([str(m) for m in matches])
        raise MultipleFilesFound(message)
    elif matches:
        return str(matches[0])
    raise FileNotFoundError(f'Unable to find {name}')
