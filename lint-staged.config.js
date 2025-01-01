export default {
    '**/*.php': ['vendor/bin/duster lint'],
    '**/*': 'prettier --check --ignore-unknown',
};
