<?php

namespace BlueSpice\WhoIsOnline\Tag;

use BlueSpice\Tag\Handler;
use BlueSpice\WhoIsOnline\Tracer;
use MediaWiki\Config\Config;
use MediaWiki\Html\Html;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\PPFrame;

class CountHandler extends Handler {

	/**
	 * @param string $processedInput
	 * @param array $processedArgs
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @param Tracer|null $tracer
	 */
	public function __construct(
		$processedInput, array $processedArgs, Parser $parser, PPFrame $frame, ?Tracer $tracer = null
	) {
		parent::__construct( $processedInput, $processedArgs, $parser, $frame );
		if ( !$tracer ) {
			$tracer = MediaWikiServices::getInstance()->getService( 'BSWhoIsOnlineTracer' );
		}
		$this->tracer = $tracer;
	}

	/**
	 * @return string
	 */
	public function handle() {
		$recordSet = $this->tracer->getTracedRecords();

		$this->parser->getOutput()->setPageProperty( 'bs-tag-userscount', 1 );
		return Html::element(
			'span',
			[ 'class' => 'bs-whoisonline-count' ],
			$recordSet->getTotal()
		);
	}

	/**
	 * @return Config
	 */
	protected function getConfig() {
		return MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'bsg' );
	}
}
