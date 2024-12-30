export default {
    '**/*.php*,!_ide_helper.php,!.phpstorm.meta.php': ['vendor/bin/duster lint'],
    '**/*': 'prettier --check --ignore-unknown',
};
