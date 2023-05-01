<?php
/**
 * Event.php: Contains the socarrat event base class.
 *
 * @author Romein van Buren
 * @license MIT
 */

namespace Socarrat\Core;

/** Base class for events. */
abstract class Event {
	/**
	 * An array of event listeners to call when the event is dispatched.
	 * @var callable[]
	 */
	abstract static protected array $listeners = array();

	/** Returns the name of the event. */
	static function getName(): string {
		return get_class();
	}

	/**
	 * Registers an event listener for the current event.
	 *
	 * @param int $priority The priority of this listener.
	 * @param $callback The function to execute when the event is fired.
	 * @return int The index that has been assigned to the listener.
	 */
	static function on(int $priority, $callback): int {
		if (!is_callable($callback)) {
			// @todo: error
		}
		if ($priority < 0) {
			// @todo: error
		}

		if (!isset(static::$listeners)) {
			static::$listeners = array();
		}

		// Find open position
		while (isset(static::$listeners[$priority])) {
			$priority++;
		}

		static::$listeners[$priority] = $callback;
		ksort(static::$listeners);
		return $priority;
	}

	/**
	 * Dispatch the event.
	 *
	 * This executes all registered callbacks in order, passing optional callback data to them.
	 *
	 * Warning: this is a blocking function; it will stop execution until all callbacks have finished. Callbacks are executed synchronously.
	 *
	 * @param mixed $callbackData Data to pass to the callbacks. Optional.
	 */
	static function dispatch(...$callbackData) {
		if (!isset(static::$listeners)) {
			return;
		}

		foreach (static::$listeners as $callback) {
			$callback(...$callbackData);
		}
	}
}
