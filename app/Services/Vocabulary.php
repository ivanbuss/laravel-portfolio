<?php

namespace App\Services;

use App\Term;
use App\Dictionary;


class Vocabulary {

	/**
	 * Get term by keyword and dictionary id
	 *
	 * @param $key
	 * @param $value
	 * @return static
	 */
	public function getTerm($key, $value) {
		if (!empty($value)) {
			$dictionary = $this->getDictionary($key);
			$term = Term::where('dictionary_id', $dictionary->id)->where('value', $value)->first();
			if (!$term) {
				$term = Term::create([
					'dictionary_id' => $dictionary->id,
					'value' => $value,
				]);
			}
			return $term;
		} else return null;
	}

	/**
	 * Get dictionary by id
	 *
	 * @param $key
	 * @return static
	 */
  	public function getDictionary($key) {
		$dictionary = Dictionary::where('name', $key)->first();
		if (!$dictionary) {
	  		$dictionary = Dictionary::create([
				'name' => $key,
	  		]);
		}
		return $dictionary;
  	}


	public function getTerms($name, $isArray = FALSE) {
		$dictionary = $this->getDictionary($name);
		if ($isArray) return $dictionary->terms()->lists('value', 'term_id');
			else return $dictionary->terms;
	}

}