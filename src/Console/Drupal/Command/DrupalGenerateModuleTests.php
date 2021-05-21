<?php

namespace Trupal\Console\Drupal\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Trupal\Console\Command\Command;

/**
 * Generates tests for a Drupal module.
 * @package Trupal\Console\Drupal\Command
 */
class DrupalGenerateModuleTests extends Command {

  protected static $defaultName = "generate:drupal:module";

  /**
   * {@inheritDoc}
   */
  protected function doExecute(InputInterface $input, OutputInterface $output) {
    $module = $input->getArgument('module');
    if (!$drupal_root = rtrim($input->getOption('drupal-root'), DIRECTORY_SEPARATOR)) {
      $drupal_root = rtrim($this->getDefaultDrupalRoot(), DIRECTORY_SEPARATOR);
    }

    if (!file_exists($drupal_root)) {
      throw new \InvalidArgumentException('The Drupal root folder could not be determined or does not exist.');
    }

    if (!$module_root = trim($input->getOption('module-root'), DIRECTORY_SEPARATOR)) {
      $module_root = trim($this->getDefaultModuleRoot(), DIRECTORY_SEPARATOR);
    }

    $test_root = implode(DIRECTORY_SEPARATOR, [$drupal_root, $module_root, $module, 'tests']);
    $subject_root = implode(DIRECTORY_SEPARATOR, [$test_root, 'trupal']);
    $output_root = $test_root;

    $output->writeln($module);
    $output->writeln(implode('', array_fill(0, strlen($module), '=')));

    $generated_tests = $this->trupal()->generate($subject_root, $output_root);
    foreach ($generated_tests as $filepath) {
      $output->writeln($filepath);
    }
    $output->writeln(sprintf('Generated %s tests.', count($generated_tests)));
  }

  /**
   * Get the default Drupal root path.
   *
   * @return string
   *   A path string.
   */
  protected function getDefaultDrupalRoot() {
    $root = rtrim($this->trupal()->root()->systemPath(), DIRECTORY_SEPARATOR);
    return implode(DIRECTORY_SEPARATOR, [$root, '..', '..', '..']);
  }

  /**
   * Get the default module root path.
   *
   * @return string
   *   A path string.
   */
  protected function getDefaultModuleRoot() {
    return "modules/custom";
  }

  /**
   * {@inheritDoc}
   */
  protected function configure() {
    parent::configure();
    $this
      ->setDescription('Generates test cases from subject defined by the given Drupal module.');
    $this
      ->addArgument('module', InputArgument::REQUIRED, 'Machine name of the Drupal module.')
      ->addOption('drupal-root', 'd', InputOption::VALUE_OPTIONAL, 'The Drupal root')
      ->addOption('module-root', 'm', InputOption::VALUE_OPTIONAL,'The module root, relative from the Drupal root');
  }


}
