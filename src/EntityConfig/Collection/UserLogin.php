<?php

namespace BlueSpice\WhoIsOnline\EntityConfig\Collection;

use BlueSpice\ExtendedStatistics\Data\Entity\Collection\Schema;
use BlueSpice\Data\FieldType;
use BlueSpice\ExtendedStatistics\EntityConfig\Collection;
use BlueSpice\WhoIsOnline\Entity\Collection\UserLogin as Entity;

class UserLogin extends Collection {

	/**
	 *
	 * @return string
	 */
	protected function get_TypeMessageKey() {
		return 'bs-whoisonline-collection-type-userlogin';
	}

	/**
	 *
	 * @return array
	 */
	protected function get_VarMessageKeys() {
		return array_merge( parent::get_VarMessageKeys(), [
			Entity::ATTR_LOGIN => 'bs-whoisonline-collection-var-login',
		] );
	}

	/**
	 *
	 * @return string[]
	 */
	protected function get_Modules() {
		return array_merge( parent::get_Modules(), [
			'ext.bluespice.whoisonline.collection.userlogin',
		] );
	}

	/**
	 *
	 * @return string
	 */
	protected function get_EntityClass() {
		return "\\BlueSpice\\WhoIsOnline\\Entity\\Collection\\UserLogin";
	}

	/**
	 *
	 * @return array
	 */
	protected function get_AttributeDefinitions() {
		$attributes = parent::get_AttributeDefinitions();
		$attributes[ Entity::ATTR_LOGIN ] = [
			Schema::FILTERABLE => true,
			Schema::SORTABLE => true,
			Schema::TYPE => FieldType::INT,
			Schema::INDEXABLE => true,
			Schema::STORABLE => true,
			Schema::PRIMARY => true
		];
		return $attributes;
	}

}
