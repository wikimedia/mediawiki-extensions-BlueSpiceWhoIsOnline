<?php

namespace BlueSpice\WhoIsOnline\Tag;

use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MWStake\MediaWiki\Component\GenericTagHandler\ClientTagSpecification;
use MWStake\MediaWiki\Component\GenericTagHandler\GenericTag;
use MWStake\MediaWiki\Component\GenericTagHandler\ITagHandler;
use MWStake\MediaWiki\Component\InputProcessor\Processor\StringValue;

class Popup extends GenericTag {

	/** @var int */
	private int $counter = 0;

	/**
	 * @inheritDoc
	 */
	public function getTagNames(): array {
		return [
			'userslink',
			'bs:whoisonline:popup',
			'bs:whoisonlinepopup',
		];
	}

	/**
	 * @return bool
	 */
	public function hasContent(): bool {
		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function getHandler( MediaWikiServices $services ): ITagHandler {
		$id = $this->counter++;
		return new PopupHandler( $services->getService( 'BSWhoIsOnlineTracer' ), $id );
	}

	/**
	 * @inheritDoc
	 */
	public function getParamDefinition(): ?array {
		$anchorText = new StringValue();

		return [
			'anchortext' => $anchorText,
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getClientTagSpecification(): ClientTagSpecification|null {
		return new ClientTagSpecification(
			'Whoisonlinepopup',
			Message::newFromKey( 'bs-whoisonline-tag-whoisonlinepopup-description' ),
			null,
			Message::newFromKey( 'bs-whoisonline-ve-whoisonlinepopupinspector-title' )
		);
	}
}
