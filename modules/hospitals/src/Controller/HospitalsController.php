<?php
namespace Drupal\hospitals\Controller;

use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\taxonomy\Entity\Term;

class HospitalsController {
  public function content() {
    $json_array = array(
      'data' => array()
    );
    $nids = \Drupal::entityQuery('node')->condition('type','hospitals')->execute();
    $nodes =  Node::loadMultiple($nids);
    foreach ($nodes as $node) {
		$tname = array();
		foreach($node->field_speciality->getValue() as $tid){
			
			$term = Term::load($tid['target_id']);
			$tname[] = $term->getName();
		}
		// update taxonomy
      $json_array['data'][] = array(
        'type' => $node->get('type')->target_id,
        'id' => $node->get('nid')->value,
        'attributes' => array(
          'title' =>  $node->get('title')->value,
          'field_address' =>  $node->get('field_address')->value,
          'field_phone_number' =>  $node->get('field_phone_number')->value,
          'field_speciality' =>  $tname,
         
        ),
      );
    }
    return new JsonResponse($json_array);
  }
}