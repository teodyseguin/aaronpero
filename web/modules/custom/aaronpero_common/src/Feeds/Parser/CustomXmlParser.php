<?php

namespace Drupal\aaronpero_common\Feeds\Parser;

use Drupal\feeds\FeedInterface;
use Drupal\feeds\Feeds\Item\DynamicItem;
use Drupal\feeds\Result\FetcherResultInterface;
use Drupal\feeds\Result\ParserResultInterface;
use Drupal\feeds\StateInterface;
use Drupal\feeds_ex\Feeds\Parser\XmlParser;

/**
 * Defines a XML parser using XPath.
 *
 * @FeedsParser(
 *   id = "custom_xml",
 *   title = @Translation("Custom XML"),
 *   description = @Translation("Parse XML with XPath.")
 * )
 */
class CustomXmlParser extends XmlParser {

  /**
   * {@inheritDoc}
   */
  protected function parseItems(FeedInterface $feed, FetcherResultInterface $fetcher_result, ParserResultInterface $result, StateInterface $state) {
    $expressions = $this->prepareExpressions();
    $variable_map = $this->prepareVariables($expressions);
    foreach ($this->executeContext($feed, $fetcher_result, $state) as $row) {
      if ($item = $this->executeSources($row, $expressions, $variable_map)) {
        $item_array = $item->toArray();
        foreach ($item_array['images_image_isfloorplan'] as $key => $value) {
          if ($value === 'True') {
            $item_array['images_image_description'][$key] = 'floorplan';
          }
        }
        $item->fromArray($item_array);
        $result->addItem($item);
      }
    }
  }

  /**
   * {@inheritDoc}
   */
  protected function prepareExpressions() {
    $expressions = [];
    foreach ($this->configuration['sources'] as $machine_name => $source) {
      if (strlen($source['value'])) {
        $expressions[$machine_name] = $source['value'];
      }
    }
    $expressions['images_image_isfloorplan'] = 'Images/Image/IsFloorPlan';
    return $expressions;
  }

  /**
   * Executes the source expressions.
   *
   * @param mixed $row
   *   A single item returned from the context expression.
   * @param array $expressions
   *   A map of machine name to expression.
   * @param array $variable_map
   *   A map of machine name to varible name.
   *
   * @return array
   *   The fully-parsed item array.
   */
  protected function executeSources($row, array $expressions, array $variable_map) {

    $item = new DynamicItem();
    $variables = [];

    foreach ($expressions as $machine_name => $expression) {
      // Variable substitution.
      $expression = strtr($expression, $variables);

      $features_data_names = $this->executeSourceExpression(
        'attributedata_features_feature_name',
        'AttributeData/Features/Feature/Name',
        $row);
      $features_data_values = $this->executeSourceExpression(
        'attributedata_features_feature_value',
        'AttributeData/Features/Feature/Value',
        $row);
      $features_complicated_names = [
        'Approx Floor Area' => 'attributedata_features_feature_chattels_approxfloorarea',
        'Property Type' => 'attributedata_features_feature_propertytype',
        'Property Features' => 'Property Features',
        'Approx year built' => 'attributedata_features_feature_yearbuilt',
        'House style' => 'attributedata_features_feature_housestyle',
        'Unit style' => 'Unit style',
        'Garaging / carparking' => 'attributedata_features_feature_garagingcarpark',
        'Heating / Cooling' => 'attributedata_features_feature_heatingcooling',
        'Chattels remaining' => 'Chattels remaining',
        'Living area' => 'attributedata_features_feature_livingarea',
        'Main bedroom' => 'attributedata_features_feature_mainbedroom',
        'Bedroom 2' => 'attributedata_features_feature_bedroom2',
        'Bedroom 3' => 'attributedata_features_feature_bedroom3',
        'Bedroom 4' => 'attributedata_features_feature_bedroom4',
        'Main bathroom' => 'Main bathroom',
        'Outdoor living' => 'Outdoor living',
        'Land contour' => 'Land contour',
        'Water heating' => 'Water heating',
        'Water supply' => 'Water supply',
        'Legal details' => 'Legal details',
      ];
      if (substr($expression, 0, 22) == 'AttributeData/Features') {
        $exploded = explode('/', $expression);
        $key = array_search(end($exploded), $features_data_names);
        if ($key) {
          $variables[$machine_name] = $features_data_values[$key];
          $item->set($machine_name, $features_data_values[$key]);
        }
        else {
          $another_key = array_search($machine_name, $features_complicated_names);
          if ($another_key) {
            $one_more_key = array_search($another_key, $features_data_names);
            if ($one_more_key) {
              $value = trim(str_replace("\r\n", "", $features_data_values[$one_more_key]));
              $variables[$machine_name] = $value;
              $item->set($machine_name, $value);
            }
          }
        }
      }
      elseif ($expression == 'ListingStatus') {
        $variables[$machine_name] = 'Available';
        $item->set($machine_name, 'Available');
      }
      elseif ($expression == 'CreationMethod') {
        $variables[$machine_name] = 'Auto RSS Feed';
        $item->set($machine_name, 'Auto RSS Feed');
      }
      elseif ($expression == 'PropertyLink') {
        $vals = \Drupal::entityQuery('node')
          ->condition('type', 'property')
          ->condition('field_listing_number', $variables['$listingnumber'])
          ->execute();
        if ($vals) {
          $variables[$machine_name] = '/node/' . $vals[0];
          $item->set($machine_name, '/node/' . $vals[0]);
        }
      }
      else {
        $result = $this->executeSourceExpression(
          $machine_name,
          $expression,
          $row
        );

        if (!empty($this->configuration['sources'][$machine_name]['debug'])) {
          $this->debug($result, $machine_name);
        }

        if ($result === NULL) {
          $variables[$variable_map[$machine_name]] = '';
          continue;
        }

        $item->set($machine_name, $result);
        $variables[$variable_map[$machine_name]] = is_array($result) ? reset(
          $result
        ) : $result;
      }
    }

    return $item;
  }

}
