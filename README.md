# Trupal for Drupal
Drupal module to support automatic test generation using Trupal.

# Installation
`composer require --dev unb-libraries/dupal-trupal`

# Usage

## Generate a test case for a Drupal module

Place a subject definition in `/path/to/your/module/tests/trupal` and assign a unique name, e.g. `test_page.yml`:

```yaml
id: 'test_page'
type: 'page'
url: '/test/page/1'
public: FALSE
grant_access:
  - member
```

Next, run

`drush tgm test_module`

Which will generate tests for all `test_module` subjects. Rendered test cases will by default be placed under

`<DRUPAL_ROOT>/modules/test_module/tests/behat/features/test_page.feature`

If you assign a different ID than the one in the example, that ID will be used to name the rendered test case.

Now execute tests as you would otherwise.
