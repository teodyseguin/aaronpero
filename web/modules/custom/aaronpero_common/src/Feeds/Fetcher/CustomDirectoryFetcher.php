<?php

namespace Drupal\aaronpero_common\Feeds\Fetcher;

use Drupal\feeds\Exception\EmptyFeedException;
use Drupal\feeds\FeedInterface;
use Drupal\feeds\Feeds\Fetcher\DirectoryFetcher;
use Drupal\feeds\Plugin\Type\Fetcher\FetcherInterface;
use Drupal\feeds\Plugin\Type\PluginBase;
use Drupal\Core\File\FileSystemInterface;
use Drupal\feeds\Result\FetcherResult;
use Drupal\feeds\StateInterface;
use Drupal\feeds\Utility\File;

/**
 * Defines a directory fetcher.
 *
 * @FeedsFetcher(
 *   id = "custom_directory",
 *   title = @Translation("Custom Directory"),
 *   description = @Translation("Uses a directory, or file, on the server."),
 *   form = {
 *     "configuration" = "Drupal\feeds\Feeds\Fetcher\Form\DirectoryFetcherForm",
 *     "feed" = "\Drupal\feeds\Feeds\Fetcher\Form\DirectoryFetcherFeedForm",
 *   },
 * )
 */
class CustomDirectoryFetcher extends DirectoryFetcher {

  /**
   * {@inheritdoc}
   */
  public function fetch(FeedInterface $feed, StateInterface $state) {
    if (!isset($state->files)) {
      $this->preFetchParser();
    }
    $path = $feed->getSource();
    // Just return a file fetcher result if this is a file. Make sure to
    // re-validate the file extension in case the feed type settings have
    // changed.
    if (is_file($path)) {
      if (File::validateExtension($path, $this->configuration['allowed_extensions'])) {
        return new FetcherResult($path);
      }
      else {
        throw new \RuntimeException($this->t('%source has an invalid file extension.', ['%source' => $path]));
      }
    }

    if (!is_dir($path) || !is_readable($path)) {
      throw new \RuntimeException($this->t('%source is not a readable directory or file.', ['%source' => $path]));
    }

    // Batch if this is a directory.
    if (!isset($state->files)) {
      $state->files = $this->listFiles($path);
      $state->total = count($state->files);
    }
    if ($state->files) {
      $file = array_shift($state->files);
      $state->progress($state->total, $state->total - count($state->files));
      return new FetcherResult($file);
    }

    throw new EmptyFeedException();
  }

  /**
   * Performs few fetches of XML paged list of listings.
   *
   * @return array
   *   List of listings to  be updated.
   */
  protected function preFetchParser() {
    $listings_dir = 'public://listings_feeds/';

    \Drupal::service('file_system')->deleteRecursive($listings_dir);
    \Drupal::service('file_system')->mkdir($listings_dir);

    $parser = xml_parser_create();
    $string = file_get_contents('https://harcourts.co.nz/Listing/Rss?StaffID=32903');
    xml_parse_into_struct($parser, $string, $values, $index);
    xml_parser_free($parser);

    // To prevent memory leak.
    unset($parser);

    $listings_to_update = [];
    $pages = ceil($values[$index['LISTINGS'][0]]['attributes']['TOTALCOUNT'] / 15); // 15 items shown per page
    for ($i = 1; $i <= $pages; $i++) {
      if ($i == 1) {
        foreach ($index['LISTING'] as $listing) {
          $listing_url = 'https://harcourts.co.nz/Listing/XML/' . $values[$listing]['attributes']['LISTINGNUMBER'];
          $my_stream = file_get_contents($listing_url);
          $destination = $listings_dir . $values[$listing]['attributes']['LISTINGNUMBER'] . '.xml';
          file_save_data($my_stream, $destination, FileSystemInterface::EXISTS_REPLACE);
        }
      }
      else {
        $string = file_get_contents('https://harcourts.co.nz/Listing/Rss?StaffID=32903&page=' . $i);
        $parser = xml_parser_create();
        xml_parse_into_struct(xml_parser_create(), $string, $values, $index);
        xml_parser_free($parser);

        // To prevent memory leak.
        unset($parser);

        foreach ($index['LISTING'] as $listing) {
          $listing_url = 'https://harcourts.co.nz/Listing/XML/' . $values[$listing]['attributes']['LISTINGNUMBER'];
          $my_stream = file_get_contents($listing_url);
          $destination = $listings_dir . $values[$listing]['attributes']['LISTINGNUMBER'] . '.xml';
          file_save_data($my_stream, $destination, FileSystemInterface::EXISTS_REPLACE);
        }
      }
    }
    return $listings_to_update;
  }

}
