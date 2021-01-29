<?php

namespace Drupal\aaronpero_tools;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Path\PathMatcher;

class AaronPeroTools {

  /**
   * The Entity Type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Logger factory channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * Path Matcher.
   *
   * @var \Drupal\Core\Path\PathMatcher
   */
  protected $pathMatcher;

  /**
   * Class constructor.
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    LoggerChannelFactoryInterface $loggerFactory,
    PathMatcher $pathMatcher) {

    $this->entityTypeManager = $entityTypeManager;
    $this->loggerFactory = $loggerFactory;
    $this->pathMatcher = $pathMatcher;
  }

  /**
   * Return the Entity Type Manager Interface.
   */
  public function entityTypeManager() {
    return $this->entityTypeManager;
  }

  /**
   * Return the Logger Factory Interface.
   */
  public function loggerFactory() {
    return $this->loggerFactory;
  }

  /**
   * Return the Path matcher service.
   */
  public function pathMatcher() {
    return $this->pathMatcher;
  }

}