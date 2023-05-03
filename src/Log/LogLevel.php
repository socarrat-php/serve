<?php

namespace Socarrat\Core\Log;

/**
 * Log levels for use with the logger.
 *
 * These log levels are loosely conform RFC 5424, except that Alert and Emergency are left out for the sake of simplicity.
 *
 * @see https://datatracker.ietf.org/doc/html/rfc5424
 */
enum LogLevel: int {
	/** Critical: critical conditions */
	case LOG_CRITICAL = 2;

	/** Error: error conditions */
	case LOG_ERROR = 3;

	/** Warning: warning conditions */
	case LOG_WARNING = 4;

	/** Notice: normal but significant condition */
	case LOG_NOTICE = 5;

	/** Informational: informational messages */
	case LOG_INFO = 6;

	/** Debug: debug-level messages */
	case LOG_DEBUG = 7;
}
